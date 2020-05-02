<?php namespace DotFiler\Targets;

/**
 * A configured target contains a path for a directory or file
 * that has not been verified to either exist or not exist.
 */
final class ConfiguredTarget
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
     * Create a configured target path instance from a
     * filesystem path that may or may not identify a 
     * file or directory.
     */
    public static function fromPath(string $path): self
    {
        return new static($path);
    }
}