<?php namespace DotFiler\Targets;

final class TargetProcessor
{
    private RepoPath $repoPath;

    public function __construct(RepoPath $repoPath)
    {
        $this->repoPath = $repoPath;
    }

    public function process(UnprocessedTargets $targets): Results
    {
        $results = $targets->all()->map(
            fn(UnprocessedTarget $target): Result => $this->processTarget($target)
        );

        return new Results($results);
    }

    private function processTarget(UnprocessedTarget $target): Result
    {
        $repoTargetPath = $this->repoPath->repoTargetPath($target);

        // 1. make sure that there's no file / directory at repo target path
        if ($this->repoPath->containsTarget($target)) {
            return Error::repoTargetPathAlreadyExists($target, $this->repoPath);
        }

        // 2. make sure that we have permissions to the target file (often outside of home)
        if ( ! is_writable($target->path())) {
            return Error::targetPathMustBeWritable($target, $this->repoPath);
        }

        // 3. create the path in the repo that the target will be moved into.
        if (
            ! is_dir(dirname($repoTargetPath)) &&
            ! mkdir(dirname($repoTargetPath), 0777, true)
        ) {
            return Error::couldNotCreateRepoDirectory($target, $this->repoPath, dirname($repoTargetPath));
        }

        // 4. move target to repo path
        if ( ! rename($target->path(), $repoTargetPath)) {
            return Error::couldNotMoveTarget($target, $this->repoPath);
        }

        // 5. symlink target to repo path
        if ( ! symlink($repoTargetPath, $target->path())) {
            return Error::couldNotCreateLink($target, $this->repoPath);
        }

        // 6. verify symlink references repo target path
        if (
            readlink($target->path()) !== $repoTargetPath
        ) {
            return Error::couldNotVerifySymlinkValidity(
                $target, $this->repoPath, readlink($target->path())
            );
        }

        return Success::achieved($target, $this->repoPath);
    }
}