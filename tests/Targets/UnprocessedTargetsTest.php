<?php namespace Tests\DotFiler\Targets;

use Tests\DotFiler\TestValues;
use Tests\DotFiler\DotFilerTestCase;
use DotFiler\Targets\ExistingTargets;
use DotFiler\Targets\ConfiguredTargets;
use DotFiler\Targets\UnprocessedTargets;

class UnprocessedTargetsTest extends DotFilerTestCase
{
    use TestValues;

    function test_can_identify_unprocessed_targets_in_a_fresh_repo()
    {
        $configured = ConfiguredTargets::fromTargetFile($this->targetFile);
        $existing = ExistingTargets::fromConfigured($configured);
        $unprocessed = UnprocessedTargets::fromExisting($existing);

        self::assertSame(
            [
                $this->basePath . 'tests/test-environment/config-directory',
                $this->basePath . 'tests/test-environment/example-file.txt',
                $this->basePath . 'tests/test-environment/managed-file.txt',
            ],
            $this->targetPaths($unprocessed)
        );
    }

    function test_can_ignore_processed_targets()
    {
        $repoPath = $this->basePath . 'tests/test-environment/repo/managed-file.txt';
        $originalPath = realpath('tests/test-environment/managed-file.txt');

        rename($originalPath, $repoPath);
        symlink($repoPath, $originalPath);
        
        $configured = ConfiguredTargets::fromTargetFile($this->targetFile);
        $existing = ExistingTargets::fromConfigured($configured);
        $unprocessed = UnprocessedTargets::fromExisting($existing);

        self::assertSame(
            [
                $this->basePath . 'tests/test-environment/config-directory',
                $this->basePath . 'tests/test-environment/example-file.txt',
            ],
            $this->targetPaths($unprocessed)
        );
    }
}