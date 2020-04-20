<?php namespace DotFiler\Targets;

final class TargetValidation
{
    private UnvalidatedTargets $targets;

    private function __construct(UnvalidatedTargets $targets)
    {
        $this->targets = $targets;
    }

    public function validTargets(): ValidTargetCollection
    {
        return ValidTargetCollection::of(
            $this->targets->paths()->filter(
                fn(UnvalidatedTargetPath $unvalidated) => $this->isValidPath($unvalidated)
            )->map(
                fn(UnvalidatedTargetPath $unvalidated) => ValidTargetPath::fromUnvalidatedTargetPath($unvalidated)
            )->toArray()
        );
    }

    public function invalidTargets(): InvalidTargetCollection
    {
        return InvalidTargetCollection::of(
            $this->targets->paths()->filter(
                fn(UnvalidatedTargetPath $unvalidated) => ! $this->isValidPath($unvalidated)
            )->map(
                fn(UnvalidatedTargetPath $unvalidated) => InvalidTargetPath::fromUnvalidatedTargetPath($unvalidated)
            )->toArray()
        );
    }

    private function isValidPath(UnvalidatedTargetPath $path): bool
    {
        return file_exists($path->toString());
    }

    /**
     * Validate these unvalidated targets.
     *
     * @param UnvalidatedTargets $targets
     * @return static
     */
    public static function for(UnvalidatedTargets $targets)
    {
        return new static($targets);
    }
}