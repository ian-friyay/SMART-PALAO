<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        Product::create([
            'branch_location' => 'Gloria',
            'size' => 'Large',
            'price' => 180.00,
            'stock' => 50,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('SMART PALAO');
    }
}
