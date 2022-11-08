<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function change(Request $request): JsonResponse
    {
        if (in_array($request->locale, config('app.available_locales'))) {
            session()->put('lang', $request->locale);
        } else {
            session()->put('lang', 'ka');
        }
        return response()->json(['message' => 'Successfully changed locale'], 200);
    }
}
