<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CurrencyTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testQuote()
    {
        $response = $this->json('GET', '/api/quote', [
            'amount' => 100,
            'fromCurrencyCode' => 'USD',
            'toCurrencyCode' => 'EUR'
        ]);

        $response->assertStatus(200);
    }
}
