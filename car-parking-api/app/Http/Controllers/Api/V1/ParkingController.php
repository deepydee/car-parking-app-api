<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StartParkingRequest;
use App\Http\Resources\ParkingResource;
use App\Models\Parking;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class ParkingController extends Controller
{
    public function start(StartParkingRequest $request): Response|JsonResource
    {
        if (Parking::active()->where('vehicle_id', $request->vehicle_id)->exists()) {
            return response()->json([
                'errors' => ['general' => ['Can\'t start parking twice using same vehicle. Please stop currently active parking.']],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $parking = Parking::create($request->validated());
        $parking->load('vehicle', 'zone');

        return new ParkingResource($parking);
    }

    public function show(Parking $parking): JsonResource
    {
        return ParkingResource::make($parking);
    }

    public function stop(Parking $parking): JsonResource
    {
        $parking->update([
            'stop_time' => now(),
        ]);

        return ParkingResource::make($parking);
    }
}
