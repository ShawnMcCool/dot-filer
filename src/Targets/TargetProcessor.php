<?php namespace DotFiler\Targets;

final class TargetProcessor
{
    private RepoPath $repoPath;

    public function __construct(RepoPath $repoPath)
    {
        $this->repoPath = $repoPath;
    }

    public function process(UnprocessedTargets $targets)
    {
        die('bob');
//        $targets->each(
//            fn(UnprocessedTarget $target) => $this->
//        );
    }
}