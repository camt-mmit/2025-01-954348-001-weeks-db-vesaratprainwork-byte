<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Product;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;

class ProductController extends SearchableController
{

    function list(): View
    {
        $products = Product
            ::orderBy('code')
            ->get();

        return view('products.list', [
            'products' => $products,
        ]);
    }



    function view(string $productCode): View
    {
        $product = Product
            ::where('code', $productCode)
            ->firstOrFail();

        return view('products.view', [
            'product' => $product,
        ]);
    }
}