<?php namespace DotFiler\Targets;

/**
 * A managed target is one whose target path has been
 * replaced by a symbolic link and whose home resides
 * within the specific repo path.
 */
final class ManagedTarget implements TargetPath
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

    /**
     * Return a managed target if the target file is a symlink that points to
     * the target repo path and if the target repo path exists.
     */
    public static function check(ExistingTarget $target, RepoPath $repoPath): ?self
    {
        if (
            is_link($target->path()) &&
            static::symlinkPointsToRepoTargetPath($target->path(), $repoPath->repoTargetPath($target))
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