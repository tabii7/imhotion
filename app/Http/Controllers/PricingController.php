<?php

namespace App\Http\Controllers;

use App\Models\PricingCategory;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index()
    {
        $categories = PricingCategory::with(['items' => static function ($q) {
            $q->where('active', true)->orderBy('sort');
        }])->where('active', true)->orderBy('sort')->get();

        return view('pricing.index', compact('categories'));
    }
}
