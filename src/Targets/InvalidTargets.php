<?php namespace DotFiler\Targets;

final class InvalidTargets
{
    private InvalidTargetCollection $targetPaths;

    private function __construct(InvalidTargetCollection $paths)
    {
        $this->targetPaths = $paths;
    }

    public function allPaths(): InvalidTargetCollection
    {
        return $this->targetPaths;
    }

    public static function fromUnvalidatedTargets(UnvalidatedTargets $targets)
    {
        return new static(
            InvalidTargetCollection::of(
                $targets->paths()->map(
                    fn(UnvalidatedTargetPath $target) => InvalidTargetPath::fromUnvalidatedTargetPath($target)
                )->toArray()
            )
        );
    }
}