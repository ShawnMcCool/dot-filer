<?php namespace Tests\DotFiler;

use ReflectionClass;
use PHPUnit\Framework\TestCase;
use DotFiler\Targets\TargetPath;
use DotFiler\Collections\Collection;
use PHPUnit\Framework\AssertionFailedError;
use Tests\DotFiler\GenerateTestEnvironment\GenerateTestEnvironment;
use Tests\DotFiler\GenerateTestEnvironment\TestEnvironmentPathIsInvalid;

abstract class DotFilerTestCase extends TestCase
{
    use TestValues;
    
    protected function setUp(): void
    {
        parent::setUp();

        try {
            GenerateTestEnvironment::generate();
        } catch (TestEnvironmentPathIsInvalid $e) {
            throw new AssertionFailedError($e->getMessage());
        }

        $this->initializeTestValues();
    }

    protected function targetPaths($targets)
    {
        return $targets->all()->map(
            fn(TargetPath $target) => $target->path()
        )->toArray();
    }

    protected function injectCollection($targetCollection, Collection $targetsToInject)
    {
        $property = (new ReflectionClass($targetCollection))
            ->getProperty('targets');

        $property->setAccessible(true);
        $property->setValue($targetCollection, $targetsToInject);
    }

}