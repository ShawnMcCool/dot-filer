<?php namespace DotFiler\Targets;

use DotFiler\Collections\Collection;

final class InvalidTargets
{
    private Collection $targetPaths;

    private function __construct(Collection $paths)
    {
        $this->targetPaths = $paths;
    }

    public function allPaths(): Collection
    {
        return $this->targetPaths;
    }

    public function count(): int
    {
        return $this->targetPaths->count();
    }

    public function each(callable $f): void
    {
        $this->targetPaths->each($f);
    }

    public static function fromValidator(Collection $invalidTargets): self
    {
        return new static($invalidTargets);
    }
}