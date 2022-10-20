<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuotePostResource;
use App\Http\Resources\QuoteResource;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    //
    public function getPost(): JsonResponse
    {
        $quote = QuotePostResource::collection(Quote::orderByDesc('id')->simplePaginate(5));

        return response()->json($quote, 200);
    }
    public function index(): JsonResponse
    {
        $quote = QuoteResource::collection(Quote::orderByDesc('id')->get());

        return response()->json($quote, 200);
    }
}
