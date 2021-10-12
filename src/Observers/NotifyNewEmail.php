<?php

namespace App\Observers;

class NotifyNewEmail implements \SplObserver
{
    private $map_notify_all_devices = [
        'windows' => '\App\Events\NotifyWindowsDevice',
        'android' => '\App\Events\NotifyAndroidDevice',
        'ios' => '\App\Events\NotifyIosDevice',
    ];

    public function update(
        \SplSubject $repository,
        string $event = null,
        $data = null,
        $connection = null
    ): void {
        $sql = 'SELECT * FROM devices WHERE user_id = :user_id';
        $query = $connection->prepare($sql);
        $id = $data->receiverId();
        $query->bindParam(':user_id', $id, \PDO::PARAM_INT);
        $query->execute();
        $result_query = $query->fetchAll(\PDO::FETCH_ASSOC);
        $platforms = array_column($result_query, 'platform');

        foreach ($platforms as $platform) {
            $method = $this->map_notify_all_devices[$platform];
            $event = new $method();
            $event->notify($data);
        }
    }
}