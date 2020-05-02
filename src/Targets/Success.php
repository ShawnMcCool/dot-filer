<?php namespace DotFiler\Targets;

final class Success implements Result
{
    private TargetPath $target;
    private RepoPath $repoPath;
    private string $message;

    public function __construct(TargetPath $target, RepoPath $repoPath, string $message)
    {
        $this->target = $target;
        $this->repoPath = $repoPath;
        $this->message = $message;
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
        return $this->message;
    }

    public static function achieved(TargetPath $target, RepoPath $repoPath): self
    {
        $repoTargetPath = $repoPath->repoTargetPath($target);

        return new static(
            $target,
            $repoPath,
            "Successfully moved '{$target->path()}' to '{$repoTargetPath}' and created a symlink to reference its new location."
        );
    }
}