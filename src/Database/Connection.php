<?php

namespace App\Database;

class Connection 
{   
    public static function create(): \PDO
    {
        $path = dirname(__DIR__) . '/../database.sqlite';
        return new \PDO("sqlite:$path");
    }
}