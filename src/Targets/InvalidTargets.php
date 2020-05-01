<?php namespace DotFiler\Targets;

use DotFiler\Collections\Collection;

final class InvalidTargets
{
    private Collection $targetPaths;

    private function __construct(Collection $paths)
    {
        $this->targetPaths = $paths;
    }

    public function paths(): Collection
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

    public static function fromUnvalidatedTargets(UnvalidatedTargets $unvalidatedTargets)
    {
        $invalidTargets = $unvalidatedTargets->paths()->filter(
            fn(UnvalidatedTarget $unvalidated) => static::isInvalidPath($unvalidated)
        )->map(
            fn(UnvalidatedTarget $unvalidated) => InvalidTarget::fromUnvalidatedTarget($unvalidated)
        );

        return new static($invalidTargets);
    }

    private static function isInvalidPath(UnvalidatedTarget $path): bool
    {
        return ! file_exists($path->toString());
    }
}