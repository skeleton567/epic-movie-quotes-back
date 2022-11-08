<?php

namespace App\Http\Controllers;

use App\Events\NotificationEvent;
use App\Http\Requests\Comment\DestroyRequest;
use App\Http\Requests\Comment\StoreRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\NotificationResource;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    //
    public function store(StoreRequest $request): JsonResponse
    {
        $comment =  DB::transaction(function () use ($request) {
            $comment = Comment::create($request->validated());
            $notification = Notification::create([
                'user_id' => $request->user_id,
                'user_to_notify' => $request->user_to_notify,
                'type' => 'comment',
                'seen_by_user' => false
            ]);
            event(new NotificationEvent(NotificationResource::make($notification)));
            return $comment;
        });

        return response()->json(CommentResource::make($comment), 201);
    }
    public function destroy(Comment $comment): JsonResponse
    {
        $comment->delete();

        return response()->json(['message' => 'Comment successfully deleted'], 204);
    }
}
