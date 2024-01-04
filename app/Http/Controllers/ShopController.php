<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShopController extends Controller
{
    public
    function index()
    {
        return Inertia::render('Shop/Index', [
            ''
        ]);

    }
}
