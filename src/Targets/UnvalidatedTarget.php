<?php namespace DotFiler\Targets;

final class UnvalidatedTargetPath
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

    public static function fromString(string $path): self
    {
        return new static($path);
    }
}