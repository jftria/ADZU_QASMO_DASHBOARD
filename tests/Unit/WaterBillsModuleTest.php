<?php

namespace Tests\Unit;

use App\Http\Controllers\ReportController;
use App\Models\User;
use PHPUnit\Framework\TestCase;

class WaterBillsModuleTest extends TestCase
{
    public function test_water_bills_are_available_in_report_types_and_modules(): void
    {
        $this->assertArrayHasKey('water-bills', User::REPORT_TYPES);

        $controller = new ReportController();
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('allModules');
        $method->setAccessible(true);

        $modules = $method->invoke($controller);

        $this->assertArrayHasKey('water-bills', $modules);
        $this->assertSame('Water Consumption', $modules['water-bills']['label']);
    }
}
