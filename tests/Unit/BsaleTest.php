<?php

namespace ticketeradigital\bsale\tests\Unit;

use ticketeradigital\bsale\Bsale;

class BsaleTest extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app): array
    {
        return [\ticketeradigital\bsale\BsaleServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        config()->set('bsale.base_url', 'https://api.bsale.io');
    }

    public function test_no_access_token()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Config access_token not set.');
        Bsale::makeRequest('/v1/products.json');
    }

    public function test_no_base_url()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Config base_url not set.');
        config(['bsale.access_token' => '123']);
        Bsale::makeRequest('/v1/products.json');
    }
}
