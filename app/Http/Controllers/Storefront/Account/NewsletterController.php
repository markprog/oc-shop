<?php

namespace App\Http\Controllers\Storefront\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function update(Request $request): RedirectResponse
    {
        auth('web')->user()->update([
            'newsletter' => $request->boolean('newsletter'),
        ]);

        return back()->with('success', 'Newsletter preference updated.');
    }
}
