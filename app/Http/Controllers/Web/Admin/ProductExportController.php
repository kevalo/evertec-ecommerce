<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ProductExportJob;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductExportController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        dispatch(new ProductExportJob($request->user()));
        session()->flash('success', __('products.export_started'));
        return redirect()->route('products.index');
    }
}
