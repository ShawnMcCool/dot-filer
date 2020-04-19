<?php namespace Tests\DotFiler;

trait TestValues
{
    protected string $targetFile = 'tests/stubs/example.target';
    
    public function __construct()
    {
        parent::__construct();
    }
}