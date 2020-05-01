<?php namespace DotFiler\Targets;

use DotFiler\Collections\Collection;

final class UnprocessedTargets
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

    public static function fromValidTargets(ValidTargets $validTargets)
    {
        $unprocessedTargets = $validTargets->paths()->filter(
            fn($targetPath) => ! is_link($targetPath)
        )->map(
            fn($targetPath) => UnprocessedTarget::fromValidTarget($targetPath)
        );

        return new static($unprocessedTargets);
    }
}