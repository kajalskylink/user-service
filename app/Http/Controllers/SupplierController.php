<?php

namespace App\Http\Controllers;

use App\Constants\Constants;
use App\Http\Requests\Supplier\CreateSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use App\Models\Supplier;
use App\Services\SupplierService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use PHPUnit\TextUI\Configuration\Constant;

class SupplierController extends Controller implements HasMiddleware
{
    protected SupplierService $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:can-view-supplier', only: ['index']),
            new Middleware('permission:can-view-details-supplier', only: ['show']),
            new middleware('permission:can-create-supplier', only: ['create','store']),
            new middleware('permission:can-edit-supplier', only: ['edit','update','changeStatus']),
            new middleware('permission:can-delete-supplier', only: ['destroy'])
        ];
    }

    public function index()
    {
        $suppliers = $this->supplierService->all();

        $responseData = [
            'suppliers' => $suppliers,
        ];

        return response()->json($responseData);
    }

    public function store(CreateSupplierRequest $request)
    {
        $validatedData = $request->validated();
        $supplier = $this->supplierService->create($validatedData);
        $status = $supplier ? Constants::SUCCESS : Constants::ERROR;
        $message = $supplier ? 'Supplier created succesfully' : 'Supplier could not be created';

        return response()->json([
            'status' => $status,
            'message' => $message,
            'supplier' => $supplier
        ]);
    }

    public function show(Supplier $supplier)
    {
        $responseData = [
            'supplier' => $supplier
        ];

        return response()->json( $responseData);
    }

    public function edit(Supplier $supplier)
    {
        $responseData = [
            'supplier' => $supplier
        ];

        return response()->json($responseData);
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $validatedData = $request->validated();
        $isUpdated = $this->supplierService->update($supplier, $validatedData);
        $status = $isUpdated ? Constants::SUCCESS : Constants::ERROR;
        $message = $isUpdated ? 'Supplier updated succesfully' : 'Supplier could not be updated';

        return response()->json([
            'status' => $status,
            'message' => $message,
            'supplier' => $supplier
        ]);
    }

    public function destroy(Supplier $supplier)
    {
        $isDeleted = $this->supplierService->delete($supplier->id);
        $status = $isDeleted ? Constants::SUCCESS : Constants::ERROR;
        $message = $isDeleted ? 'Supplier deleted succesfully' : 'Supplier could not be deleted';

        return response()->json([
            'status' => $status,
            'message' => $message,
            'supplier' => $supplier
        ]);
    }

    public function changeStatus(Supplier $supplier, Request $request)
    {
        $supplier = $this->supplierService->changeStatus($supplier, $request->is_active);
        $status = $supplier ? 'success' : 'error';
        $message = $supplier ? ($supplier->is_active? 'Supplier status change activated' : 'Supplier status is deactivated') : 'Supplier status could not be activated';

        return response()->json([
            'status' => $status,
            'message' => $message,
            'supplier' => $supplier
        ]);
    }
}
