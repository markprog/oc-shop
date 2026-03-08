<?php

namespace App\Http\Controllers\Storefront\Content;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('storefront.content.contact');
    }

    public function send(Request $request): RedirectResponse
    {
        $request->validate([
            'name'    => ['required', 'string', 'max:64'],
            'email'   => ['required', 'email'],
            'enquiry' => ['required', 'string', 'min:10'],
        ]);

        $adminEmail = Setting::get('config_email');

        Mail::raw($request->enquiry, function ($msg) use ($request, $adminEmail) {
            $msg->from($request->email, $request->name)
                ->to($adminEmail)
                ->subject('Contact form enquiry from ' . $request->name);
        });

        return back()->with('success', 'Your enquiry has been sent.');
    }
}
