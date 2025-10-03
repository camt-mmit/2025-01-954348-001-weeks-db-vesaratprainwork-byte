<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;

class ShopController extends SearchableController
{
    const int MAX_ITEMS = 5;

    #[\Override]
    function getQuery(): Builder
    {
        return Shop::orderBy('code');
    }

    #[\Override]
    function applyWhereToFilterByTerm(Builder $query, string $word): void
    {
        parent::applyWhereToFilterByTerm($query, $word);

        $query->orWhere('owner', 'LIKE', "%{$word}%");
    }

    function list(ServerRequestInterface $request): View
    {
        $criteria = $this->prepareCriteria($request->getQueryParams());
        $query = $this->search($criteria)->withCount('products');

        return view('shops.list', [
            'criteria' => $criteria,
            'shops' => $query->paginate(self::MAX_ITEMS),
        ]);
    }

    function view(string $shopCode): View
    {
        $shop = $this->find($shopCode);

        return view('shops.view', [
            'shop' => $shop,
        ]);
    }

    function showCreateForm(): View
    {
        Gate::authorize('create', Shop::class);
        return view('shops.create-form');
    }

    function create(ServerRequestInterface $request): RedirectResponse
    {
         Gate::authorize('create', Shop::class);
        $shop = Shop::create($request->getParsedBody());

        return redirect(
            session()->get('bookmarks.shops.create-form', route('shops.list')),
        )
            ->with('status', "Shop {$shop->code} was created");
    }

    function showUpdateForm(string $shopCode): View
    {
        $shop = $this->find($shopCode);
         Gate::authorize('update', $shop);

        return view('shops.update-form', [
            'shop' => $shop,
        ]);
    }

    function update(ServerRequestInterface $request,
        string $shopCode,
    ): RedirectResponse {
        $shop = $this->find($shopCode);
        Gate::authorize('update', $shop);
        $shop->fill($request->getParsedBody());
        $shop->save();

        return redirect()
            ->route('shops.view', [
                'shop' => $shop->code,
            ])
            ->with('status', "Shop {$shop->code} was updated");
    }

    function delete(string $shopCode): RedirectResponse
    {
        $shop = $this->find($shopCode);
         Gate::authorize('delete', $shop);
        $shop->delete();

        return redirect(
            session()->get('bookmarks.shops.view', route('shops.list')),
        )
            ->with('status', "Shop {$shop->code} was deleted");
    }

    function viewProducts(
        ServerRequestInterface $request,
        ProductController $productController,
        string $shopCode,
    ): View {
        $shop = $this->find($shopCode);
        $criteria = $productController->prepareCriteria($request->getQueryParams());
        $query = $productController
            ->filter($shop->products(), $criteria)
            ->with('category')
            ->withCount('shops');

        return view('shops.view-products', [
            'shop' => $shop,
            'criteria' => $criteria,
            'products' => $query->paginate($productController::MAX_ITEMS),
        ]);
    }

    function showAddProductsForm(
        ServerRequestInterface $request,
        ProductController $productController,
        string $shopCode,
    ): View {
        $shop = $this->find($shopCode);
        Gate::authorize('addProduct', $shop);
        $query = $productController
            ->getQuery()
            ->whereDoesntHave(
                'shops',
                function (Builder $innerQuery) use ($shop) {
                    return $innerQuery->where('code', $shop->code);
                },
            );

        $criteria = $productController->prepareCriteria($request->getQueryParams());
        $query = $productController
            ->filter($query, $criteria)
            ->with('category')
            ->withCount('shops');

        return view('shops.add-products-form', [
            'criteria' => $criteria,
            'shop' => $shop,
            'products' => $query->paginate($productController::MAX_ITEMS),
        ]);
    }

    function addProduct(
        ServerRequestInterface $request,
        ProductController $productController,
        string $shopCode,
    ): RedirectResponse {
        $shop = $this->find($shopCode);
        Gate::authorize('addProduct', $shop);
        $data = $request->getParsedBody();

        $product = $productController
            ->getQuery()
            ->whereDoesntHave(
                'shops',
                function (Builder $innerQuery) use ($shop) {
                    return $innerQuery->where('code', $shop->code);
                },
            )
            ->where('code', $data['product'])
            ->firstOrFail();

        $shop->products()->attach($product);

        return redirect()
            ->back()
            ->with('status', "Product {$product->code} was added to Shop {$shop->code}");
    }

    function removeProduct(
        ServerRequestInterface $request,
        string $shopCode,
    ): RedirectResponse {
        $shop = $this->find($shopCode);
        Gate::authorize('removeProduct', $shop);
        $data = $request->getParsedBody();

        $product = $shop->products()
            ->where('code', $data['product'])
            ->firstOrFail();

        $shop->products()->detach($product);

        return redirect()
            ->back()
            ->with('status', "Product {$product->code} was removed from Shop {$shop->code}");
    }
}