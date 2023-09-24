<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ZoneResource;
use App\Models\Zone;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Knuckles\Scribe\Attributes\Group;

#[Group('Zones')]
class ZoneController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return ZoneResource::collection(Zone::all());
    }
}
