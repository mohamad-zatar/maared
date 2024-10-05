<?php

namespace App\Http\Controllers;

use App\Http\Requests\Car\StoreCarRequest;
use App\Http\Requests\Car\UpdateCarRequest;
use App\Http\Resources\CarResource;
use App\Models\Car;
use App\Models\Setting;
use Carbon\Carbon;
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

        try {
            // Retrieve auction settings from the database
            $settings = Setting::first();
            $auctionStartTime = Carbon::createFromFormat('H:i', $settings->auction_start_time);
            $auctionEndTime = Carbon::createFromFormat('H:i', $settings->auction_end_time);
            $eachAuctionMinutes = $settings->each_auction_minutes;

            // Get the auction day from the request
            $auctionDay = Carbon::createFromFormat('Y-m-d', $validated['auction_day']);
            $auctionStartDateTime = $auctionDay->copy()->setTimeFromTimeString($auctionStartTime->format('H:i:s'));
            $auctionEndDateTime = $auctionDay->copy()->setTimeFromTimeString($auctionEndTime->format('H:i:s'));

            // Find the last car auctioned on the given day
            $lastCar = Car::whereDate('start_time', $auctionDay)->latest('end_time')->first();
            $nextAuctionStartTime = $lastCar ? Carbon::parse($lastCar->end_time) : $auctionStartDateTime;

            // Validate auction timing
            if ($this->isExceedingAuctionTime($nextAuctionStartTime, $auctionEndDateTime)) {
                return $this->respondError('Cannot schedule more auctions for this day. Auction time exceeds the allowed range.', 422);
            }

            // Calculate next auction end time
            $nextAuctionEndTime = $nextAuctionStartTime->copy()->addMinutes(intval($eachAuctionMinutes));

            // Ensure the next auction end time does not exceed the auction end time
            if ($nextAuctionEndTime->gt($auctionEndDateTime)) {
                return $this->respondError('Auction time exceeds the end of the auction day.', 422);
            }

            // Create the car with scheduled auction times
            $car = Car::create([
                'title' => $validated['title'],
                'start_price' => $validated['start_price'],
                'sale_price' => $validated['sale_price'],
                'start_time' => $nextAuctionStartTime,
                'end_time' => $nextAuctionEndTime,
            ]);

            return $this->respondSuccess(new CarResource($car));

        } catch (\Exception $e) {
            // Handle any exceptions and respond with a generic error message
            return $this->respondError('An error occurred while scheduling the auction: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Check if the next auction time exceeds the allowed auction end time.
     *
     * @param Carbon $nextAuctionStartTime
     * @param Carbon $auctionEndDateTime
     * @return bool
     */
    private function isExceedingAuctionTime($nextAuctionStartTime, $auctionEndDateTime)
    {
        return $nextAuctionStartTime->gt($auctionEndDateTime);
    }


    public function update(UpdateCarRequest $request, $id)
    {
        $car = Car::findOrFail($id);

        $validated = $request->validated();

        $car->update($validated);
        return new CarResource($car);
    }

    public function getAvailableAuctionTimes(String $auctionDate)
    {
        // Parse the input date
        $auctionDay = Carbon::parse($auctionDate);

        $settings = Setting::first();
        if (!$settings) {
            return $this->respondError('Settings not found.', 500);
        }


        $startOfDay = $auctionDay->copy()->setTimeFromTimeString($settings->auction_start_time);
        $endOfDay = $auctionDay->copy()->setTimeFromTimeString($settings->auction_end_time);

        // Get all auctions scheduled for the given date
        $scheduledAuctions = Car::whereDate('auction_start_time', $auctionDay->format('Y-m-d'))
            ->orderBy('auction_start_time', 'asc')
            ->get();

        // Start searching from the start of the auction day
        $availableStartTime = $startOfDay;

        foreach ($scheduledAuctions as $auction) {
            // If there is a gap between the available time and the next scheduled auction
            if ($availableStartTime->lt($auction->auction_start_time) && $availableStartTime->addMinutes(5)->lte($auction->auction_start_time)) {
                return response()->json([
                    'next_available_start_time' => $availableStartTime,
                    'next_available_end_time' => $availableStartTime->copy()->addMinutes(5),
                ]);
            }

            // Move the available time to after the current auction's end time
            $availableStartTime = $auction->auction_end_time;
        }

        // If no gaps were found, the available time is after the last auction or at the start of the day
        if ($availableStartTime->lt($endOfDay)) {
            return response()->json([
                'next_available_start_time' => $availableStartTime,
                'next_available_end_time' => $availableStartTime->copy()->addMinutes(5),
            ]);
        }

        // If no slots are available
        return response()->json([
            'message' => 'No available slots for the given date',
        ], 404);
    }


}
