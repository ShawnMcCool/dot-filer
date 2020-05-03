<?php namespace DotFiler;

use DotFiler\Targets\Results;
use DotFiler\Targets\RepoPath;
use DotFiler\Targets\TargetFile;
use DotFiler\Targets\PathNotFound;
use DotFiler\Targets\ManagedTarget;
use DotFiler\Targets\ManagedTargets;
use DotFiler\Targets\ExistingTargets;
use DotFiler\Targets\BackupProcedure;
use DotFiler\Targets\ConfiguredTarget;
use DotFiler\Targets\RestoreProcedure;
use DotFiler\Targets\RestorableTarget;
use DotFiler\Targets\ConfiguredTargets;
use DotFiler\Targets\UnprocessedTarget;
use DotFiler\Targets\NonExistingTarget;
use DotFiler\Targets\BackupableTarget;
use DotFiler\Targets\RestorableTargets;
use DotFiler\Targets\NonExistingTargets;
use DotFiler\Targets\UnprocessedTargets;
use DotFiler\Targets\BackupableTargets;

/**
 * Facade class that serves up a simple api and seeks to
 * improve comprehensibility to the model.
 */
final class DotFiler
{
    private TargetFile $targetFile;
    private RepoPath $repoPath;

    public function __construct(TargetFile $targetFile, RepoPath $repoPath)
    {
        $this->targetFile = $targetFile;
        $this->repoPath = $repoPath;
    }

    public function configuredTargets(): ConfiguredTargets
    {
        return ConfiguredTargets::fromTargetFile($this->targetFile);
    }

    public function existingTargets(): ExistingTargets
    {
        return ExistingTargets::fromConfigured($this->configuredTargets());
    }

    public function nonExistingTargets(): NonExistingTargets
    {
        return NonExistingTargets::fromConfigured($this->configuredTargets());
    }

    public function unprocessedTargets(): UnprocessedTargets
    {
        return UnprocessedTargets::fromExisting($this->existingTargets());
    }

    public function processableTargets(): BackupableTargets
    {
        return BackupableTargets::fromUnprocessed($this->unprocessedTargets(), $this->repoPath);
    }

    public function managedTargets(): ManagedTargets
    {
        return ManagedTargets::fromExisting($this->existingTargets(), $this->repoPath);
    }

    public function restorableTargets(): RestorableTargets
    {
        return RestorableTargets::fromConfigured($this->configuredTargets(), $this->repoPath);
    }

    public function processBackup(): Results
    {
        return
            (new BackupProcedure($this->repoPath))
                ->run(
                    $this->processableTargets()
                );
    }

    public function processRestore()
    {
        return
            (new RestoreProcedure($this->repoPath))
                ->run(
                    $this->restorableTargets()
                );
    }

    public function restoreStatus(string $path)
    {
        if ( ! $this->configuredTargets()->all()->first(
            fn(ConfiguredTarget $configured) => $configured->path() == $path
        )) {
            throw PathNotFound::pathNotFoundInTargetFile($path);
        }

        $mostAdvanced =
            $this->managedTargets()->all()->first(
                fn(ManagedTarget $managed) => $managed->path() == $path
            ) ?? $this->restorableTargets()->all()->first(
                fn(RestorableTarget $restorable) => $restorable->path() == $path
            );

        switch (get_class($mostAdvanced)) {
            case ManagedTarget::class:
                return 'managed';
            case RestorableTarget::class:
                return 'can be restored';
        }
    }

    public function backupStatus(string $path)
    {
        if ( ! $this->configuredTargets()->all()->first(
            fn(ConfiguredTarget $configured) => $configured->path() == $path
        )) {
            throw PathNotFound::pathNotFoundInTargetFile($path);
        }

        $mostAdvanced =
            $this->managedTargets()->all()->first(
                fn(ManagedTarget $managed) => $managed->path() == $path
            ) ??
            $this->processableTargets()->all()->first(
                fn(BackupableTarget $processable) => $processable->path() == $path
            ) ??
            $this->unprocessedTargets()->all()->first(
                fn(UnprocessedTarget $unprocessed) => $unprocessed->path() == $path
            ) ??
            $this->nonExistingTargets()->all()->first(
                fn(NonExistingTarget $nonExisting) => $nonExisting->path() == $path
            );

        switch (get_class($mostAdvanced)) {
            case ManagedTarget::class:
                return 'managed';
            case BackupableTarget::class:
                return 'ready for management';
            case UnprocessedTarget::class:
                return 'not ready for management';
            case NonExistingTarget::class:
                return 'target path not found';
        }
    }

    public function allTargetStatuses(): array
    {
        return
            $this->configuredTargets()
                 ->all()
                 ->map(
                     fn(ConfiguredTarget $configured) => [$configured->path(), $this->backupStatus($configured), $this->restoreStatus($configured)]
                 )->toArray();
    }
}