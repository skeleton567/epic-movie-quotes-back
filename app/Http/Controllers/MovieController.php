<?php

namespace App\Http\Controllers;

use App\Http\Requests\Movie\StoreRequest;
use App\Http\Requests\Movie\UpdateRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    //
    public function store(StoreRequest $request): JsonResponse
    {
        $movie = Movie::create([
            'user_id' => auth()->id(),
            'release_year' => $request->validated()['release_year'],
            'budget' => $request->validated()['budget'],
            'image' =>  $request->file('image')->store('images')
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
        if ($request->categories) {
            $categories = $request->categories;
            foreach ($categories as $category) {
                $movie->categories()->attach($category->id);
            }
        }
        $movie->save();
        return response()->json(['message' => 'Movie created successfully'], 201);
    }
    public function show(): JsonResponse
    {
        $movie = MovieResource::collection(auth()->user()->movies->sortByDesc('id'));

        return response()->json($movie, 200);
    }
    public function index(): JsonResponse
    {
        $movie = MovieResource::collection(Movie::all());

        return response()->json($movie, 200);
    }
    public function selectMovie(Movie $movie): JsonResponse
    {
        return response()->json(MovieResource::make($movie), 200);
    }
    public function update(UpdateRequest $request, Movie $movie): JsonResponse
    {
        $attributes = $request->validated();

        if ($request->file('image')) {
            $attributes['image'] = $request->file('image')->store('images');
        }
        $movie->replaceTranslations('title', [
            'en' => $request->title_en,
            'ka' => $request->title_ka,
        ]);
        $movie->replaceTranslations('director', [
            'en' => $request->director_en,
            'ka' => $request->director_ka,
        ]);
        $movie->replaceTranslations('description', [
            'en' => $request->description_en,
            'ka' => $request->description_ka,
        ]);
        $movie->update($attributes);
        if ($request->categories) {
            $categories = $request->categories;
            $ids = [];
            foreach ($categories as $category) {
                array_push($ids, $category->id);
            }
            $movie->categories()->sync($ids);
        }
        return response()->json(['message' => 'Movie updated succesfully'], 204);
    }
    public function destroy(Movie $movie): JsonResponse
    {
        $movie->delete();
        return response()->json(['message' => 'Movie deleted succesfully'], 204);
    }
}
