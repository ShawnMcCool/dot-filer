<?php namespace DotFiler\Targets;

use DotFiler\Collections\TypedCollection;

final class InvalidTargetCollection extends TypedCollection
{
    protected string $collectionTypeClass = InvalidTargetPath::class;
}