<?php

namespace App\Services;

use App\Models\Supplier;

class SupplierService extends BaseModelService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function model(): string
    {
        return Supplier::class;
    }

    public function changeStatus(Supplier $supplier, $isActive)
    {
        $isActive = ($supplier->is_active) ? false : true;
        $supplier->is_active = $isActive;
        $supplier->save();
        return $supplier;
    }
}
