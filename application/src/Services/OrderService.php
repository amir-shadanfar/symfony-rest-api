<?php

namespace App\Services;

class OrderService
{
    /**
     * @return string
     */
    public function generateOrderCode()
    {
        return '#' . rand(9, 99999999999);
    }
}
