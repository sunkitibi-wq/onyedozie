<?php

return [
    'length' => env('OTP_LENGTH', 6),
    'expires_in_minutes' => env('OTP_EXPIRES_IN_MINUTES', 10),
    'max_attempts' => env('OTP_MAX_ATTEMPTS', 5),
    'resend_cooldown_seconds' => env('OTP_RESEND_COOLDOWN_SECONDS', 60),

    'request_rate_limit' => [
        'max_attempts' => env('OTP_REQUEST_RATE_LIMIT_MAX_ATTEMPTS', 5),
        'decay_seconds' => env('OTP_REQUEST_RATE_LIMIT_DECAY_SECONDS', 3600),
    ],

    'verify_rate_limit' => [
        'max_attempts' => env('OTP_VERIFY_RATE_LIMIT_MAX_ATTEMPTS', 10),
        'decay_seconds' => env('OTP_VERIFY_RATE_LIMIT_DECAY_SECONDS', 300),
    ],

    'testing_code' => env('OTP_TEST_CODE'),
];
