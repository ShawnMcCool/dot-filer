<?php namespace Tests\DotFiler;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\AssertionFailedError;
use Tests\DotFiler\GenerateTestEnvironment\GenerateTestEnvironment;
use Tests\DotFiler\GenerateTestEnvironment\TestEnvironmentPathIsInvalid;

abstract class DotFilerTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        try {
            GenerateTestEnvironment::generate();
        } catch (TestEnvironmentPathIsInvalid $e) {
            throw new AssertionFailedError($e->getMessage());
        }
    }

}