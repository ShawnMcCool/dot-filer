<?php namespace Tests\DotFiler\GenerateTestEnvironment;

use Exception;

final class TestEnvironmentPathIsInvalid extends Exception
{
    public static function canNotMoveUpARelativePath(string $path)
    {
        return new static("For your safety it is illegal to include relative path symbols '..' that would allow you to refer to a parent directory.");
    }

    public static function pathMayNotBeEmpty()
    {
        return new static("The target test-environment path may not be empty.");
    }
}