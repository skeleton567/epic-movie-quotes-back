<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quote\SearchRequest;
use App\Http\Requests\Quote\UpdateRequest;
use App\Http\Requests\Quote\StoreRequest;
use App\Http\Resources\QuotePostResource;
use App\Http\Resources\QuoteResource;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Undefined;

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
    public function store(StoreRequest $request): JsonResponse
    {
        $quote = Quote::create([
            'movie_id' => $request->validated()['movie_id'],
            'image' =>  $request->file('image')->store('images'),
            'user_id' => auth()->id()
        ]);
        $quote->setTranslations('quote', [
            'en' => $request->quote_en,
            'ka' => $request->quote_ka,
        ]);
        $quote->save();

        return response()->json(['message' => 'Quote Succesfully created'], 201);
    }
    public function show(Quote $quote): JsonResponse
    {
        return response()->json(QuotePostResource::make($quote), 200);
    }
    public function update(UpdateRequest $request, Quote $quote): JsonResponse
    {
        $attributes = $request->validated();

        if ($request->file('image')) {
            $attributes['image'] = $request->file('image')->store('images');
        }

        $quote->replaceTranslations('quote', [
            'en' => $request->quote_en,
            'ka' => $request->quote_ka,
        ]);
        $quote->update($attributes);
        return response()->json(['message' => 'Quote updated succesfully'], 200);
    }
    public function destroy(Quote $quote): JsonResponse
    {
        $quote->delete();
        return response()->json(['message' => 'Quote deleted succesfully'], 200);
    }
    public function searchPost(SearchRequest $request): JsonResponse
    {
        if (strpos($request->search, '@') === 0) {
            $search = ltrim($request->search, $request->search[0]);
            $quote = QuotePostResource::collection(Quote::with(['movie'])
            ->whereHas('movie', function ($movie) use ($search) {
                $movie->where(DB::raw('lower(title)'), 'LIKE', "%". strtolower($search)."%")
                ->orWhere('title->ka', 'LIKE', "%{$search}%");
            })->orderByDesc('id')->simplePaginate(5));
        } elseif (strpos($request->search, '#') === 0) {
            $search = ltrim($request->search, $request->search[0]);
            $quote = QuotePostResource::collection(Quote::query()
            ->where(DB::raw('lower(quote)'), 'LIKE', "%". strtolower($search)."%")
            ->orWhere('quote->ka', 'LIKE', "%{$search}%")
            ->orderByDesc('id')->simplePaginate(5));
        } else {
            $quote = QuotePostResource::collection(Quote::with(['movie'])
            ->whereHas('movie', function ($movie) use ($request) {
                $movie->where(DB::raw('lower(title)'), 'LIKE', "%". strtolower($request->search)."%")
                ->orWhere('title->ka', 'LIKE', "%{$request->search}%");
            })
            ->orWhere(DB::raw('lower(quote)'), 'LIKE', "%". strtolower($request->search)."%")
            ->orWhere('quote->ka', 'LIKE', "%{$request->search}%")
            ->orderByDesc('id')->simplePaginate(5));
        }

        return response()->json($quote, 200);
    }
}
