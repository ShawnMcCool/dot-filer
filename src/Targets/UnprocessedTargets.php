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

    public static function fromExisting(ExistingTargets $existingTargets): self
    {
        return new static(
            $existingTargets
                ->all()
                ->map(
                    fn(ExistingTarget $existing) => UnprocessedTarget::check($existing)
                )->filter()
        );
    }
}