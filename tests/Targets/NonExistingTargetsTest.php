<?php namespace Tests\DotFiler\Targets;

use Tests\DotFiler\TestValues;
use Tests\DotFiler\DotFilerTestCase;
use DotFiler\Targets\ConfiguredTargets;
use DotFiler\Targets\NonExistingTargets;

class NonExistingTargetsTest extends DotFilerTestCase
{
    use TestValues;

    function test_can_identify_nonexisting_targets_from_a_list_of_configured_targets()
    {
        $configured = ConfiguredTargets::fromTargetFile($this->targetFile);
        $nonExisting = NonExistingTargets::fromConfigured($configured);

        self::assertSame(
            [
                'tests/test-environment/does-not-exist.txt',
            ],
            $this->targetPaths($nonExisting)
        );
    }
}