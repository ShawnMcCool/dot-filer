<?php namespace DotFiler\Targets;

use DotFiler\Collections\Collection;

final class Results
{
    private Collection $results;

    public function __construct(Collection $results)
    {
        $this->results = $results;
    }

    public function all(): Collection
    {
        return $this->results;
    }
}