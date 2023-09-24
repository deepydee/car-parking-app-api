<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Knuckles\Scribe\Attributes\Group;

#[Group('Vehicles')]
class VehicleController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return VehicleResource::collection(Vehicle::all());
    }

    public function store(StoreVehicleRequest $request): JsonResponse
    {
        $vehicle = Vehicle::create($request->validated());

        return VehicleResource::make($vehicle)
            ->response()
            ->setStatusCode(\Symfony\Component\HttpFoundation\Response::HTTP_CREATED);
    }

    public function show(Vehicle $vehicle): JsonResource
    {
        return new VehicleResource($vehicle);
    }

    public function update(StoreVehicleRequest $request, Vehicle $vehicle): JsonResponse
    {
        $vehicle->update($request->validated());

        return VehicleResource::make($vehicle)
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Vehicle $vehicle): Response
    {
        $vehicle->delete();

        return response()->noContent();
    }
}
