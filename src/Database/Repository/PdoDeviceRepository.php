<?php

namespace App\Database\Repository;

use App\Repository\DeviceRepository;
use App\Models\Device;
use PDO;

class PdoDeviceRepository implements DeviceRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function add(Device $device): Device
    {
        $insert_query = "INSERT INTO devices (platform, user_id, date_created) VALUES (
            '{$device->platform()}',
            '{$device->userId()}',
            '{$device->dateCreated()->format('Y-m-d')}'
        );";
        $success = $this->connection->exec($insert_query);

        if ($success) {
            $device->defineId($this->connection->lastInsertId());
        }

        return $device;
    }
}