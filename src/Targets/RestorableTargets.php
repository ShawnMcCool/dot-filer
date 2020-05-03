<?php namespace DotFiler\Targets;

use DotFiler\Collections\Collection;

/**
 * A collection of restorable targets.
 *
 * A restorable target is one that is otherwise managed but does not 
 * have a symlink at the target path which points to its repo path.
 */
final class RestorableTargets
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

    public static function fromConfigured(ConfiguredTargets $configured, RepoPath $repoPath)
    {
        return new static(
            $configured
                ->all()
                ->map(
                    fn(ConfiguredTarget $configured) => RestorableTarget::check($configured, $repoPath)
                )->filter()
        );
    }
}