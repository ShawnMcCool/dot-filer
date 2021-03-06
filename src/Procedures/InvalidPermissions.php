<?php namespace DotFiler\Procedures;

use DotFiler\DotFilerException;

final class InvalidPermissions extends DotFilerException
{
    public static function pathMustBeWritable(string $path)
    {
        return new static("Path '{$path}' must be writable.");
    }
}