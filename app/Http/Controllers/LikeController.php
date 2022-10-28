<?php

namespace App\Http\Controllers;

use App\Events\NotificationEvent;
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
        $like =  DB::transaction(function () use ($request) {
            $like = LikeResource::make(Like::create($request->validated()));
            $notification = Notification::create([
                'user_id' => $request->user_id,
                'user_to_notify' => $request->user_to_notify,
                'type' => 'like',
                'seen_by_user' => false
            ]);
            event(new NotificationEvent(NotificationResource::make($notification)));
            return $like;
        });

        return response()->json($like, 201);
    }
    public function destroy(DestroyRequest $request): JsonResponse
    {
        Like::find($request->validated()['id'])->delete();

        return response()->json(['message' => 'Successfully unliked'], 204);
    }
}
