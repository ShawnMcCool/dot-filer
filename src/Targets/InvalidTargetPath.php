<?php namespace DotFiler\Targets;

final class InvalidTargetPath
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

    public static function fromUnvalidatedTargetPath(UnvalidatedTargetPath $path): self
    {
        return new static($path->toString());
    }
}