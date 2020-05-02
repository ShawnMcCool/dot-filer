<?php namespace DotFiler\Targets;

/**
 * A non existing target's path could not be located on
 * the filesystem.
 */
final class NonExistingTarget implements TargetPath
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
     * Attempt to locate a configured target and return a non-existing
     * target if location is unsuccessful.
     */
    public static function locate(ConfiguredTarget $target): ?self
    {
        if (static::cannotBeFound($target)) {
            return new static($target->path());
        }

        return null;
    }

    /**
     * Determine whether a target can be found by looking on the filesystem.
     */
    private static function cannotBeFound(ConfiguredTarget $path): bool
    {
        return ! file_exists($path->path());
    }
}