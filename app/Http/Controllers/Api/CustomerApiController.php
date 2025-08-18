<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer\Customer;
use App\Pipelines\Website\Customer\AuthenticateRequest;
use App\Pipelines\Website\Customer\FormatDataForAppCreate;
use App\Pipelines\Website\Customer\FormatDataForAppUpdate;
use App\Pipelines\Website\Customer\ValidateRequestData;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class CustomerApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $pipelineData = [
                'request' => $request,
                'is_update' => false,
            ];

            $attributes = app(Pipeline::class)
                ->send($pipelineData)
                ->through([
                    AuthenticateRequest::class,
                    ValidateRequestData::class,
                    FormatDataForAppCreate::class,
                ])
                ->thenReturn();

            if($attributes) {
                $customer = Customer::create($attributes);
                return response()->json([
                    "message" => "customer created successfully",
                    "error" => false,
                    'customer' => $customer,
                ]);

            } else {
                return response()->json([
                    "message" => "customer not created",
                    'error' => true
                ]);
            }

        } catch(\Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "error" => true,
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $accountNumber)
    {

        try {
            $pipelineData = [
                'request' => $request,
                'accountNumber' => $accountNumber,
                'is_update' => true,
            ];

            $attributes = app(Pipeline::class)
                ->send($pipelineData)
                ->through([
                    // ValidateCustomer::class,
                    //                    AuthenticateRequest::class,
                    ValidateRequestData::class,
                    FormatDataForAppUpdate::class,
                ])
                ->thenReturn();

            if($attributes && $accountNumber) {
                $customer = Customer::where('account_number', $accountNumber)->firstOrFail();
                // Update customer with the new attributes
                $customer->update($attributes);

                return response()->json([
                    "message" => "customer updated successfully",
                    "error" => false,
                    "customer" => $customer,
                ]);
            } else {
                return response()->json([
                    "message" => "customer not updated - missing attributes or ID",
                    "error" => true,
                ]);
            }
        } catch(\Exception $e) {
            return response()->json([
                "message" => "Exception: " . $e->getMessage(),
                "error" => true,
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
