<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bid\StoreBidRequest;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return CustomerResource::collection(Customer::all());
    }
    public function store(StoreCustomerRequest $request)
    {

        $customer = Customer::create($request->validated());

        return $this->respondSuccess($customer,null,201);
    }

    public function update(UpdateCustomerRequest $request, $id)
    {
        $customer = Customer::findOrFail($id);

        if ($request->filled('customer_id')) {
            $customer->customer_id = $request->customer_id;
        }
        if ($request->filled('name')) {
            $customer->name = $request->name;
        }

        if ($request->filled('balance_add')) {
            $customer->balance += $request->balance_add;
            $customer->available_balance += $request->balance_add;
        }

        if ($request->filled('balance_deduct')) {
            $deductAmount = $request->balance_deduct;
            if ($customer->available_balance >= $deductAmount) {
                $customer->balance -= $deductAmount;
                $customer->available_balance -= $deductAmount;
            } else {
                return $this->respondError('Insufficient balance to deduct', 422,422);
            }
        }

        // Save the updated customer information
        $customer->save();
        return $this->respondSuccess($customer);
    }


    public function updateInsurance(Request $request)
    {
        $Customer = Customer::find($request->Customer_id);
        $Customer->update(['insurance_amount' => $request->insurance_amount]);
        return response()->json($Customer);
    }

    public function refundInsurance(Request $request)
    {
        $Customer = Customer::find($request->Customer_id);
        $Customer->update(['insurance_amount' => 0]);
        return response()->json(['message' => 'Insurance refunded']);
    }

}
