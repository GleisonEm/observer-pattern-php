<?php

namespace App\Models;

class Email
{
    private ?int $id;
    private string $name;
    private \DateTimeInterface $date_created;

    public function __construct(
        ?int $id,
        string $title,
        string $content,
        int $creator_id,
        int $receiver_id,
        string $status,
        \DateTimeInterface $date_created
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->creator_id = $creator_id;
        $this->receiver_id = $receiver_id;
        $this->status = $status;
        $this->date_created = $date_created;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function creatorId(): int
    {
        return $this->creator_id;
    }

    public function receiverId(): int
    {
        return $this->receiver_id;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function dateCreated(): \DateTimeInterface
    {
        return $this->date_created;
    }

    public function defineId(int $id): void
    {
        if (!is_null($this->id)) {
            throw new \DomainException('Vocẽ só pode definir o ID uma vez');
        }

        $this->id = $id;
    }

    public function defineStatus(string $status): void
    {
        $this->status = $status;
    }
}
