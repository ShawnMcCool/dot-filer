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

    public function allMessages(): string
    {
        return
            $this->results
                ->map(
                    fn(Result $result) => $result->message()
                )->implode("\n");
    }

    public function successMessages(): string
    {
        return
            $this->results
                ->filter(
                    fn(Result $result) => $result instanceof Error
                )
                ->map(
                    fn(Result $result) => $result->message()
                )->implode("\n");
    }

    public function errorMessages(): string
    {
        return
            $this->results
                ->filter(
                    fn(Result $result) => $result instanceof Error
                )
                ->map(
                    fn(Result $result) => $result->message()
                )->implode("\n");
    }
}