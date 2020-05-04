<?php namespace Tests\DotFiler;

use DotFiler\RepoPath;
use DotFiler\DotFiler;
use DotFiler\TargetFile;
use DotFiler\Procedures\BackupProcedure;

trait TestValues
{
    /**
     * This path is prepended to relative paths to make valid absolute paths.
     */
    protected string $basePath = '/vagrant/';
    /**
     * This is the path that the new test environment will be installed into.
     */
    protected string $targetPath = 'tests/test-environment';
    /**
     * This is the template path that will be used to create new instances of the test environment.
     */
    protected string $templatePath = 'tests/GenerateTestEnvironment/templates';
    /**
     * This is the location of the target file within the test environment.
     */
    protected TargetFile $targetFile;
    /**
     * This is the location that the targets will be moved into.
     */
    protected RepoPath $repoPath;
    /**
     * Dotfiler instance
     */
    protected DotFiler $dotFiler;
    protected BackupProcedure $backupProcedure;
    
    public function __construct()
    {
        parent::__construct();
    }

    public function initializeTestValues()
    {
        $this->targetFile = TargetFile::fromString('tests/test-environment/example.target');
        $this->repoPath = RepoPath::fromString('tests/test-environment/repo');
        $this->dotFiler = new DotFiler($this->targetFile, $this->repoPath);
        $this->backupProcedure = new BackupProcedure($this->repoPath);
    }
}