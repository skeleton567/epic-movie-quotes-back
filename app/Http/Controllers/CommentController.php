<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\StoreRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //
    public function store(StoreRequest $request): JsonResponse
    {
        $comment = Comment::create($request->validated());

        return response()->json(CommentResource::make($comment), 201);
    }
}
