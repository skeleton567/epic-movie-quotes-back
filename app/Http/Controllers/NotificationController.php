<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notifications\IndexRequest;
use App\Http\Requests\Notifications\UpdateRequest;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //

    public function index(): JsonResponse
    {
        $notifications = Notification::orderByDesc('id')->where('user_to_notify', auth()->id())->get();
        return response()->json(NotificationResource::collection($notifications), 200);
    }
    public function update(UpdateRequest $request): JsonResponse
    {
        if ($request->id) {
            Notification::find($request->id)->update(['seen_by_user' => true]);
        } else {
            $notifications = Notification::where('user_to_notify', $request->user_to_notify)->where('seen_by_user', false)->get();
            foreach ($notifications as $notification) {
                $notification->update(['seen_by_user' => true]);
            }
        }
        return response()->json('Notification updated succesfully', 200);
    }
}
