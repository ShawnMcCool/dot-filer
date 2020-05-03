<?php namespace DotFiler\Targets;

final class RestoreProcedure
{
    private RepoPath $repoPath;

    public function __construct(RepoPath $repoPath)
    {
        $this->repoPath = $repoPath;
    }

    public function run(RestorableTargets $targets): Results
    {
        $results = $targets->all()->map(
            fn(RestorableTarget $target): Result => $this->restoreTarget($target)
        );

        return new Results($results);
    }

    private function restoreTarget(RestorableTarget $target): Result
    {
        $repoTargetPath = $this->repoPath->repoTargetPath($target);

        // 1. remove the path from the host machine
        if ( ! $this->pathIsWritable($target->path())) {
            return Error::targetPathMustBeWritable($target, $this->repoPath);
        }
        
        if (is_dir($target->path())) {
            $this->recursiveDelete($target->path());
        } elseif(
            is_file($target->path()) ||
            is_link($target->path())
        ) {
            unlink($target->path());
        }
        
        if (file_exists($target->path())) {
            return Error::couldNotDestroyExistingPath($target, $this->repoPath);
        }
        
        // 2. create (if necessary) the path that contains our target path
        $this->createParentDirectory($target->path());
        
        // 3. symlink it to the repo target path
        if ( ! symlink($repoTargetPath, $target->path())) {
            return Error::couldNotCreateLink($target, $this->repoPath);
        }
        
        return Success::restoreComplete($target, $this->repoPath);
    }

    private function recursiveDelete(string $path)
    {
        if ( ! is_dir($path)) {
            return;
        }

        collect(
            scandir($path)
        )->filter(
            fn($path) => ! in_array($path, ['.', '..'])
        )->each(
            function ($subPath) use ($path) {
                $subPath = "{$path}/{$subPath}";

                if (is_dir($subPath)) {
                    $this->recursiveDelete($subPath);
                } else {
                    unlink($subPath);
                }
            }
        );

        rmdir($path);
    }

    private function pathIsWritable(string $path): bool
    {
        if (is_dir($path) || is_file($path)) {
            return is_writable($path);
        }

        $parent = $this->nearestExistingParentDirectory($path);
        
        return (bool) $parent;
    }

    private function nearestExistingParentDirectory(string $path): ?string
    {
        foreach (range(1, substr_count($path, '/')) as $i) {
            $dir = dirname($path, $i);
            if (is_dir($dir)) {
                return $dir;
            }
        }
        return null;
    }

    private function createParentDirectory(string $path)
    {
        $parent = dirname($path, 1);
        
        if (is_dir($parent)) {
            return;
        }
        
        mkdir($parent, 0777, true);
    }
}