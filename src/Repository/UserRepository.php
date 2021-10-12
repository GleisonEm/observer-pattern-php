<?php

namespace App\Repository;

use App\Models\User;

interface UserRepository
{   
    public function add(User $user): User;
}