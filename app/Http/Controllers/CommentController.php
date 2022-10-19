<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\StoreRequest;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //
    public function store(StoreRequest $request): JsonResponse
    {
        Comment::create($request->validated());

        return response()->json(['message' => 'Successfully commented'], 201);
    }
}
