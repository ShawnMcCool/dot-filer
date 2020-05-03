<?php namespace DotFiler\Targets;

use DotFiler\RepoPath;

/**
 * A restorable target is one which exists at the target 
 * repo path but which doesn't have a symlink at the target
 * path which points to it.
 */
final class RestorableTarget implements TargetPath
{
    private string $path;

    private function __construct(string $path)
    {
        $this->path = $path;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function __toString(): string
    {
        return $this->path();
    }

    public static function check(ConfiguredTarget $target, RepoPath $repoPath): ?self
    {
        if (
            // if target exists in the repo
            file_exists($repoPath->repoTargetPath($target)) &&
            // and
            (
                // the target path is not a symlink
                ! is_link($target->path()) ||
                // the symlink doesn't point to the target repo path
                ! static::symlinkPointsToRepoTargetPath($target->path(), $repoPath->repoTargetPath($target))
            )
        ) {
            return new static($target->path());
        }   

        return null;
    }

    private static function symlinkPointsToRepoTargetPath(string $path, string $repoTargetPath)
    {
        return realpath($path) == $repoTargetPath;
    }
}