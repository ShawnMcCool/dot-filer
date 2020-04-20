<?php namespace Tests\DotFiler\GenerateTestEnvironment;

use Tests\DotFiler\TestValues;

final class GenerateTestEnvironment
{
    use TestValues;

    private function __construct()
    {
    }

    private function rmdir(string $path)
    {
        $dir = opendir($path);

        while (false !== ($file = readdir($dir))) {
            if ( ! in_array(['.', '..'], $file)) {
                $full = "{$path}/{$file}";
                if (is_dir($full)) {
                    rmdir($full);
                } else {
                    unlink($full);
                }
            }
        }

        closedir($dir);
        rmdir($path);
    }

    private function clearPath()
    {
        if (is_dir($this->targetPath)) {
            $this->rmdir($this->targetPath);
        }
    }
    
    private function buildEnvironment() {
        
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
        $generator->buildEnvironment();;
    }
}