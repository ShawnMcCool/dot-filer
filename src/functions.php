<?php

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