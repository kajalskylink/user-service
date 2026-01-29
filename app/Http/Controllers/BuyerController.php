<?php

namespace App\Http\Controllers;

use App\Constants\Constants;
use App\Http\Requests\Buyer\CreateBuyerRequest;
use App\Http\Requests\Buyer\UpdateBuyerRequest;
use App\Models\Buyer;
use App\Services\BuyerService;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    protected BuyerService $buyerService;

    public function __construct(BuyerService $buyerService)
    {
        $this->buyerService = $buyerService;
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
}
