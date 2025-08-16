<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer\Customer;
use App\Pipelines\Website\Customer\AuthenticateRequest;
use App\Pipelines\Website\Customer\FormatDataForApp;
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
            $attributes = app(Pipeline::class)
                ->send($request)
                ->through([
                    AuthenticateRequest::class,
                    ValidateRequestData::class,
                    FormatDataForApp::class,
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
