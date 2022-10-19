<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuoteResource;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    //
    public function getQuote(): JsonResponse
    {
        $quote = QuoteResource::collection(Quote::orderByDesc('id')->simplePaginate(5));

        return response()->json($quote, 200);
    }
}
