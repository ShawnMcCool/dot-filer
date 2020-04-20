<?php

use DotFiler\Collections\Collection;

function d(...$dumps): void
{
    echo '<pre>';
    var_dump(...$dumps);
    echo '</pre>';
}

function dd(...$dumps): void
{
    d(...$dumps);
    exit;
}

function collect($items): Collection
{
    if (is_null($items)) {
        return Collection::empty();
    }

    if ( ! is_array($items)) {
        $items = [$items];
    }
    return new Collection($items);
}