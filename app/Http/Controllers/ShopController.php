<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;


class ShopController extends SearchableController
{
    const int MAX_ITEMS = 5;

    #[\Override]
    function getQuery(): Builder {
        return Shop::query()->withCount('products')->orderBy('code');
    }

    #[\Override]
    function applyWhereToFilterByTerm(Builder $query, string $word): void {
        $query->where('code','LIKE',"%{$word}%")
              ->orWhere('name','LIKE',"%{$word}%")
              ->orWhere('owner','LIKE',"%{$word}%");
    }

    function list(ServerRequestInterface $request): View {
        $criteria = $this->prepareCriteria($request->getQueryParams());
        $query = $this->search($criteria);

        return view('shops.list', [
            'criteria' => $criteria,
            'shops' => $query->paginate(self::MAX_ITEMS)->withQueryString(),
        ]);
    }

    function view(string $shopCode): View {
        $shop = $this->find($shopCode);
        return view('shops.view', ['shop' => $shop]);
    }

    function showCreateForm(): View {
        return view('shops.create-form', ['title' => 'Create']);
    }

    function create(ServerRequestInterface $request): RedirectResponse {
        Shop::create($request->getParsedBody());
        return redirect()->route('shops.list');
    }

    function showUpdateForm(string $shopCode): View {
        $shop = $this->find($shopCode);
        return view('shops.update-form', ['shop' => $shop]);
    }

    function update(ServerRequestInterface $request, string $shopCode): RedirectResponse {
        $shop = $this->find($shopCode);
        $shop->fill($request->getParsedBody());
        $shop->save();
        return redirect()->route('shops.view', ['shop' => $shop->code]);
    }

    function delete(string $shopCode): RedirectResponse {
        $shop = $this->find($shopCode);
        $shop->delete();
        return redirect()->route('shops.list');
    }

    function viewProducts(
        ServerRequestInterface $request,
        ProductController $productController,
        string $shopCode
    ): View {
        $shop = $this->find($shopCode);
        $criteria = $productController->prepareCriteria($request->getQueryParams());
        $query = $productController
            ->filter($shop->products()->getQuery(), $criteria)
            ->with(['category'])
            ->withCount('shops')
            ->orderBy('code');

        return view('shops.view-products', [
            'shop' => $shop,
            'criteria' => $criteria,
            'products' => $query->paginate($productController::MAX_ITEMS)->withQueryString(),
        ]);
    }
    function showAddProductsForm(
    ServerRequestInterface $request,
    ProductController $productController,
    string $shopCode
): View {
    $shop     = $this->find($shopCode);
    $criteria = $productController->prepareCriteria($request->getQueryParams());

    
    $base = Product::query()
        ->whereDoesntHave('shops', function (Builder $q) use ($shop): void {
            $q->where('shops.id', $shop->id);
        })
        ->orderBy('code');

    $query = $productController
        ->filter($base, $criteria)
        ->with(['category'])
        ->withCount('shops');

    return view('shops.add-products-form', [
        'shop'      => $shop,
        'criteria'  => $criteria,
        'products'  => $query->paginate($productController::MAX_ITEMS)->withQueryString(),
    ]);
}

function addProducts(
    ServerRequestInterface $request,
    ProductController $productController,
    string $shopCode
): RedirectResponse {
    $shop = $this->find($shopCode);
    $data = $request->getParsedBody();

   
    if (!empty($data['products']) && is_array($data['products'])) {
        $ids = Product::query()->whereIn('code', $data['products'])->pluck('id')->all();
        $shop->products()->syncWithoutDetaching($ids);
        return redirect()->route('shops.view-products', ['shop' => $shop->code]);
    }

    if (!empty($data['product'])) {
        $product = Product::query()
            ->whereDoesntHave('shops', function (Builder $q) use ($shop): void {
                $q->where('shops.id', $shop->id);
            })
            ->where('code', $data['product'])
            ->firstOrFail();

        $shop->products()->attach($product->id);
    }

    
    return redirect()->back();
}
function removeProduct(
    ServerRequestInterface $request,
    string $shopCode
): RedirectResponse {
    $shop  = $this->find($shopCode);
    $code  = $request->getParsedBody()['product'] ?? null;
    abort_if(!$code, 400);

    $product = $shop->products()->where('code', $code)->firstOrFail();
    $shop->products()->detach($product->id);

    return redirect()->route('shops.view-products', ['shop' => $shop->code]);
}


}
