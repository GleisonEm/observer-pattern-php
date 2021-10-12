<?php

namespace App\Models;

class User
{
    private ?int $id;
    private string $name;

    public function __construct(
        ?int $id,
        string $name,
        string $email,
        \DateTimeInterface $date_created
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->date_created = $date_created;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
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
