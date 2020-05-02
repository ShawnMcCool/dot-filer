<?php namespace DotFiler\Targets;

/**
 * A processable target is a file or directory that
 * is specified as a backup target that has not yet
 * become managed and that has valid permissions and
 * available room in the repo directory.
 */
final class ProcessableTarget implements TargetPath
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
     * Return a Processable target if have the permissions to replace the target path
     * and there's nothing blocking us from creating the repo target path
     */
    public static function check(UnprocessedTarget $target, RepoPath $repoPath): ?self
    {
        if (
            ! is_writable($target->path()) ||
            $repoPath->containsTarget($target)
        ) {
            return null;
        }

        return new static($target->path());
    }
}