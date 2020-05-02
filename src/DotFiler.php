<?php namespace DotFiler;

use DotFiler\Targets\Results;
use DotFiler\Targets\RepoPath;
use DotFiler\Targets\TargetFile;
use DotFiler\Targets\PathNotFound;
use DotFiler\Targets\ManagedTarget;
use DotFiler\Targets\ManagedTargets;
use DotFiler\Targets\ExistingTargets;
use DotFiler\Targets\TargetProcessor;
use DotFiler\Targets\ConfiguredTarget;
use DotFiler\Targets\ConfiguredTargets;
use DotFiler\Targets\UnprocessedTarget;
use DotFiler\Targets\NonExistingTarget;
use DotFiler\Targets\ProcessableTarget;
use DotFiler\Targets\NonExistingTargets;
use DotFiler\Targets\UnprocessedTargets;
use DotFiler\Targets\ProcessableTargets;

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

    public function processableTargets(): ProcessableTargets
    {
        return ProcessableTargets::fromUnprocessed($this->unprocessedTargets(), $this->repoPath);
    }

    public function managedTargets(): ManagedTargets
    {
        return ManagedTargets::fromExisting($this->existingTargets(), $this->repoPath);
    }

    public function processBackup(): Results
    {
        return
            (new TargetProcessor($this->repoPath))
                ->process(
                    $this->processableTargets()
                );
    }

    public function targetStatus(string $path)
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
                fn(ProcessableTarget $processable) => $processable->path() == $path
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
            case ProcessableTarget::class:
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
                     fn(ConfiguredTarget $configured) => [$configured->path(), $this->targetStatus($configured)]
                 )->toArray();
    }
}