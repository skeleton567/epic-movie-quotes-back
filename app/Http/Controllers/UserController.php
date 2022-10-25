<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\AddEmailRequest;
use App\Http\Requests\User\MakePrimaryRequest;
use App\Http\Requests\User\SecondaryEmailRequest;
use App\Http\Requests\User\UpdateNameRequest;
use App\Http\Resources\UserSecondaryEmailResource;
use App\Models\SecondaryEmail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function updateName(UpdateNameRequest $request): JsonResponse
    {
        $user = User::find($request->id);
        $user->update(['name' => $request->name]);

        return response()->json('user updated Successfully', 204);
    }
    public function addEmail(AddEmailRequest $request): JsonResponse
    {
        SecondaryEmail::create($request->validated());

        return response()->json('user updated Successfully', 204);
    }
    public function getSecondaryEmail(SecondaryEmailRequest  $request): JsonResponse
    {
        $data = UserSecondaryEmailResource::make(User::find($request->id));

        return response()->json($data, 200);
    }
    public function makePrimary(MakePrimaryRequest  $request): JsonResponse
    {
        $user = User::find($request->id);
        $secondaryEmail =  SecondaryEmail::firstWhere('email', $request->email);
        $secondaryEmail->email =  $user->email;
        $user->email = $request->email;
        $user->save();
        $secondaryEmail->save();


        return response()->json(['Primary email changed seccusfully'], 200);
    }
}
