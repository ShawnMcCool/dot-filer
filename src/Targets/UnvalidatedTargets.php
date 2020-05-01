<?php namespace DotFiler\Targets;

use DotFiler\Collections\Collection;

final class UnvalidatedTargets
{
    private Collection $targets;

    private function __construct(Collection $targets)
    {
        $this->targets = $targets;
    }

    public function paths(): Collection
    {
        return $this->targets;
    }

    public static function fromFile(string $filepath): self
    {
        $unvalidatedTargets = collect(
            file($filepath)
        )->map(
            fn($target) => UnvalidatedTarget::fromString(
                trim($target)
            )
        );

        return new static($unvalidatedTargets);
    }
}