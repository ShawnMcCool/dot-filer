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

    public function containsTarget(TargetPath $target): bool
    {
        $targetRepoPath = $this->repoTargetPath($target);

        return $this->repoContainsPath($targetRepoPath) &&
            $this->repoTargetPathExists($target);
    }

    private function repoTargetPathExists(TargetPath $target): bool
    {
        return file_exists(
            $this->repoTargetPath($target)
        );
    }

    public function repoTargetPath(TargetPath $targetPath)
    {
        return $this->repoPath . $targetPath->path();
    }

    private function repoContainsPath(string $targetRepoPath): bool
    {
        return strpos($targetRepoPath, $this->repoPath) == 0;
    }

    public static function fromString(string $path)
    {
        $path = realpath($path);

        if ( ! $path || ! is_dir($path)) {
            throw PathNotFound::repoDirectory($path);
        }
        
        if ( ! is_writable($path)) {
            throw InvalidPermissions::pathMustBeWritable($path);
        }

        return new static($path);
    }
}