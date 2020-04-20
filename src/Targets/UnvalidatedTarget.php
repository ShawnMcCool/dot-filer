<?php namespace DotFiler\Targets;

final class UnvalidatedTarget
{
    private string $targetPath;

    private function __construct(string $targetPath)
    {
        $this->targetPath = $targetPath;
    }

    public function toString(): string
    {
        return $this->targetPath;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public static function fromString(string $targetPath): self
    {
        return new static($targetPath);
    }
}