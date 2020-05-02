<?php namespace DotFiler\Targets;

use DotFiler\Collections\Collection;

/**
 * A collection of unprocessed targets.
 *
 * A target is unprocessed if it hasn't been moved into the
 * dot file repository and replaced by a symlink.
 */
final class UnprocessedTargets
{
    private Collection $targets;

    private function __construct(Collection $targets)
    {
        $this->targets = $targets;
    }

    public function all(): Collection
    {
        return $this->targets;
    }

    public function count(): int
    {
        return $this->targets->count();
    }

    public function each(callable $f): void
    {
        $this->targets->each($f);
    }

    public static function fromExisting(ExistingTargets $existingTargets, RepoPath $repoPath)
    {
        $unprocessedTargets =
            $existingTargets->all()
                            ->map(
                             fn(ExistingTarget $existing) => UnprocessedTarget::check($existing, $repoPath)
                         )->filter();

        return new static($unprocessedTargets);
    }
}