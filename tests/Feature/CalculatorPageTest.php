<?php

namespace Tests\Feature;

use Tests\TestCase;

class CalculatorPageTest extends TestCase
{
    public function test_calculator_page_returns_a_successful_response(): void
    {
        $response = $this->get(route('calculator'));

        $response->assertOk();
        $response->assertSee('Thuisbatterij calculator', false);
        $response->assertSee('SoftwareApplication', false);
        $response->assertSee('System Configurator', false);
    }
}
