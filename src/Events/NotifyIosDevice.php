<?php

namespace App\Events;

class NotifyIosDevice
{
    public function notify($data): void
    {
        echo "Novo email na sua caixa de entrada: {$data->title()}\n";
    }
}