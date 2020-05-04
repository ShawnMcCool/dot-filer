<?php namespace Tests\DotFiler\GenerateTestEnvironment;

use Tests\DotFiler\TestValues;

final class GenerateTestEnvironment
{
    use TestValues;

    private function __construct()
    {
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

    public function clearPath()
    {
        if (is_dir($this->targetPath)) {
            $this->recursiveDelete($this->targetPath);
        }
    }
    
    private function recursiveCopy($source, $dest)
    {
        mkdir($dest);

        collect(
            scandir($source)
        )->filter(
            fn($path) => ! in_array($path, ['.', '..'])
        )->each(
            function ($path) use ($source, $dest) {
                $sourcePath = "{$source}/{$path}";
                $destPath = "{$dest}/{$path}";
                
                if (is_dir($sourcePath)) {
                    $this->recursiveCopy($sourcePath, $destPath);
                } else {
                    copy($sourcePath, $destPath);
                }
            }
        );
        
    }

    private function buildEnvironment()
    {
        if (
            is_dir($this->templatePath) &&
            ! is_dir($this->targetPath)
        ) {
            $this->recursiveCopy(realpath($this->templatePath), $this->targetPath);
        }
        
        # provide a symlink as a target to test UnprocessedTarget
        symlink(
            $this->basePath . 'tests/test-environment/example-file.txt',
            $this->basePath . 'tests/test-environment/symlink'
        );
    }

    private function validateTargetPath()
    {
        // if we allow this then we could accidentally shred your hard drive... whoops!
        if (stristr($this->targetPath, '..')) {
            throw TestEnvironmentPathIsInvalid::canNotMoveUpARelativePath($this->targetPath);
        }

        if (empty($this->targetPath)) {
            throw TestEnvironmentPathIsInvalid::pathMayNotBeEmpty();
        }
    }

    public static function generate()
    {
        $generator = new static();
        $generator->validateTargetPath();
        $generator->clearPath();
        $generator->buildEnvironment();
    }
}