<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserUpdateRequest;
use App\Http\Resources\Auth\UserShowResourse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResource
    {
        return new UserShowResourse($request->user());
    }

    public function update(UserUpdateRequest $request): JsonResource
    {
        $r = $request;
        auth()->user()->update($request->validated());

        return new UserShowResourse(auth()->user());
    }
}
