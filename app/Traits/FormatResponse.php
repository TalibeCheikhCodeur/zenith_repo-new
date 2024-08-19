<?php

namespace App\Traits;

trait FormatResponse
{
    public function response($status, $message, $data)
    {
        return [
            "statut" => $status,
            "message" => $message,
            "data" => $data
        ];
    }
}
