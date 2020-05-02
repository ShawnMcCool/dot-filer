<?php namespace Tests\DotFiler;

use DotFiler\Targets\ConfiguredTargets;

class TargetsTest extends DotFilerTestCase
{
    use TestValues;

    function test_can_be_created_from_file()
    {
        $targets = ConfiguredTargets::fromTargetFile($this->targetFile);
        self::assertCount(2, $targets->all());
    }
}