<?php namespace Tests\DotFiler;

trait TestValues
{
    protected string $targetPath = 'test-environment';
    protected string $targetFile = 'tests/test-environment/example.target';
    
    public function __construct()
    {
        parent::__construct();
    }
}