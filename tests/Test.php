<?php namespace Tests\DotFiler;

use TargetsTest;
use DotFiler\Targets;
use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    use TestValues;

    function test_can_be_created_from_file()
    {
        $targets = Targets::fromFile($this->targetFile);
        self::assertCount(2, $targets->allPaths());
    }
}
