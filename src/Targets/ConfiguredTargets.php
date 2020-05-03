<?php namespace DotFiler\Targets;

use DotFiler\TargetFile;
use DotFiler\Collections\Collection;

/**
 * A collection of targets straight from the target file.
 *
 * A configured target is an unprocessed file path. It is the first step
 * of a target's lifespan directly loaded from the target file.
 */
final class ConfiguredTargets
{
    private Collection $targets;

    private function __construct(Collection $targets)
    {
        $this->targets = $targets;
    }

    /**
     * Returns a collection with all configured targets.
     */
    public function all(): Collection
    {
        return $this->targets;
    }

    /**
     * Create a collection of configured targets. This fails if the target
     * file does not exist.
     */
    public static function fromTargetFile(TargetFile $targetFile): self
    {

        $configuredTargets =
            $targetFile->contents()
                       ->map(
                           fn($path) => ConfiguredTarget::fromPath(
                               trim($path)
                           )
                       );

        return new static($configuredTargets);
    }
}