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
        // the path to the file with ./ and ../ resolved
        // the path to the target if the file is a link
        $resolvedPath = realpath($target->path());

        // if it's a link then we just want the path of the link
        if (is_link($target->path())) {
            $basename = basename($target->path());
            $dirname = dirname($target->path());
            
            $path = realpath($dirname) .'/'. $basename;
            
            return new static($path);
        }

        // if it's not a link then resolve all path symbols 
        if (file_exists($resolvedPath)) {
            return new static($resolvedPath);
        }

        return null;
    }
}