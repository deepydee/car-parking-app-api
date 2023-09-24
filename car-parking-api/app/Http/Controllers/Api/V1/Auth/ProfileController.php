<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserUpdateRequest;
use App\Http\Resources\Auth\UserShowResourse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;
use Knuckles\Scribe\Attributes\Group;

#[Group('Auth')]
class ProfileController extends Controller
{
    public function show(Request $request): JsonResource
    {
        return new UserShowResourse($request->user());
    }

    public function update(UserUpdateRequest $request): JsonResponse
    {
        $r = $request;
        auth()->user()->update($request->validated());

        return UserShowResourse::make(auth()->user())
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
