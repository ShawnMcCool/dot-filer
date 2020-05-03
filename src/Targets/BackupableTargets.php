<?php namespace DotFiler\Targets;

use DotFiler\Collections\Collection;

/**
 * A collection of processable targets.
 *
 * A target is processable if it is both an unprocessed target
 * and there's no permission issue or unwanted files in the way
 * of its transition into management.
 */
final class BackupableTargets
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

    public static function fromUnprocessed(UnprocessedTargets $unprocessedTargets, RepoPath $repoPath)
    {
        return new static(
            $unprocessedTargets
                ->all()
                ->map(
                    fn(UnprocessedTarget $unprocessed) => BackupableTarget::check($unprocessed, $repoPath)
                )->filter()
        );
    }
}