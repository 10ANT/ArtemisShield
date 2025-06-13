<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display the Terms of Service page.
     *
     * @return \Illuminate\View\View
     */
    public function terms()
    {
        return view('pages.terms');
    }

    /**
     * Display the Frequently Asked Questions page.
     *
     * @return \Illuminate\View\View
     */
    public function faq()
    {
        return view('pages.faq');
    }
}