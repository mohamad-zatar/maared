<?php

namespace App\Http\Controllers;

use App\Http\Requests\Car\StoreCarRequest;
use App\Http\Requests\Car\UpdateCarRequest;
use App\Http\Resources\CarResource;
use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index()
    {
        return CarResource::collection(Car::all());

    }
    public function store(StoreCarRequest $request)
    {
        $validated = $request->validated();

        $car = Car::create($validated);
        return new CarResource($car);
    }

    public function update(UpdateCarRequest $request, $id)
    {
        $car = Car::findOrFail($id);

        $validated = $request->validated();

        $car->update($validated);
        return new CarResource($car);
    }

}
