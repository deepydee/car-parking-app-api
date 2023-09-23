<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\UserRegistrationResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request): JsonResource
    {
        $user = User::create($request->validated());

        event(new Registered($user));

        return new UserRegistrationResource($user);
    }
}
