<?php namespace DotFiler\Targets;

final class FindUnprocessedTargets
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
                fn(UnvalidatedTarget $unvalidated) => $this->isValidPath($unvalidated)
            )->map(
                fn(UnvalidatedTarget $unvalidated) => ValidTarget::fromUnvalidatedTarget($unvalidated)
            )->toArray()
        );
    }

    public function invalidTargets(): InvalidTargetCollection
    {
        return InvalidTargetCollection::of(
            $this->targets->paths()->filter(
                fn(UnvalidatedTarget $unvalidated) => ! $this->isValidPath($unvalidated)
            )->map(
                fn(UnvalidatedTarget $unvalidated) => InvalidTarget::fromUnvalidatedTarget($unvalidated)
            )->toArray()
        );
    }

    private function isValidPath(UnvalidatedTarget $path): bool
    {
        return file_exists($path->toString());
    }

    /**
     * Validate these unvalidated targets.
     *
     * @param UnvalidatedTargets $targets
     * @return static
     */
    public static function for(ValidTarget $targets)
    {
        return new static($targets);
    }
}