<?php namespace DotFiler;

use DotFiler\Collections\Collection;

final class TargetFile
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

    public function contents(): Collection
    {
        return collect(
            file($this->path)
        );
    }

    public static function fromString(string $path)
    {
        $resolvedPath = realpath($path);

        if ( ! $resolvedPath || ! is_file($resolvedPath)) {
            throw PathNotFound::targetsFile($path);
        }

        return new static($resolvedPath);
    }
}