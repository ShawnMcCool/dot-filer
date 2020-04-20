<?php namespace DotFiler\Targets;

use DotFiler\Collections\TypedCollection;

final class ValidTargetCollection extends TypedCollection
{
    protected string $collectionTypeClass = ValidTargetPath::class;
}