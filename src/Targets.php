<?php namespace DotFiler;

use DotFiler\Collections\Collection;

final class Targets
{
    private Collection $paths;

    public static function fromFile(string $filepath)
    {
        return new Targets(
            Collection::of(
                file($filepath)
            )
        );
    }

    private function __construct(Collection $paths)
    {
        $this->paths = $paths;
    }

    public function allPaths(): Collection
    {
        return $this->paths;
    }
}