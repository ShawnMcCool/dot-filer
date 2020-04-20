<?php namespace DotFiler\Targets;

final class InvalidTargets
{
    private InvalidTargetCollection $targetPaths;

    private function __construct(InvalidTargetCollection $paths)
    {
        $this->targetPaths = $paths;
    }

    public function paths(): InvalidTargetCollection
    {
        return $this->targetPaths;
    }

    public static function fromUnvalidatedTargets(TargetValidation $targetValidation): self
    {
        return new static(
            $targetValidation->invalidTargets()
        );
    }
}