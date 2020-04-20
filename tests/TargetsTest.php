<?php namespace Tests\DotFiler;

use DotFiler\Targets\UnvalidatedTargets;

class TargetsTest extends DotFilerTestCase
{
    use TestValues;

    function test_can_be_created_from_file()
    {
        $targets = UnvalidatedTargets::fromFile($this->targetFile);
        self::assertCount(2, $targets->paths());
    }
}
