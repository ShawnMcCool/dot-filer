<?php namespace DotFiler\Targets;

final class UnprocessedTarget
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

    public static function fromValidTarget(ValidTarget $target): self
    {
        return new static($target->toString());
    }
}