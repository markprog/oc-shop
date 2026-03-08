<?php

namespace App\Http\Controllers\Admin\Tool;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ErrorLogController extends Controller
{
    private string $logPath = 'logs/error.log';

    public function index(): View
    {
        $log = '';

        if (Storage::disk('local')->exists($this->logPath)) {
            $content = Storage::disk('local')->get($this->logPath);
            $lines   = array_reverse(explode("\n", trim($content)));
            $log     = implode("\n", array_slice($lines, 0, 500));
        }

        return view('admin.tool.error-log', compact('log'));
    }

    public function clear(): RedirectResponse
    {
        if (Storage::disk('local')->exists($this->logPath)) {
            Storage::disk('local')->put($this->logPath, '');
        }

        return back()->with('success', 'Error log cleared.');
    }
}
