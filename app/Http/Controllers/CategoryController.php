<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;

class CategoryController extends SearchableController
{
    public const MAX_ITEMS = 5;

    #[\Override]
    function getQuery(): Builder
    {

        return Category::query()
            ->withCount('products')
            ->orderBy('code');
    }

    #[\Override]
    function applyWhereToFilterByTerm(Builder $query, string $word): void
    {
        $query->where('code', 'LIKE', "%{$word}%")
            ->orWhere('name', 'LIKE', "%{$word}%");
    }


    function list(ServerRequestInterface $request): View
    {
        Gate::authorize('viewAny', Category::class);

        $criteria = $this->prepareCriteria($request->getQueryParams());
        $query = $this->search($criteria);

        return view('categories.list', [
            'criteria'    => $criteria,
            'categories'  => $query->paginate(self::MAX_ITEMS)->withQueryString(),
        ]);
    }


    function view(string $categoryCode): View
    {
        $category = $this->find($categoryCode);
        Gate::authorize('view', $category);


        session()->put('bookmarks.categories.view', url()->full());

        return view('categories.view', [
            'category' => $category,
        ]);
    }


    function showCreateForm(): View
    {
        Gate::authorize('create', Category::class);


        session()->put('bookmarks.categories.create-form', url()->previous() ?? route('categories.list'));

        return view('categories.create-form', ['title' => 'Create']);
    }

    function create(ServerRequestInterface $request): RedirectResponse
    {
        Gate::authorize('create', Category::class);

        $data = $request->getParsedBody();

        $category = Category::create($data);

        $target = session()->get('bookmarks.categories.create-form', route('categories.list'));
        if ($target === url()->full()) {
            $target = route('categories.list');
        }

        return redirect($target)->with('status', "Category {$category->code} was created.");
    }


    function showUpdateForm(string $categoryCode): View
    {
        $category = $this->find($categoryCode);
        Gate::authorize('update', $category);

        return view('categories.update-form', ['category' => $category]);
    }

    function update(ServerRequestInterface $request, string $categoryCode): RedirectResponse
    {
        $category = $this->find($categoryCode);
        Gate::authorize('update', $category);

        $category->fill($request->getParsedBody());
        $category->save();

        return redirect()
            ->route('categories.view', ['category' => $category->code])
            ->with('status', "Category {$category->code} was updated.");
    }


    function delete(string $categoryCode): RedirectResponse
    {
        $category = $this->find($categoryCode);
        Gate::authorize('delete', $category);

        $category->delete();

        return redirect(
            session()->get('bookmarks.categories.view', route('categories.list'))
        )->with('status', "Category {$category->code} was deleted.");
    }


    function viewProducts(
        ServerRequestInterface $request,
        ProductController $productController,
        string $categoryCode
    ): View {
        $category = $this->find($categoryCode);
        Gate::authorize('view', $category);

        $criteria = $productController->prepareCriteria($request->getQueryParams());

        $base = $category->products()
            ->getQuery()
            ->with(['category'])
            ->withCount('shops');

        $query = $productController->filter($base, $criteria);


        session()->put('bookmarks.categories.view-products', url()->full());

        return view('categories.view-products', [
            'category' => $category,
            'criteria' => $criteria,
            'products' => $query->paginate($productController::MAX_ITEMS)->withQueryString(),
        ]);
    }


    function showAddProductsForm(
        ServerRequestInterface $request,
        ProductController $productController,
        string $categoryCode
    ): View {
        $category = $this->find($categoryCode);
        Gate::authorize('update', $category);

        $criteria = $productController->prepareCriteria($request->getQueryParams());

        $base = Product::query()
            ->whereHas('category')
            ->whereRelation('category', 'id', '!=', $category->id)
            ->orderBy('code');

        $query = $productController
            ->filter($base, $criteria)
            ->with(['category']);


        session()->put('bookmarks.categories.add-products-form', url()->full());

        return view('categories.add-products-form', [
            'category' => $category,
            'criteria' => $criteria,
            'products' => $query->paginate($productController::MAX_ITEMS)->withQueryString(),
        ]);
    }

    function addProducts(ServerRequestInterface $request, string $categoryCode): RedirectResponse
    {
        $category = $this->find($categoryCode);
        Gate::authorize('update', $category);

        $data = $request->getParsedBody();
        $code = $data['product'] ?? null;

        $product = Product::where('code', $code)->firstOrFail();


        $product->category()->associate($category);
        $product->save();

        return redirect()->back()->with(
            'status',
            "Product {$product->code} was added to Category {$category->code}."
        );
    }


    protected function find(string $categoryCode): Category
    {
        return Category::query()->where('code', $categoryCode)->firstOrFail();
    }
}
