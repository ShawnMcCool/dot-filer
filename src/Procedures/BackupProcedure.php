<?php namespace DotFiler\Procedures;

use DotFiler\RepoPath;
use DotFiler\Targets\BackupableTarget;
use DotFiler\Targets\BackupableTargets;

final class BackupProcedure
{
    private RepoPath $repoPath;

    public function __construct(RepoPath $repoPath)
    {
        $this->repoPath = $repoPath;
    }

    public function run(BackupableTargets $targets): Results
    {
        $results = $targets->all()->map(
            fn(BackupableTarget $target): Result => $this->backupTarget($target)
        );

        return new Results($results);
    }

    private function backupTarget(BackupableTarget $target): Result
    {
        $repoTargetPath = $this->repoPath->repoTargetPath($target);

        // 1. create the path in the repo that the target will be moved into.
        if (
            ! is_dir(dirname($repoTargetPath)) &&
            ! mkdir(dirname($repoTargetPath), 0777, true)
        ) {
            return Error::couldNotCreateRepoDirectory($target, $this->repoPath, dirname($repoTargetPath));
        }

        // 2. move target to repo path
        if ( ! @rename($target->path(), $repoTargetPath)) {
            return Error::couldNotMoveTarget($target, $this->repoPath);
        }

        // 3. symlink target to repo path
        if ( ! symlink($repoTargetPath, $target->path())) {
            return Error::couldNotCreateLink($target, $this->repoPath);
        }

        // 4. verify symlink references repo target path
        if (
            readlink($target->path()) !== $repoTargetPath
        ) {
            return Error::couldNotVerifySymlinkValidity(
                $target, $this->repoPath, readlink($target->path())
            );
        }

        return Success::backupComplete($target, $this->repoPath);
    }
}