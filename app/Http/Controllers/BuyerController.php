<?php

namespace App\Http\Controllers;

use App\Constants\Constants;
use App\Http\Requests\Buyer\CreateBuyerRequest;
use App\Http\Requests\Buyer\UpdateBuyerRequest;
use App\Models\Buyer;
use App\Services\BuyerService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class BuyerController extends Controller implements HasMiddleware
{
    protected BuyerService $buyerService;

    public function __construct(BuyerService $buyerService)
    {
        $this->buyerService = $buyerService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:can-view-buyer', only: ['index']),
            new Middleware('permission:can-view-details-buyer', only: ['show']),
            new middleware('permission:can-create-buyer', only: ['create','store']),
            new middleware('permission:can-edit-buyer', only: ['edit','update','changeStatus']),
            new middleware('permission:can-delete-buyer', only: ['destroy'])
        ];
    }

    public function index()
    {
        $buyers = $this->buyerService->all();

        $responseData = [
            'buyers' => $buyers
        ];

        return response()->json($responseData);
    }

    public function store(CreateBuyerRequest $request)
    {
        $validatedData = $request->validated();
        $buyer = $this->buyerService->create($validatedData);
        $status = $buyer ? Constants::SUCCESS : Constants::ERROR;
        $message = $buyer ? 'Buyer Created Successfully' : 'Buyer could not be created';

        return response()->json([
            'status' => $status,
            'message' => $message,
            'buyer' => $buyer
        ]);
    }

    public function show(Buyer $buyer, Request $request)
    {
        $responseData = [
            'buyer' => $buyer,
        ];

        return response()->json($responseData);
    }

    public function edit(Buyer $buyer)
    {
        $responseData = [
            'buyer' => $buyer,
        ];

        return response()->json($responseData);
    }

    public function update(UpdateBuyerRequest $request, Buyer $buyer)
    {
        $validatedData = $request->validated();
        $isUpdated = $this->buyerService->update($buyer, $validatedData);
        $status = $isUpdated ? Constants::SUCCESS : Constants::ERROR;
        $message = $isUpdated ? 'Buyer updated succesfully' : 'Buyer could not be updated';

        return response()->json([
            'status' => $status,
            'message' => $message,
            'buyer' => $buyer
        ]);
    }

    public function destroy(Buyer $buyer)
    {
        $isDeleted = $this->buyerService->delete($buyer->id);
        $status = $isDeleted ? 'success' : 'error';
        $message = $isDeleted ? 'Buyer deleted succesfully' : 'Buyer could not be deleted';

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function changeStatus(Buyer $buyer, Request $request)
    {
        $buyer = $this->buyerService->changeStatus($buyer, $request->is_active);
        $status = $buyer ? 'success' : 'error';
        $message = $buyer ? ($buyer->is_active? 'Buyer status change activated' : 'Buyer status is deactivated') : 'Buyer status could not be activated';

        return response()->json([
            'status' => $status,
            'message' => $message,
            'buyer' => $buyer
        ]);
    }
}
