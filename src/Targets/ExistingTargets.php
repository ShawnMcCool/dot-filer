<?php namespace DotFiler\Targets;

use DotFiler\Collections\Collection;

/**
 * A collection of existing targets.
 *
 * A target exists if we have verified that there is a file or
 * directory at the specified path.
 */
final class ExistingTargets
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
     * Return an ExistingTarget collection that contains all targets
     * from the specified paths that have been located on the filesystem.
     */
    public static function fromConfigured(ConfiguredTargets $configuredTargets)
    {
        $existingTargets =
            $configuredTargets->all()
                              ->map(
                                   fn(ConfiguredTarget $configuredTarget) => ExistingTarget::locate($configuredTarget)
                               )->filter();

        return new static($existingTargets);
    }
}