<?php

namespace App\Http\Controllers;

use App\Models\AdModel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdModelController extends Controller
{

    public function index()
    {
        $ads = AdModel::all();

        return Inertia::render('Ads/Index', [
            'ads' => $ads,
        ]);
    }

    public function getAds()
    {
        $ads = AdModel::all();
        return response()->json($ads);
    }
}
