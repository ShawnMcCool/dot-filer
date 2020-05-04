<?php namespace Tests\DotFiler\Targets;

use Tests\DotFiler\TestValues;
use Tests\DotFiler\DotFilerTestCase;
use DotFiler\Targets\ConfiguredTargets;

class ConfiguredTargetsTest extends DotFilerTestCase
{
    use TestValues;

    function test_can_load_target_file()
    {
        $targets = ConfiguredTargets::fromTargetFile($this->targetFile);

        self::assertSame(
            [
                'tests/test-environment/config-directory',
                'tests/test-environment/example-file.txt',
                'tests/test-environment/does-not-exist.txt',
                'tests/test-environment/managed-file.txt',
                'tests/test-environment/symlink',
            ],
            $this->targetPaths($targets)
        );
    }
}