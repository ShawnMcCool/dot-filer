<?php namespace DotFiler\Targets;

final class ValidTargets
{
    private ValidTargetCollection $targetPaths;

    private function __construct(ValidTargetCollection $paths)
    {
        $this->targetPaths = $paths;
    }

    public function paths(): ValidTargetCollection
    {
        return $this->targetPaths;
    }

    public static function fromUnvalidatedTargets(TargetValidation $targetValidation): self
    {
        return new static(
            $targetValidation->validTargets()
        );
    }
}