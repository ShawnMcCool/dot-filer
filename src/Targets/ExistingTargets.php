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

    /**
     * Return an ExistingTarget collection that contains all targets
     * from the specified paths that have been located on the filesystem.
     */
    public static function fromConfigured(ConfiguredTargets $configuredTargets)
    {
        return new static(
            $configuredTargets
                ->all()
                ->map(
                    fn(ConfiguredTarget $configuredTarget) => ExistingTarget::locate($configuredTarget)
                )->filter()
        );
    }
}