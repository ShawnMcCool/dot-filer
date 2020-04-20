<?php namespace DotFiler\Targets;

final class ValidateTargets
{
    private UnvalidatedTargets $targets;

    private function __construct(UnvalidatedTargets $targets)
    {
        $this->targets = $targets;
    }

    public function validTargets(): ValidTargets
    {
        return ValidTargets::fromValidator(
            $this->targets->paths()->filter(
                fn(UnvalidatedTarget $unvalidated) => $this->isValidPath($unvalidated)
            )->map(
                fn(UnvalidatedTarget $unvalidated) => ValidTarget::fromUnvalidatedTarget($unvalidated)
            )
        );
    }

    public function invalidTargets(): InvalidTargets
    {
        return InvalidTargets::fromValidator(
            $this->targets->paths()->filter(
                fn(UnvalidatedTarget $unvalidated) => ! $this->isValidPath($unvalidated)
            )->map(
                fn(UnvalidatedTarget $unvalidated) => InvalidTarget::fromUnvalidatedTarget($unvalidated)
            )
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
    public static function for(UnvalidatedTargets $targets)
    {
        return new static($targets);
    }
}