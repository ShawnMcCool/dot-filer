<?php namespace DotFiler\Targets;

use DotFiler\Collections\Collection;

final class ValidTargets
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

    public function unprocessed(): UnprocessedTargets
    {
        return UnprocessedTargets::fromValidTargets($this);
    }

    public static function fromUnvalidatedTargets(UnvalidatedTargets $unvalidatedTargets)
    {
        $validTargets = $unvalidatedTargets->paths()->filter(
                fn(UnvalidatedTarget $unvalidated) => static::isValidPath($unvalidated)
            )->map(
                fn(UnvalidatedTarget $unvalidated) => ValidTarget::fromUnvalidatedTarget($unvalidated)
            );
        
        return new static($validTargets);
    }

    private static function isValidPath(UnvalidatedTarget $path): bool
    {
        return file_exists($path->toString());
    }
}