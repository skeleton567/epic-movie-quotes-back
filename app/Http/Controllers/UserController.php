<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\AddEmailRequest;
use App\Http\Requests\User\DestroyEmailRequest;
use App\Http\Requests\User\MakePrimaryRequest;
use App\Http\Requests\User\SecondaryEmailRequest;
use App\Http\Requests\User\StoreProfilePictureRequest;
use App\Http\Requests\User\UpdateNameRequest;
use App\Http\Resources\UserSecondaryEmailResource;
use App\Models\SecondaryEmail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function updateName(UpdateNameRequest $request): JsonResponse
    {
        $user = User::find(auth()->id());
        $user->update(['name' => $request->name]);

        return response()->json(['message' => 'User updated successfully'], 200);
    }
    public function addEmail(AddEmailRequest $request): JsonResponse
    {
        $email = SecondaryEmail::create($request->validated());
        event(new Registered($email));
        return response()->json(['message' => 'User updated successfully'], 200);
    }
    public function getSecondaryEmail(): JsonResponse
    {
        $data = UserSecondaryEmailResource::make(User::find(auth()->id()));

        return response()->json($data, 200);
    }
    public function makePrimary(MakePrimaryRequest  $request): JsonResponse
    {
        $user = User::find(auth()->id());
        $secondaryEmail =  SecondaryEmail::firstWhere('email', $request->email);
        $secondaryEmail->email =  $user->email;
        $secondaryEmail->email_verified_at = $user->email_verified_at;
        $user->email = $request->email;
        $user->email_verified_at = $request->verified;
        $user->save();
        $secondaryEmail->save();

        return response()->json(['message' => 'Primary email changed successfully'], 200);
    }
    public function destroyEmail(DestroyEmailRequest $request): JsonResponse
    {
        $secondaryEmail = SecondaryEmail::find($request->id);
        $secondaryEmail->delete();

        return response()->json(['message' => 'Secondary email deleted successfully'], 200);
    }
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $user = User::find(auth()->id());
        $user->forceFill([
                    'password' => $request->password
                ]);

        $user->save();

        return response()->json(['message' => 'Password updated successfully'], 200);
    }
    public function storeProfileImage(StoreProfilePictureRequest $request): JsonResponse
    {
        $user = User::find(auth()->id());
        $path = $request->file('image')->store('images');
        $user->profile_picture = $path;
        $user->save();
        return response()->json(['message' => 'Profile picture set successfully'], 201);
    }
}
