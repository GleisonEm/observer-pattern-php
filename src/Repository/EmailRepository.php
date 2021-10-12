<?php

namespace App\Repository;

use App\Models\Email;

interface EmailRepository
{
    public function add(Email $email): bool;
}