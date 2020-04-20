<?php namespace DotFiler\Targets;

final class ValidTargets
{
    private ValidTargetCollection $targetPaths;

    private function __construct(ValidTargetCollection $paths)
    {
        $this->targetPaths = $paths;
    }

    public function allPaths(): ValidTargetCollection
    {
        return $this->targetPaths;
    }

    public static function fromUnvalidatedTargets(UnvalidatedTargets $targets)
    {
        return new static(
            ValidTargetCollection::of(
                $targets->paths()->map(
                    fn(UnvalidatedTargetPath $target) => InvalidTargetPath::fromUnvalidatedTargetPath($target)
                )->toArray()
            )
        );
    }
}