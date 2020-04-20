<?php namespace DotFiler\Targets;

final class UnvalidatedTargets
{
    private UnvalidatedTargetCollection $targetPaths;

    private function __construct(UnvalidatedTargetCollection $paths)
    {
        $this->targetPaths = $paths;
    }

    public function paths(): UnvalidatedTargetCollection
    {
        return $this->targetPaths;
    }

    public static function fromFile(string $filepath): self
    {
        /** @var UnvalidatedTargetCollection $targets */
        $targets = UnvalidatedTargetCollection::of(
            file($filepath)
        )->map(
            fn($target) => UnvalidatedTargetPath::fromString(
                trim($target)
            )
        );

        return new static($targets);
    }
}