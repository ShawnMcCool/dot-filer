<?php namespace DotFiler\Targets;

use DotFiler\DotFilerException;

final class PathNotFound extends DotFilerException
{
    public static function targetsFile(string $filepath): self
    {
        return new static("Targets file not found: '{$filepath}'");
    }

    public static function repoDirectory(string $repoPath): self
    {
        return new static("Repo directory not found: '{$repoPath}'");
    }

    public static function pathNotFoundInTargetFile(string $path)
    {
        return new static("The path '{$path}' was not found in the target file.");
    }
}