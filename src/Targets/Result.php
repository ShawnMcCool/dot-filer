<?php namespace DotFiler\Targets;

interface Result
{
    function target(): TargetPath;
    function repoPath(): RepoPath;
    function message(): string;
}