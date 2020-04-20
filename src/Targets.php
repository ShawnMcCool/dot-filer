<?php namespace DotFiler;

use DotFiler\Collections\Collection;

final class Targets
{
    private Collection $paths;

    public static function fromFile(string $filepath)
    {
        $targets = collect(
            file($filepath)
        )->map(
            fn($target) => trim($target)
        );
        
        $targets->each(
            function($target) {
                if ( ! file_exists($target)) {
                    throw TargetNotValid::targetDoesNotExist($target);
                }
            }
        );
            
        return new Targets($targets);
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