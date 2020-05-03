<?php namespace DotFiler\Procedures;

use DotFiler\RepoPath;
use DotFiler\Targets\TargetPath;
use DotFiler\Targets\RestorableTarget;
use DotFiler\Targets\UnprocessedTarget;

final class Error implements Result
{
    private string $errorMessage;
    private TargetPath $target;
    private RepoPath $repoPath;

    private function __construct(TargetPath $target, RepoPath $repoPath, string $errorMessage)
    {
        $this->errorMessage = $errorMessage;
        $this->target = $target;
        $this->repoPath = $repoPath;
    }

    public function target(): TargetPath
    {
        return $this->target;
    }

    public function repoPath(): RepoPath
    {
        return $this->repoPath;
    }

    
    public function message(): string
    {
        return $this->errorMessage;
    }

    public static function repoTargetPathAlreadyExists(UnprocessedTarget $target, RepoPath $repoPath): self
    {
        $repoTargetPath = $repoPath->repoTargetPath($target);

        return new static(
            $target,
            $repoPath,
            "Can not backup '{$target->path()}' because the path '{$repoTargetPath}' already exists."
        );
    }

    public static function targetPathMustBeWritable(TargetPath $target, RepoPath $repoPath): self
    {
        return new static(
            $target,
            $repoPath,
            "Target path must be writable to create the symlink back to the repo."
        );
    }

    public static function couldNotMoveTarget(TargetPath $target, RepoPath $repoPath): self
    {
        $repoTargetPath = $repoPath->repoTargetPath($target);

        return new static(
            $target,
            $repoPath,
            "Could not move path from '{$target->path()}' to '{$repoTargetPath}'."
        );
    }

    public static function couldNotCreateLink(TargetPath $target, RepoPath $repoPath): self
    {
        $repoTargetPath = $repoPath->repoTargetPath($target);

        return new static(
            $target,
            $repoPath,
            "Could not create link to '{$repoTargetPath}' from '{$target->path()}'."
        );
    }

    public static function couldNotVerifySymlinkValidity(TargetPath $target, RepoPath $repoPath, string $linkTarget): self
    {
        $repoTargetPath = $repoPath->repoTargetPath($target);

        return new static(
            $target,
            $repoPath,
            "Symlink at '{$target->path()}' points to '{$linkTarget}' but we expected it to point to '{$repoTargetPath}'."
        );
    }

    public static function couldNotCreateRepoDirectory(TargetPath $target, RepoPath $repoPath, string $repoTargetDirName)
    {
        $repoTargetPath = $repoPath->repoTargetPath($target);

        return new static(
            $target,
            $repoPath,
            "We were unable to create path '{$repoTargetDirName}' to hold contents of '{$repoTargetPath}'."
        );
    }

    public static function couldNotDestroyExistingPath(RestorableTarget $target, RepoPath $repoPath)
    {
        $repoTargetPath = $repoPath->repoTargetPath($target);

        return new static(
            $target,
            $repoPath,
            "We were unable to destroy the target path '{$target->path()}' in order to create a link back to the repo."
        );
    }
}