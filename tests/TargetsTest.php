<?php namespace Tests\DotFiler;

use DotFiler\Targets;

class TargetsTest extends DotFilerTestCase
{
    use TestValues;

    function test_can_be_created_from_file()
    {
        return;
        $targets = Targets::fromFile('..');
        self::assertCount(2, $targets->allPaths());
    }
}
