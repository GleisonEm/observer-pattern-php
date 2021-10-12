<?php

namespace App\Database\Repository;

use App\Repository\EmailRepository;
use App\Models\Email;
use PDO;

class PdoEmailRepository implements \SplSubject, EmailRepository
{
    private PDO $connection;
    private $observers = [];

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->observers["*"] = [];
    }

    private function initEventGroup(string $event = "*"): void
    {
        if (!isset($this->observers[$event])) {
            $this->observers[$event] = [];
        }
    }

    private function getEventObservers(string $event = "*"): array
    {
        $this->initEventGroup($event);
        $group = $this->observers[$event];
        $all = $this->observers["*"];

        return array_merge($group, $all);
    }

    public function add(Email $email): bool
    {
        $insert = "INSERT INTO emails (title, content, creator_id, receiver_id, date_created) VALUES (
            '{$email->title()}',
            '{$email->content()}',
            '{$email->creatorId()}',
            '{$email->receiverId()}',
            '{$email->dateCreated()->format('Y-m-d')}'
        );";

        $success = $this->connection->exec($insert);
        $email->defineId($this->connection->lastInsertId());
        $this->notify("email:created", $email);

        return boolval($success);
    }

    public function attach(\SplObserver $observer, string $event = "*"): void
    {
        $this->initEventGroup($event);
        $this->observers[$event][] = $observer;
    }

    public function detach(\SplObserver $observer, string $event = "*"): void
    {
        foreach ($this->getEventObservers($event) as $key => $s) {
            if ($s === $observer) {
                unset($this->observers[$event][$key]);
            }
        }
    }

    public function notify(string $event = "*", $data = null): void
    {
        echo "Transmissão do evento: '$event'\n";

        foreach ($this->getEventObservers($event) as $observer) {
            $observer->update($this, $event, $data, $this->connection);
        }
    }
}