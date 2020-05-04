<?php namespace Tests\DotFiler\Targets;

use Tests\DotFiler\TestValues;
use Tests\DotFiler\DotFilerTestCase;
use DotFiler\Targets\ExistingTargets;
use DotFiler\Targets\ConfiguredTargets;

class ExistingTargetsTest extends DotFilerTestCase
{
    use TestValues;

    function test_can_identify_existing_targets_from_a_list_of_configured_targets()
    {
        $configured = ConfiguredTargets::fromTargetFile($this->targetFile);
        $existing = ExistingTargets::fromConfigured($configured);

        self::assertSame(
            [
                $this->basePath . 'tests/test-environment/config-directory',
                $this->basePath . 'tests/test-environment/example-file.txt',
                $this->basePath . 'tests/test-environment/managed-file.txt',
                $this->basePath . 'tests/test-environment/symlink',
            ],
            $this->targetPaths($existing)
        );
    }
}