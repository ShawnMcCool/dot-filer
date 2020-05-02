<?php namespace DotFiler\Targets;

/**
 * An unprocessed target is a file or directory that
 * is specified as a backup target that has not yet been
 * replaced by a symbolic link to the dotfile repository.
 */
final class UnprocessedTarget
{
    private string $path;

    private function __construct(string $path)
    {
        $this->path = $path;
    }

    public function toString(): string
    {
        return $this->path;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * Return an UnprocessedTarget if the valid target is determined
     * to not be a symlink that identifies a path inside the config repo.
     */
    public static function check(ExistingTarget $target, RepoPath $repoPath): ?self
    {
        if (static::isUnprocessed($target, $repoPath)) {
            return new static($target->path());
        }
        
        return null;
    }

    /**
     * Determine if a target is unprocessed by verifying that a
     * symlink exists at the specified path.
     */
    private static function isUnprocessed(ExistingTarget $target, RepoPath $repoPath): bool
    {
        return ! is_link($target->path()) && // if it's not a link
            ! $repoPath->containsTarget($target);
    }
}