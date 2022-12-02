<?php

namespace App\Http\Controllers;

use App\Events\NotificationEvent;
use App\Events\FeedbackEvent;
use App\Http\Requests\Likes\DestroyRequest;
use App\Http\Requests\Likes\StoreRequest;
use App\Http\Resources\LikeResource;
use App\Http\Resources\NotificationResource;
use App\Models\Like;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{
    //

    public function store(StoreRequest $request): JsonResponse
    {
        DB::transaction(function () use ($request) {
            $like = LikeResource::make(Like::create([
                'user_id' => auth()->id(),
                'user_to_notify' => $request->user_to_notify,
                "quote_id" => $request->quote_id
            ]));

            if ($request->user_to_notify !== auth()->id()) {
                $notification = Notification::create([
                    'user_id' => auth()->id(),
                    'user_to_notify' => $request->user_to_notify,
                    'type' => 'like',
                    'seen_by_user' => false
                ]);
                event(new NotificationEvent(NotificationResource::make($notification)));
            }
            event(new FeedbackEvent($like, true));
        });

        return response()->json(['message' =>'Post liked successfuly'], 201);
    }
    public function destroy(Like $like): JsonResponse
    {
        event(new FeedbackEvent(LikeResource::make($like), false));
        $like->delete();

        return response()->json(['message' => 'Successfully unliked'], 200);
    }
}
