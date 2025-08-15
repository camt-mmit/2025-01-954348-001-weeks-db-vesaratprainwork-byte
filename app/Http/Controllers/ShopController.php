<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\View\View;

class ShopController extends Controller
{
    function list(): View
    {
        $shops = Shop::orderBy('code')->get();

        return view('shops.list', [
            'shops' => $shops,
        ]);
    }

    // /shops/{shop}  -> {shop} = code
    function view(string $shop): View
    {
        $item = Shop::where('code', $shop)->firstOrFail();

        return view('shops.view', [
            'shop' => $item,
        ]);
    }
}

