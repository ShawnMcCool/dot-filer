<?php namespace DotFiler\Targets;

/**
 * An existing target contains a path for a directory or file
 * that we have verified exists on the filesystem. 
 */
final class ExistingTarget implements TargetPath
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
     * Locate the unvalidated target and return an ExistingTarget
     * if location is successful. 
     */
    public static function locate(ConfiguredTarget $target): ?self
    {
        $path = realpath($target->path());
        
        if ( ! static::pathExists($path)) {
            return null;
        }
        
        return new static($path);
    }

    /**
     * Determine if a target exists by locating it on the filesystem.
     */
    private static function pathExists(string $path): bool
    {
        return file_exists($path);
    }
}