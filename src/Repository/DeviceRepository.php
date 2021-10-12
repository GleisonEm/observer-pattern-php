<?php

namespace App\Repository;

use App\Models\Device;

interface DeviceRepository
{
    public function add(Device $device): Device;
}