<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginThrottleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * POST /login dibatasi 5 percobaan per menit per IP (throttle:5,1)
     * untuk mencegah brute force password.
     */
    public function test_login_is_rate_limited_after_five_failed_attempts(): void
    {
        for ($attempt = 1; $attempt <= 5; $attempt++) {
            $this->post('/login', [
                'email' => 'admin@aset.com',
                'password' => 'password-salah',
            ])->assertStatus(302);
        }

        $this->post('/login', [
            'email' => 'admin@aset.com',
            'password' => 'password-salah',
        ])->assertStatus(429);
    }
}
