<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */

    public function test_dashboard_page(): void{
        $this->withSession([
            'isLogin' => true,
            'role' => 1
        ]);
        
        $this->get('/dashboard')->assertStatus(200);
    }
}
