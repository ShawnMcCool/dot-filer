<?php namespace DotFiler\Targets;

final class RepoPath
{
    private string $repoPath;

    private function __construct(string $repoPath)
    {
        $this->repoPath = $repoPath;
    }

    public function toString(): string
    {
        return $this->repoPath;
    }

    public function containsTarget(ExistingTarget $target): bool
    {
        $targetRepoPath = $this->repoPath . $target->path();
        
        return strpos($targetRepoPath, $this->repoPath) == 0 &&
            file_exists($targetRepoPath);
    }

    public static function fromString(string $path)
    {
        $path = realpath($path);

        if ( ! $path || ! is_dir($path)) {
            throw PathNotFound::repoDirectory($path);
        }

        return new static($path);
    }
}