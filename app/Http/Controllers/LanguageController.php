<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch(Request $request, $locale)
    {
        if (!in_array($locale, ['fa', 'en'])) {
            abort(404);
        }

        Session::put('locale', $locale);

        return redirect()->back();
    }
}