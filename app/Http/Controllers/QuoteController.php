<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quote\UpdateRequest;
use App\Http\Requests\Quote\StoreRequest;
use App\Http\Resources\QuotePostResource;
use App\Http\Resources\QuoteResource;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
            'user_id' => auth()->user()->id
        ]);
        $quote->setTranslations('quote', [
            'en' => $request->quote_en,
            'ka' => $request->quote_ka,
        ]);
        $quote->save();

        return response()->json(['Quote Succesfully added'], 201);
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
        return response()->json(['Quote updated succesfully'], 204);
    }
    public function destroy(Quote $quote): JsonResponse
    {
        $quote->delete();
        return response()->json(['Quote deleted succesfully'], 204);
    }
}
