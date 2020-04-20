<?php namespace DotFiler\Targets;

use Exception;

final class TargetNotValid extends Exception
{
    public static function targetDoesNotExist(string $target)
    {
        return new static("Target '{$target}' does not exist.");
    }
}