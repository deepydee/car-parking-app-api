<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $device = substr($request->userAgent() ?? '', 0, 255);

        return [
            'access_token' => $this->createToken($device)->plainTextToken,
        ];
    }
}
