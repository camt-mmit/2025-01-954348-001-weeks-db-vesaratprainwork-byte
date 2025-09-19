<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Psr\Http\Message\ServerRequestInterface;

class CategoryController extends SearchableController
{
    const int MAX_ITEMS = 5;


    #[\Override]
    function getQuery(): Builder
    {
        return Category::query()->withCount('products')->orderBy('code');
    }

    #[\Override]
    function applyWhereToFilterByTerm(Builder $query, string $word): void
    {
        $query->where('code','LIKE',"%{$word}%")
              ->orWhere('name','LIKE',"%{$word}%");
    }

    function list(ServerRequestInterface $request): View
    {
        $criteria = $this->prepareCriteria($request->getQueryParams());
        $query = $this->search($criteria);

        return view('categories.list', [
            'criteria' => $criteria,
            'categories' => $query->paginate(self::MAX_ITEMS)->withQueryString(),
        ]);
    }

    function view(string $categoryCode): View
    {
        $category = $this->find($categoryCode);

        return view('categories.view', [
            'category' => $category,
        ]);
    }

    
    function showCreateForm(): View
    {
        return view('categories.create-form', ['title' => 'Create']);
    }

    function create(ServerRequestInterface $request): RedirectResponse
    {
        Category::create($request->getParsedBody());
        return redirect()->route('categories.list');
    }

    
    function showUpdateForm(string $categoryCode): View
    {
        $category = $this->find($categoryCode);
        return view('categories.update-form', ['category' => $category]);
    }

    function update(ServerRequestInterface $request, string $categoryCode): RedirectResponse
    {
        $category = $this->find($categoryCode);
        $category->fill($request->getParsedBody());
        $category->save();

        return redirect()->route('categories.view', ['category' => $category->code]);
    }

    
    function delete(string $categoryCode): RedirectResponse
    {
        $category = $this->find($categoryCode);
        $category->delete();
        return redirect()->route('categories.list');
    }

    
    function viewProducts(
    ServerRequestInterface $request,
    ProductController $productController,
    string $categoryCode
): View {
    $category = $this->find($categoryCode);
    $criteria = $productController->prepareCriteria($request->getQueryParams());

    $base = $category->products()->getQuery()->with(['category'])->withCount('shops');
    
    $query = $productController->filter($base, $criteria);

    return view('categories.view-products', [
        'category' => $category,
        'criteria' => $criteria,
        'products' => $query->paginate($productController::MAX_ITEMS)->withQueryString(),
    ]);
}

function showAddProductsForm(
    \Psr\Http\Message\ServerRequestInterface $request,
    ProductController $productController,
    string $categoryCode
): \Illuminate\View\View {
    $category = $this->find($categoryCode);
    $criteria = $productController->prepareCriteria($request->getQueryParams());

    $base = Product::query()
        ->whereHas('category')               
        ->whereRelation('category', 'id', '!=', $category->id)   
        ->orderBy('code');

    $query = $productController
        ->filter($base, $criteria)           
        ->with(['category']);

    return view('categories.add-products-form', [
        'category' => $category,
        'criteria' => $criteria,
        'products' => $query->paginate($productController::MAX_ITEMS)->withQueryString(),
    ]);
}


function addProducts(
    \Psr\Http\Message\ServerRequestInterface $request,
    string $categoryCode
): \Illuminate\Http\RedirectResponse {
    $category = $this->find($categoryCode);
    $data     = $request->getParsedBody();

    
    if (!empty($data['products']) && is_array($data['products'])) {
        $items = Product::query()->whereIn('code', $data['products'])->get();
        foreach ($items as $p) {
            $p->category()->associate($category->id);
            $p->save();
        }
        return redirect()->route('categories.view-products', ['category' => $category->code]);
    }

    
    if (!empty($data['product'])) {
        $p = Product::query()->where('code', $data['product'])->firstOrFail();
        $p->category()->associate($category->id);
        $p->save();
    }

    return redirect()->back();
}
}

