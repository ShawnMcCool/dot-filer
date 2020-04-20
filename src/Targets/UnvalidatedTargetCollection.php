<?php namespace DotFiler\Targets;

use DotFiler\Collections\TypedCollection;

final class UnvalidatedTargetCollection extends TypedCollection
{
    protected string $collectionTypeClass = UnvalidatedTargetPath::class;
}