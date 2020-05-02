#!/usr/bin/php
<?php

# bootstrap cli
use DotFiler\TextOutput\Ansi;
use DotFiler\Targets\ExistingTarget;
use DotFiler\TextOutput\AnsiCodes;
use DotFiler\Targets\ExistingTargets;
use DotFiler\Targets\NonExistingTarget;
use DotFiler\Targets\NonExistingTargets;
use DotFiler\Targets\ValidateTargets;
use DotFiler\Targets\TargetProcessor;
use DotFiler\Targets\ConfiguredTargets;
use DotFiler\Targets\UnprocessedTargets;
use DotFiler\Targets\FindUnprocessedTargets;

require 'cli-bootstrap.php';

# parse arguments
[$targetFile, $backupRepo] = input(__FILE__, "<target-file> <backup-repo>");

$configured = ConfiguredTargets::fromFile($targetFile);
$valid = ExistingTargets::fromConfigured($configured);
$invalid = NonExistingTargets::fromConfigured($configured);
$unprocessed = UnprocessedTargets::fromExisting($valid);

//$targets = new TargetProcessor($backupRepo);
dd($unprocessed);


die('end');

$bypassConfirmations = (bool)getopt('f');



$unvalidatedTargets = ConfiguredTargets::fromFile($targetFile);

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