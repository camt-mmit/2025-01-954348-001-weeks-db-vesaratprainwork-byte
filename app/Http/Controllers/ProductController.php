<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;

class ProductController extends SearchableController
{
    const int MAX_ITEMS = 5;

    #[\Override]
    function getQuery(): Builder
    {
        return Product::orderBy('code');
    }

    #[\Override]
    function prepareCriteria(array $criteria): array
    {
        return [
            ...parent::prepareCriteria($criteria),
            'minPrice' => (($criteria['minPrice'] ?? null) === null)
                ? null
                : (float) $criteria['minPrice'],
            'maxPrice' => (($criteria['maxPrice'] ?? null) === null)
                ? null
                : (float) $criteria['maxPrice'],
        ];
    }


    #[\Override]
    function applyWhereToFilterByTerm(\Illuminate\Database\Eloquent\Builder $query, string $word): void
    {
        $query->where('code', 'LIKE', "%{$word}%")
            ->orWhere('name', 'LIKE', "%{$word}%")
            ->orWhereHas('category', function (\Illuminate\Database\Eloquent\Builder $q) use ($word): void {
                $q->where('code', 'LIKE', "%{$word}%")
                    ->orWhere('name', 'LIKE', "%{$word}%");
            });
    }


    function filterByPrice(
        Builder|Relation $query,
        ?float $minPrice,
        ?float $maxPrice
    ): Builder|Relation {
        if ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }

        return $query;
    }

    #[\Override]
    function filter(Builder|Relation $query, array $criteria): Builder|Relation
    {
        $query = parent::filter($query, $criteria);
        $query = $this->filterByPrice(
            $query,
            $criteria['minPrice'],
            $criteria['maxPrice'],
        );

        return $query;
    }

    function list(ServerRequestInterface $request): View

    {
        Gate::authorize('list', Product::class);


        $criteria = $this->prepareCriteria($request->getQueryParams());

        $query = $this->search($criteria)
            ->with(['category'])
            ->withCount('shops');

        return view('products.list', [
            'criteria' => $criteria,
            'products' => $query->paginate(self::MAX_ITEMS)->withQueryString(),
        ]);
    }



    function view(string $productCode): View
    {
        

        $product = $this->find($productCode);
Gate::authorize('view', $product);
        return view('products.view', [
            'product' => $product,
        ]);
    }




    function delete(string $productCode): RedirectResponse
    {
        $product = $this->find($productCode);
        Gate::authorize('delete', $product);
        $product->delete();

        return redirect(
            session()->get('bookmarks.products.view', route('products.list'))
        )
            ->with('status', "Product {$product->code} was deleted.");
    }

    function viewShops(
        ServerRequestInterface $request,
        ShopController $shopController,
        string $productCode
    ): View {
        $product = $this->find($productCode);
        Gate::authorize('view', $product);
        $criteria = $shopController->prepareCriteria($request->getQueryParams());
        $query = $shopController->filter($product->shops(), $criteria)->withCount('products');
        return view('products.view-shops', [
            'product' => $product,
            'criteria' => $criteria,
            'shops' => $query->paginate($shopController::MAX_ITEMS),
        ]);
    }


    function showAddShopsForm(
        ServerRequestInterface $request,
        ShopController $shopController,
        string $productCode
    ): View {
        $product  = $this->find($productCode);
        Gate::authorize('update', $product);
        $criteria = $shopController->prepareCriteria($request->getQueryParams());

        $query = $shopController
            ->getQuery()
            ->whereDoesntHave('products', function (Builder $innerQuery) use ($product): void {
                $innerQuery->where('code', $product->code);
            });

        $query = $shopController->filter($query, $criteria)->withCount('products');

        return view('products.add-shops-form', [
            'product'  => $product,
            'criteria' => $criteria,
            'shops'    => $query->paginate($shopController::MAX_ITEMS)->withQueryString(),
        ]);
    }


    function addShops(
        ServerRequestInterface $request,
        ShopController $shopController,
        string $productCode
    ): RedirectResponse {
        $product = $this->find($productCode);
        Gate::authorize('update', $product);
        $data    = $request->getParsedBody();


        if (!empty($data['shops']) && is_array($data['shops'])) {
            $shopIds = Shop::query()->whereIn('code', $data['shops'])->pluck('id')->all();
            $product->shops()->syncWithoutDetaching($shopIds);
            return redirect()->route('products.view-shops', ['product' => $product->code]);
        }


        if (!empty($data['shop'])) {
            $shop = $shopController
                ->getQuery()
                ->whereDoesntHave('products', function (Builder $innerQuery) use ($product): void {
                    $innerQuery->where('code', $product->code);
                })
                ->where('code', $data['shop'])
                ->firstOrFail();

            $product->shops()->attach($shop->id);
        }


        return redirect()->back()->with(
            'status',
            "Shop {$shop->code} was added to Product {$product->code}."
        );
    }


    function removeShop(string $productCode, string $shopCode): RedirectResponse
    {
        $product = $this->find($productCode);
        Gate::authorize('update', $product);
        $shop    = Shop::query()->where('code', $shopCode)->firstOrFail();

        $product->shops()->detach($shop->id);

        return redirect()->back()->with(
            'status',
            "Shop {$shop->code} was removed from Product {$product->code}."
        );
    }

    function showCreateForm(): \Illuminate\View\View
    {

        Gate::authorize('create', \App\Models\Product::class);

        return view('products.create-form', [
            'title' => 'Create',
            'categories' => Category::query()->orderBy('code')->get(),
        ]);
    }


    function create(ServerRequestInterface $request): RedirectResponse
    {

        Gate::authorize('create', \App\Models\Product::class);
        $data = $request->getParsedBody();


        $category = Category::where('code', $data['category'] ?? null)->firstOrFail();

        $product = new Product();
        $product->fill($data);
        $product->category()->associate($category->id);
        $product->save();

        return redirect(
            session()->get('bookmarks.products.create-form', route('products.list'))
        )
            ->with('status', "Product {$product->code} was created.");
    }
    function showUpdateForm(string $productCode): View
    {
        $product    = $this->find($productCode);
        Gate::authorize('update', $product);
        $categories = Category::orderBy('code')->get();

        return view('products.update-form', [
            'product'    => $product,
            'categories' => $categories,
        ]);
    }

    function update(ServerRequestInterface $request, string $productCode): RedirectResponse
    {
        $product = $this->find($productCode);
        Gate::authorize('update', $product);
        $data    = $request->getParsedBody();


        if (!empty($data['category'])) {
            $category = Category::where('code', $data['category'])->firstOrFail();
            $product->category()->associate($category->id);
        }

        $product->fill($data);
        $product->save();

        return redirect()->route('products.view', ['product' => $product->code])
            ->with('status', "Product {$product->code} was updated.");
    }
}
