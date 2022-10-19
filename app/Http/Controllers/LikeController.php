<?php

namespace App\Http\Controllers;

use App\Http\Requests\Likes\DestroyRequest;
use App\Http\Requests\Likes\StoreRequest;
use App\Models\Like;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    //

    public function store(StoreRequest $request): JsonResponse
    {
        Like::create($request->validated());

        return response()->json(['message' => 'Successfully liked'], 201);
    }
    public function destroy(DestroyRequest $request): JsonResponse
    {
        Like::find($request->validated()['id'])->delete();

        return response()->json(['message' => 'Successfully unliked'], 204);
    }
}
