<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserPasswordUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Knuckles\Scribe\Attributes\Group;

#[Group('Auth')]
class PasswordUpdateController extends Controller
{
    public function __invoke(UserPasswordUpdateRequest $request)
    {
        auth()->user()->update([
            'password' => bcrypt($request->input('password')),
        ]);

        return response()->json([
            'message' => 'Password updated successfully',
        ], Response::HTTP_ACCEPTED);
    }
}
