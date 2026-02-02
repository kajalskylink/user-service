<?php

namespace App\Services;

use App\Models\Buyer;

class BuyerService extends BaseModelService
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
        return Buyer::class;
    }

    public function getActiveBuyer($isActive = true)
    {
        return $this->model()::where('is_active', $isActive)->get();
    }

    public function changeStatus(Buyer $buyer, $isActive)
    {
        $isActive = ($buyer->is_active) ? false : true;
        $buyer->is_active = $isActive;
        $buyer->save();
        return $buyer;
    }
}
