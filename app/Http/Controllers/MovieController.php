<?php

namespace App\Http\Controllers;

use App\Http\Requests\Movie\ShowRequest;
use App\Http\Requests\Movie\StoreRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    //
    public function store(StoreRequest $request): JsonResponse
    {
        $movie = Movie::create([
            'user_id' => $request->validated()['user_id'],
            'release_year' => $request->validated()['release_year'],
            'budget' => $request->validated()['budget'],
            'image' => $request->file('image')->store('images')
        ]);

        $movie->setTranslations('title', [
            'en' => $request->title_en,
            'ka' => $request->title_ka,
        ]);
        $movie->setTranslations('director', [
            'en' => $request->director_en,
            'ka' => $request->director_ka,
        ]);
        $movie->setTranslations('description', [
            'en' => $request->description_en,
            'ka' => $request->description_ka,
        ]);
        $categories = json_decode($request->categories);
        foreach ($categories as $category) {
            $movie->categories()->attach($category->id);
        }
        $movie->save();
        return response()->json(['movie created successfully'], 201);
    }
    public function show(): JsonResponse
    {
        $movie = MovieResource::collection(auth()->user()->movies);

        return response()->json($movie, 200);
    }
}
