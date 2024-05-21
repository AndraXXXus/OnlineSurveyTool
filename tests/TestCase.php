<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
//php artisan test --coverage
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}
