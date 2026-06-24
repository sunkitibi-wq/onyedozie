<?php

namespace App\Services;

use RuntimeException;

class OtpCooldownException extends RuntimeException
{
    public function __construct(string $message, public readonly int $retryAfter)
    {
        parent::__construct($message);
    }
}
