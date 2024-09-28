<?php

namespace App\Http\Controllers;

use App\Events\BidPlaced;
use App\Http\Requests\Bid\StoreBidRequest;
use App\Models\Bid;
use App\Models\Car;
use App\Models\Customer;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BidController extends Controller
{
    public function store(StoreBidRequest $request)
    {
        $validated = $request->validated();
        // Fetch the minimum bid amount from the settings table
        $settings = Setting::first();
        if (!$settings) {
            return $this->respondError('Settings not found.', 500);
        }

        // Start a database transaction
        return DB::transaction(function () use ($validated, $settings) {
            // Fetch the car and apply a lock to prevent race conditions
            $car = Car::where('id', $validated['car_id'])->lockForUpdate()->first();

            // Check if the car is within the bid time
            $current_time = now();
            if ($current_time->lt($car->start_time)) {
                // Auction hasn't started yet
                return $this->respondError('Bidding has not started yet for this car.', 400);
            } elseif ($current_time->gt($car->end_time)) {
                // Auction has ended
                return $this->respondError('Bidding time is over for this car.', 400);
            }
            // Ensure the bid amount is greater than the minimum bid from settings
            if (abs($validated['bid_amount'] - $car->current_price) < $settings->minimum_bid_amount) {
                return $this->respondError("minimum allowed bid is {$settings->minimum_bid_amount} {$settings->currency}", 400);
            }

            // Retrieve the customer placing the bid
            $customer = Customer::where('id', $validated['customer_id'])->lockForUpdate()->first();
            // Check if the customer has sufficient available balance
            if ($customer->available_balance < $validated['bid_amount']) {
                return $this->respondError('Insufficient balance for this bid.', 400);
            }

            // Retrieve the current highest bid for the car
            $highestBid = Bid::where('car_id', $car->id)->orderBy('bid_amount', 'desc')->first();

            // Check if the bid amount is higher than the current highest bid
            if ($highestBid && $validated['bid_amount'] <= $highestBid->bid_amount) {
                return response()->json(['error' => 'Bid amount must be higher than the current highest bid.'], 400);
            }

            // If there's a current winner, refund their reserved amount
            if ($car->current_winner_id) {
                $previousWinner = Customer::where('id', $car->current_winner_id)->lockForUpdate()->first();
                $previousWinner->reserved_balance -= $highestBid->bid_amount;
                $previousWinner->available_balance += $highestBid->bid_amount;
                $previousWinner->save();
            }

            // Reserve the bid amount from the customer's balance
            $customer->available_balance -= $validated['bid_amount'];
            $customer->reserved_balance += $validated['bid_amount'];
            $customer->save();

            // Update car's current winner
            $car->current_winner_id = $customer->id;
            $car->save();

            // Create the new bid
            $bid = Bid::create([
                'car_id' => $validated['car_id'],
                'customer_id' => $validated['customer_id'],
                'bid_amount' => $validated['bid_amount'],
            ]);

            $car->update([
                'current_price' => $validated['bid_amount'],
            ]);

            event(new BidPlaced($bid));
            // Broadcast bid event
//        broadcast(new BidPlaced($bid))->toOthers();
//        Reverb::channel('bids')->send(new BidPlaced($bid));

            return $this->respondSuccess($bid, null,201);
        });


    }

}
