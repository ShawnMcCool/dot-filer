<?php namespace DotFiler\Targets;

use DotFiler\Collections\Collection;

/**
 * A collection of non-existing targets.
 *
 * A target is non-existing if we could not locate them.
 */
final class NonExistingTargets
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

    /**
     * Return a NonExistingTargets collection that contains all targets
     * from the configured collection that we have not been able to locate.
     */
    public static function fromConfigured(ConfiguredTargets $configuredTargets)
    {
        $nonExistingTargets =
            $configuredTargets->all()
                              ->map(
                                   fn(ConfiguredTarget $configured) => NonExistingTarget::locate($configured)
                               )->filter();

        return new static($nonExistingTargets);
    }
}