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

    public function update(Email $email): bool
    {
        $id = $email->id();
        $sql = 'UPDATE emails SET status = :status WHERE id = :id';
        $query_update = $this->connection->prepare($sql);
        $status = $email->status();
        $query_update->bindValue(':status', $status);
        $query_update->bindValue(':id', $id, PDO::PARAM_INT);

        return $query_update->execute();
    }

    public function add(Email $email): Email
    {
        $insert = "INSERT INTO emails (title, content, creator_id, receiver_id, date_created) VALUES (
            '{$email->title()}',
            '{$email->content()}',
            '{$email->creatorId()}',
            '{$email->receiverId()}',
            '{$email->dateCreated()->format('Y-m-d')}'
        );";

        $success = $this->connection->exec($insert);
        if ($success) {
            $email->defineId($this->connection->lastInsertId());
        }

        $this->notify("email:created", $email);

        return $email;
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