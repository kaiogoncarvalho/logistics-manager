<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Traits\DatabaseMigrationsOnce;

abstract class AcceptanceTestCase extends TestCase
{
    use DatabaseMigrationsOnce, DatabaseTransactions;
    
    protected function setUpTraits(){
        $this->runDatabaseMigrationsOnce();
        parent::setUpTraits();
    }
}
