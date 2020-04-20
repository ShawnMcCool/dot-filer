<?php namespace Tests\DotFiler;

trait TestValues
{
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
    protected string $targetFile = 'tests/test-environment/example.target';
    
    public function __construct()
    {
        parent::__construct();
    }
}