<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer\Customer;
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

        $attributes = app(Pipeline::class)
            ->send($request)
            ->through([
                ValidateRequestData::class,
                FormatDataForApp::class,
            ])
            ->thenReturn();

        $customer = Customer::create($attributes);

        return response()->json($customer);
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
