<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * General page for vue-router container.
     *
     * @return View
     */
    public function __invoke(): View
    {
        return view('page');
    }
}
