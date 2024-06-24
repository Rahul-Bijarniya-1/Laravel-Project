<?php

namespace App\Http\Controllers;

use App\Http\Resources\TripRequestDetailResource;
use Illuminate\Http\Request;
use App\Models\TripRequest;
use App\Http\Resources\TripRequestResource;
use App\Http\Resources\UpdateTripReuest;
use App\Http\Requests\TripRequestStoreRequest;
use App\Http\Requests\TripRequestUpdateRequest;
use Illuminate\Http\JsonResponse;

class TripRequestController extends Controller
{
    public function index()
    {
        $tripRequests = TripRequest::all();
        return TripRequestResource::collection($tripRequests);
    }
    public function store(TripRequestStoreRequest $request): JsonResponse
    {
        $tripRequest = TripRequest::create([
            'customer_id' => $request['customer_id'] ?? null,
            'transporter_id' => $request['transporter_id'] ?? null,
            'source' => $request['source'],
            'destination' => $request['destination'],
            'amount' => $request['amount'],
            'status' => $request['status'],
        ]);

        return response()->json([
            'message' => 'Trip request created successfully',
            'trip_request' => new TripRequestResource($tripRequest)
        ], 201);
    }

    public function show($id)
    {
        $tripRequest = TripRequest::with(['customer.users2', 'transporter.users2'])->findOrFail($id);

        return new TripRequestDetailResource($tripRequest);
    }

    public function update(UpdateTripRequest $request, $id)
    {
        $tripRequest = TripRequest::findOrFail($id);

    }

    public function destroy(TripRequest $tripRequest)
    {
        $tripRequest->delete();
        return response()->json([
            'message' => 'Trip request deleted successfully',
        ], 204);
    }
}
