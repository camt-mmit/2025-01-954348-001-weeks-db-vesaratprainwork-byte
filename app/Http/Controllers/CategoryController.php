<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Database\QueryException; // <-- เพิ่ม use statement นี้

class CategoryController extends SearchableController
{
    const int MAX_ITEMS = 5;

    #[\Override]
    function getQuery(): Builder
    {
        return Category::orderBy('code');
    }

    function list(ServerRequestInterface $request): View
    {
        Gate::authorize('list', Category::class);
        $criteria = $this->prepareCriteria($request->getQueryParams());
        $query = $this->search($criteria)->withCount('products');

        return view('categories.list', [
            'criteria' => $criteria,
            'categories' => $query->paginate(self::MAX_ITEMS),
        ]);
    }

    function view(string $categoryCode): View
    {
        $category = $this->find($categoryCode);
        Gate::authorize('view', $category); // <-- แก้ไข เพิ่ม Gate::authorize ที่ขาดไป

        return view('categories.view', [
            'category' => $category,
        ]);
    }

    function showCreateForm(): View
    {
        Gate::authorize('create', Category::class); // <-- แก้ไข เพิ่ม Gate::authorize ที่ขาดไป
        return view('categories.create-form');
    }

    function create(ServerRequestInterface $request): RedirectResponse
    {
        Gate::authorize('create', Category::class);
        try {
            $category = Category::create($request->getParsedBody());

            return redirect(
                session()->get('bookmarks.categories.create-form', route('categories.list')),
            )
                ->with('status', "Category {$category->code} was created");
        } catch (\Throwable $excp) {
            $errorMessage = ($excp instanceof QueryException) ? $excp->errorInfo[2] : $excp->getMessage();
            return redirect()->back()->withInput()->withErrors(['alert' => $errorMessage]);
        }
    }

    function showUpdateForm(string $categoryCode): View
    {
        $category = $this->find($categoryCode);
        Gate::authorize('update', $category);

        return view('categories.update-form', [
            'category' => $category,
        ]);
    }

    function update(
        ServerRequestInterface $request,
        string $categoryCode,
    ): RedirectResponse {
        $category = $this->find($categoryCode);
        Gate::authorize('update', $category);
        try {
            $category->fill($request->getParsedBody());
            $category->save();

            return redirect()
                ->route('categories.view', [
                    'category' => $category->code,
                ])
                ->with('status', "Category {$category->code} was updated");
        } catch (\Throwable $excp) {
            $errorMessage = ($excp instanceof QueryException) ? $excp->errorInfo[2] : $excp->getMessage();
            return redirect()->back()->withInput()->withErrors(['alert' => $errorMessage]);
        }
    }

    function delete(string $categoryCode): RedirectResponse
    {
        $category = $this->find($categoryCode);
        Gate::authorize('delete', $category);
        try {
            $category->delete();

            return redirect(
                session()->get('bookmarks.categories.view', route('categories.list')),
            )
                ->with('status', "Category {$category->code} was deleted");
        } catch (\Throwable $excp) {
            $errorMessage = ($excp instanceof QueryException) ? $excp->errorInfo[2] : $excp->getMessage();
            return redirect()->back()->withErrors(['alert' => $errorMessage]);
        }
    }

    function viewProducts(
        ServerRequestInterface $request,
        ProductController $productController,
        string $categoryCode,
    ): View {
        $category = $this->find($categoryCode);
        Gate::authorize('view', $category);

        $criteria = $productController->prepareCriteria($request->getQueryParams());
        $query = $productController
            ->filter($category->products(), $criteria)
            ->with('category')
            ->withCount('shops');

        return view('categories.view-products', [
            'category' => $category,
            'criteria' => $criteria,
            'products' => $query->paginate($productController::MAX_ITEMS),
        ]);
    }

    function showAddProductsForm(
        ServerRequestInterface $request,
        ProductController $productController,
        string $categoryCode,
    ): View {
        $category = $this->find($categoryCode);
        Gate::authorize('addProduct', $category);
        $query = $productController
            ->getQuery()
            ->whereDoesntHave(
                'category',
                function (Builder $innerQuery) use ($category) {
                    return $innerQuery->where('code', $category->code);
                },
            );

        $criteria = $productController->prepareCriteria($request->getQueryParams());
        $query = $productController
            ->filter($query, $criteria)
            ->with('category')
            ->withCount('shops');

        return view('categories.add-products-form', [
            'criteria' => $criteria,
            'category' => $category,
            'products' => $query->paginate($productController::MAX_ITEMS),
        ]);
    }

    function addProduct(
        ServerRequestInterface $request,
        ProductController $productController,
        string $categoryCode,
    ): RedirectResponse {
        $category = $this->find($categoryCode);
        Gate::authorize('addProduct', $category);
        try {
            $data = $request->getParsedBody();

            $product = $productController
                ->getQuery()
                ->where('code', $data['product'])
                ->firstOrFail();

            // ใช้ associate และ save แทน save() โดยตรง
            $product->category()->associate($category);
            $product->save();

            return redirect()
                ->back()
                ->with('status', "Product {$product->code} was added to Category {$category->code}");
        } catch (\Throwable $excp) {
            $errorMessage = ($excp instanceof QueryException) ? $excp->errorInfo[2] : $excp->getMessage();
            return redirect()->back()->withErrors(['alert' => $errorMessage]);
        }
    }
}