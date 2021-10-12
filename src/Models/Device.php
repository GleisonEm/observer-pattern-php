<?php

namespace App\Models;

class Device
{
    private ?int $id;
    private string $platform;
    private int $user_id;
    private \DateTimeInterface $date_created;

    public function __construct(
        ?int $id,
        string $platform,
        int $user_id,
        \DateTimeInterface $date_created
    ) {
        $this->id = $id;
        $this->platform = $platform;
        $this->user_id = $user_id;
        $this->date_created = $date_created;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function platform(): string
    {
        return $this->platform;
    }

    public function userId(): int
    {
        return $this->user_id;
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
}
