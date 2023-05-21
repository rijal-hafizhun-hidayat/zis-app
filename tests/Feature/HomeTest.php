<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class HomeTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_home_page(): void
    {
        $response = $this->get('/');
 
        $response->assertStatus(200);
    }

    public function test_donasi_page(): void{

        $response = $this->get('/donasi');
 
        $response->assertStatus(200);
    }
}
