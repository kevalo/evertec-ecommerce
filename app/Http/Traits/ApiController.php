<?php

namespace App\Http\Traits;

trait ApiController
{

    public function response(mixed $data = [], bool $status = true): array
    {
        return ['status' => $status, 'data' => $data];
    }

}
