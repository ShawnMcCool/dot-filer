#!/usr/bin/php
<?php

# bootstrap cli
use DotFiler\Targets\Result;
use DotFiler\TextOutput\Ansi;
use DotFiler\Targets\RepoPath;
use DotFiler\Targets\TargetFile;
use DotFiler\TextOutput\AnsiCodes;
use DotFiler\Targets\ExistingTarget;
use DotFiler\Targets\ExistingTargets;
use DotFiler\Targets\ValidateTargets;
use DotFiler\Targets\TargetProcessor;
use DotFiler\Targets\NonExistingTarget;
use DotFiler\Targets\ConfiguredTargets;
use DotFiler\Targets\NonExistingTargets;
use DotFiler\Targets\UnprocessedTargets;
use Tests\DotFiler\GenerateTestEnvironment\GenerateTestEnvironment;

require 'cli-bootstrap.php';

# parse arguments
[$targetFileString, $repoPathString] = input(__FILE__, "<target-file> <repo-path>");

GenerateTestEnvironment::generate();

$targetFile = TargetFile::fromString($targetFileString);
$repoPath = RepoPath::fromString($repoPathString);

$configured = ConfiguredTargets::fromTargetFile($targetFile);
$valid = ExistingTargets::fromConfigured($configured);
$invalid = NonExistingTargets::fromConfigured($configured);
$unprocessed = UnprocessedTargets::fromExisting($valid, $repoPath);

$targets = new TargetProcessor($repoPath);
$results = $targets->process($unprocessed);

$resultMessages =
    $results->all()
            ->map(
                fn(Result $result) => $result->message()
            )->implode("\n");

die($resultMessages);
die('end');

$bypassConfirmations = (bool)getopt('f');


$unvalidatedTargets = ConfiguredTargets::fromTargetFile($targetFile);

$validTargets = ExistingTargets::fromConfigured($unvalidatedTargets);
$invalidTargets = NonExistingTargets::fromConfigured($unvalidatedTargets);

if ($invalidTargets->count() > 0) {
    echo Ansi::plain("\n# Target Summary\n\n");

    echo Ansi::format("## Valid Targets\n", AnsiCodes::$underline, AnsiCodes::$green);

    $validTargets->each(
        function (ExistingTarget $path) {
            echo Ansi::green($path->path() . "\n");
        }
    );

    echo Ansi::format("\n## Invalid Targets\n", AnsiCodes::$underline, AnsiCodes::$red);

    $invalidTargets->each(
        function (NonExistingTarget $path) {
            echo Ansi::red($path->path() . "\n");
        }
    );

    echo Ansi::plain("\nYou have invalid targets in your target-file. You should probably remove them from the target-file or ensuring that the file or directory exists.\n");
    ! $bypassConfirmations && confirm_prompt("Continue while skipping invalid targets?");
}

if ($validTargets->count() == 0) {
    echo Ansi::green('There are no valid targets to process. exiting...');
}
dd($validTargets->unprocessed());
$unprocessed = $validTargets->unprocessed();

dd($unprocessed);