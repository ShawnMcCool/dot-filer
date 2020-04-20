<?php namespace DotFiler\Targets;

final class UnvalidatedTargets
{
    private UnvalidatedTargetCollection $targets;

    private function __construct(UnvalidatedTargetCollection $targets)
    {
        $this->targets = $targets;
    }

    public function paths(): UnvalidatedTargetCollection
    {
        return $this->targets;
    }

    public static function fromFile(string $filepath): self
    {
        /** @var UnvalidatedTargetCollection $targets */
        $targets = collect(
            file($filepath)
        )->map(
            fn($target) => UnvalidatedTarget::fromString(
                trim($target)
            )
        );

        return new static(
            UnvalidatedTargetCollection::of($targets->toArray())
        );
    }
}