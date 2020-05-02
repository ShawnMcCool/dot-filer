<?php namespace DotFiler\Targets;

use DotFiler\Collections\Collection;

/**
 * A collection of managed targets.
 *
 * A managed target is one whose original location has been replaced
 * by a symlink that points to a mirrored location within the repo path.
 */
final class ManagedTargets
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

    public static function fromExisting(ExistingTargets $existingTargets, RepoPath $repoPath)
    {
        return new static(
            $existingTargets
                ->all()
                ->map(
                    fn(ExistingTarget $existing) => ManagedTarget::check($existing, $repoPath)
                )->filter()
        );
    }
}