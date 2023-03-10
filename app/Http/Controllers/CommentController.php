<?php

namespace App\Http\Controllers;

use App\Events\FeedbackEvent;
use App\Events\NotificationEvent;
use App\Http\Requests\Comment\StoreRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\NotificationResource;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    //
    public function store(StoreRequest $request): JsonResponse
    {
        DB::transaction(function () use ($request) {
            $comment = Comment::create($request->validated());
            if ($request->user_to_notify !== auth()->id()) {
                $notification = Notification::create([
                    'user_id' => $request->user_id,
                    'user_to_notify' => $request->user_to_notify,
                    'type' => 'comment',
                    'seen_by_user' => false
                ]);
                event(new NotificationEvent(NotificationResource::make($notification)));
            }

            event(new FeedbackEvent(CommentResource::make($comment), true));
        });

        return response()->json(['message' =>'Comment created successfully'], 201);
    }
    public function destroy(Comment $comment): JsonResponse
    {
        if (json_decode(request()->user_id) !== auth()->id()) {
            return response()->json(['message' => 'Not Authorized'], 401);
        }
        event(new FeedbackEvent(CommentResource::make($comment), false));
        $comment->delete();

        return response()->json(['message' => 'Comment successfully deleted'], 200);
    }
}
