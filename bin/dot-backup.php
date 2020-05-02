#!/usr/bin/php
<?php

# bootstrap cli
use DotFiler\Targets\RepoPath;
use DotFiler\Targets\TargetFile;
use DotFiler\Targets\ExistingTargets;
use DotFiler\Targets\ValidateTargets;
use DotFiler\Targets\TargetProcessor;
use DotFiler\Targets\ConfiguredTargets;
use DotFiler\Targets\NonExistingTargets;
use DotFiler\Targets\UnprocessedTargets;
use Tests\DotFiler\GenerateTestEnvironment\GenerateTestEnvironment;

require 'cli-bootstrap.php';

# command line arguments
[$targetFileString, $repoPathString] = input(__FILE__, "<target-file> <repo-path>");

// construct and validate the target file (list of paths to back up) 
// and the repo path (the place to where you want to back up)
$targetFile = TargetFile::fromString($targetFileString);
$repoPath = RepoPath::fromString($repoPathString);

// collection of targets from the target file
$configured = ConfiguredTargets::fromTargetFile($targetFile);

// collections of both valid (files exist) and invalid (do not exist)
// from the collection of configured targets
$valid = ExistingTargets::fromConfigured($configured);
$invalid = NonExistingTargets::fromConfigured($configured);

// these targets are a subset of all valid targets. they are the targets
// that must be processed. the other valid targets have already been backed up
$unprocessed = UnprocessedTargets::fromExisting($valid, $repoPath);

// create the actual processor (procedure)
$processor = new TargetProcessor($repoPath);

// process the unprocessed targets, retrieving a collection of results
$results = $processor->process($unprocessed);

echo $results->errorMessages();