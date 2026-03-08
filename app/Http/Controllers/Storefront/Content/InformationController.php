<?php

namespace App\Http\Controllers\Storefront\Content;

use App\Http\Controllers\Controller;
use App\Models\Information;
use Illuminate\View\View;

class InformationController extends Controller
{
    public function show(int $id): View
    {
        $page = Information::with('description')->where('status', true)->findOrFail($id);
        return view('storefront.content.information', compact('page'));
    }
}
