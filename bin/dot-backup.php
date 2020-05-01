#!/usr/bin/php
<?php

# bootstrap cli
use DotFiler\TextOutput\Ansi;
use DotFiler\TextOutput\AnsiCodes;
use DotFiler\Targets\ValidTarget;
use DotFiler\Targets\ValidTargets;
use DotFiler\Targets\InvalidTargets;
use DotFiler\Targets\ValidateTargets;
use DotFiler\Targets\InvalidTarget;
use DotFiler\Targets\UnvalidatedTargets;
use DotFiler\Targets\FindUnprocessedTargets;

require 'cli-bootstrap.php';

$bypassConfirmations = (bool) getopt('f');

# parse arguments
[$targetFile, $backupRepo] = input(__FILE__, "<target-file> <backup-repo>");

$unvalidatedTargets = UnvalidatedTargets::fromFile($targetFile);

$validTargets = ValidTargets::fromUnvalidatedTargets($unvalidatedTargets);
$invalidTargets = InvalidTargets::fromUnvalidatedTargets($unvalidatedTargets);

if ($invalidTargets->count() > 0) {
    echo Ansi::plain("\n# Target Summary\n\n");

    echo Ansi::format("## Valid Targets\n", AnsiCodes::$underline, AnsiCodes::$green);

    $validTargets->each(
        function (ValidTarget $path) {
            echo Ansi::green($path->toString() . "\n");
        }
    );

    echo Ansi::format("\n## Invalid Targets\n", AnsiCodes::$underline, AnsiCodes::$red);

    $invalidTargets->each(
        function (InvalidTarget $path) {
            echo Ansi::red($path->toString() . "\n");
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