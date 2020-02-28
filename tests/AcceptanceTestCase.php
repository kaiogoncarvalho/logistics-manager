<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;

abstract class AcceptanceTestCase extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;
}
