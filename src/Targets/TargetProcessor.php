<?php namespace DotFiler\Targets;

final class TargetProcessor
{
    private string $repoPath;

    public function __construct(string $repoPath)
    {
        $repoPath = realpath($repoPath);
        
        if ( ! $repoPath || ! is_dir($repoPath)) {
            throw PathNotFound::repoDirectory($repoPath);
        }
        
        $this->repoPath = $repoPath;
    }

    public function process(UnprocessedTargets $targets)
    {
        $targets->each(
            fn(UnprocessedTarget $target) => $this->
        );
    }
}