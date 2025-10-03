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
    public const MAX_ITEMS = 5;

    #[\Override]
    function getQuery(): Builder
    {
        
        return Shop::query()
            ->withCount('products')
            ->orderBy('code');
    }

    #[\Override]
    function applyWhereToFilterByTerm(Builder $query, string $word): void
    {
        $query->where('code', 'LIKE', "%{$word}%")
              ->orWhere('name', 'LIKE', "%{$word}%")
              ->orWhere('owner', 'LIKE', "%{$word}%");
    }

    
    function list(ServerRequestInterface $request): View
    {
        Gate::authorize('viewAny', Shop::class);

        $criteria = $this->prepareCriteria($request->getQueryParams());
        $query = $this->search($criteria);

        return view('shops.list', [
            'criteria' => $criteria,
            'shops' => $query->paginate(self::MAX_ITEMS)->withQueryString(),
        ]);
    }

    
    function view(string $shopCode): View
    {
        $shop = $this->find($shopCode);
        Gate::authorize('view', $shop);

        
        session()->put('bookmarks.shops.view', url()->full());

        return view('shops.view', ['shop' => $shop]);
    }

    
    function showCreateForm(): View
    {
        Gate::authorize('create', Shop::class);

        
        session()->put('bookmarks.shops.create-form', url()->previous() ?? route('shops.list'));

        return view('shops.create-form', ['title' => 'Create']);
    }

    function create(ServerRequestInterface $request): RedirectResponse
    {
        Gate::authorize('create', Shop::class);

        $data = $request->getParsedBody();
        
        $shop = Shop::create($data);

        return redirect(
            session()->get('bookmarks.shops.create-form', route('shops.list'))
        )->with('status', "Shop {$shop->code} was created.");
    }

    
    function showUpdateForm(string $shopCode): View
    {
        $shop = $this->find($shopCode);
        Gate::authorize('update', $shop);

        return view('shops.update-form', ['shop' => $shop]);
    }

    function update(ServerRequestInterface $request, string $shopCode): RedirectResponse
    {
        $shop = $this->find($shopCode);
        Gate::authorize('update', $shop);

        $shop->fill($request->getParsedBody());
        $shop->save();

        return redirect()
            ->route('shops.view', ['shop' => $shop->code])
            ->with('status', "Shop {$shop->code} was updated.");
    }

    
    function delete(string $shopCode): RedirectResponse
    {
        $shop = $this->find($shopCode);
        Gate::authorize('delete', $shop);

        $shop->delete();

        return redirect(
            session()->get('bookmarks.shops.view', route('shops.list'))
        )->with('status', "Shop {$shop->code} was deleted.");
    }

    
    function viewProducts(
        ServerRequestInterface $request,
        ProductController $productController,
        string $shopCode
    ): View {
        $shop = $this->find($shopCode);
        Gate::authorize('view', $shop);

        $criteria = $productController->prepareCriteria($request->getQueryParams());

        $query = $productController
            ->filter($shop->products()->getQuery(), $criteria)
            ->with(['category'])
            ->withCount('shops')
            ->orderBy('code');

        
        session()->put('bookmarks.shops.view-products', url()->full());

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
        $shop = $this->find($shopCode);
        Gate::authorize('update', $shop); 

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

        
        session()->put('bookmarks.shops.add-products-form', url()->full());

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
        Gate::authorize('update', $shop);

        $data = $request->getParsedBody();

        
        if (!empty($data['products']) && is_array($data['products'])) {
            $ids = Product::query()
                ->whereIn('code', $data['products'])
                ->pluck('id')
                ->all();

            if (!empty($ids)) {
                $shop->products()->syncWithoutDetaching($ids);
                return redirect()
                    ->route('shops.view-products', ['shop' => $shop->code])
                    ->with('status', count($ids) . " product(s) were added to Shop {$shop->code}.");
            }

            
            return redirect()->back()->with('status', 'No valid products to add.');
        }

        
        if (!empty($data['product'])) {
            $product = Product::query()
                ->whereDoesntHave('shops', function (Builder $q) use ($shop): void {
                    $q->where('shops.id', $shop->id);
                })
                ->where('code', $data['product'])
                ->firstOrFail();

            $shop->products()->attach($product->id);

            return redirect()->back()->with(
                'status',
                "Product {$product->code} was added to Shop {$shop->code}."
            );
        }

        return redirect()->back()->with('status', 'Please select a product to add.');
    }

   
    function removeProduct(
        ServerRequestInterface $request,
        string $shopCode
    ): RedirectResponse {
        $shop = $this->find($shopCode);
        Gate::authorize('update', $shop);

        $code = $request->getParsedBody()['product'] ?? null;
        abort_if(!$code, 400, 'Missing product code.');

        $product = $shop->products()->where('code', $code)->firstOrFail();
        $shop->products()->detach($product->id);

        return redirect()->back()->with(
            'status',
            "Product {$product->code} was removed from Shop {$shop->code}."
        );
    }

    
    protected function find(string $shopCode): Shop
    {
        return Shop::query()->where('code', $shopCode)->firstOrFail();
    }
}
