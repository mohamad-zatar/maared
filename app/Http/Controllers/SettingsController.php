<?php

namespace App\Http\Controllers;

use App\Http\Requests\Settings\StoreSettingsRequest;
use App\Http\Requests\Settings\UpdateSettingsRequest;
use App\Http\Resources\SettingsResource;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::first(); // Assuming there is only one settings row
        return new SettingsResource($settings);
    }

    // Store the settings
    public function store(StoreSettingsRequest $request)
    {
        $settings = Setting::create($request->validated());
        return new SettingsResource($settings);
    }

    // Update the settings
    public function update(UpdateSettingsRequest $request)
    {
        $settings = Setting::first(); // Fetch the settings row
        $settings->update($request->validated()); // Update the settings

        return new SettingsResource($settings);
    }
}
