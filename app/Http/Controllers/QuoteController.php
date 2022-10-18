<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    //
    public function getQuote(): JsonResponse
    {
        $quote = Quote::with(['user', 'movie', 'comments.user', 'likes.user'])->get();

        return response()->json($quote, 200);
    }
}
