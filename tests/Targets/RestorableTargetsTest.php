<?php namespace Tests\DotFiler\Targets;

use Tests\DotFiler\TestValues;
use Tests\DotFiler\DotFilerTestCase;
use DotFiler\Targets\ExistingTargets;
use DotFiler\Targets\ConfiguredTargets;
use DotFiler\Targets\BackupableTargets;
use DotFiler\Targets\UnprocessedTargets;

class RestorableTargetsTest extends DotFilerTestCase
{
    use TestValues;
//
//    function test_can_identify_backupable_targets()
//    {
//        $configured = ConfiguredTargets::fromTargetFile($this->targetFile);
//        $existing = ExistingTargets::fromConfigured($configured);
//        $unprocessed = UnprocessedTargets::fromExisting($existing);
//        $backupable = BackupableTargets::fromUnprocessed($unprocessed, $this->repoPath);
//
//        self::assertSame(
//            [
//                $this->basePath . 'tests/test-environment/config-directory',
//                $this->basePath . 'tests/test-environment/example-file.txt',
//                $this->basePath . 'tests/test-environment/managed-file.txt',
//            ],
//            $this->targetPaths($backupable)
//        );
//    }
//
//    function test_backupable_targets_must_be_writable()
//    {
//        chmod($this->basePath . 'tests/test-environment/example-file.txt', 0555);
//
//        $configured = ConfiguredTargets::fromTargetFile($this->targetFile);
//        $existing = ExistingTargets::fromConfigured($configured);
//        $unprocessed = UnprocessedTargets::fromExisting($existing);
//        $backupable = BackupableTargets::fromUnprocessed($unprocessed, $this->repoPath);
//
//        self::assertSame(
//            [
//                $this->basePath . 'tests/test-environment/config-directory',
//                $this->basePath . 'tests/test-environment/managed-file.txt',
//            ],
//            $this->targetPaths($backupable)
//        );
//
//        chmod($this->basePath . 'tests/test-environment/example-file.txt', 0777);
//    }
//
//    function test_repo_path_must_not_already_contain_a_liked_named_path()
//    {
//        mkdir($this->basePath . 'tests/test-environment/repo/vagrant/tests/test-environment', 0777, true);
//        touch($this->basePath . 'tests/test-environment/repo/vagrant/tests/test-environment/example-file.txt');
//
//        $configured = ConfiguredTargets::fromTargetFile($this->targetFile);
//        $existing = ExistingTargets::fromConfigured($configured);
//        $unprocessed = UnprocessedTargets::fromExisting($existing);
//        $backupable = BackupableTargets::fromUnprocessed($unprocessed, $this->repoPath);
//
//        self::assertSame(
//            [
//                $this->basePath . 'tests/test-environment/config-directory',
//                $this->basePath . 'tests/test-environment/managed-file.txt',
//            ],
//            $this->targetPaths($backupable)
//        );
//    }
}