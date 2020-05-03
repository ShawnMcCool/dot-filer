<?php namespace DotFiler\Procedures;

use DotFiler\RepoPath;
use DotFiler\Targets\TargetPath;

interface Result
{
    function target(): TargetPath;
    function repoPath(): RepoPath;
    function message(): string;
}