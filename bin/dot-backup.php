#!/usr/bin/php
<?php

# bootstrap cli
use DotFiler\TextOutput\Ansi;
use DotFiler\TextOutput\AnsiCodes;
use DotFiler\Targets\ValidTarget;
use DotFiler\Targets\TargetValidation;
use DotFiler\Targets\InvalidTarget;
use DotFiler\Targets\UnvalidatedTargets;

require 'cli-bootstrap.php';

# parse arguments
[$targetFile, $backupRepo] = input(__FILE__, "<target-file> <backup-repo>");

$targets = TargetValidation::for(
    UnvalidatedTargets::fromFile($targetFile)
);

$invalidTargets = $targets->invalidTargets();
$validTargets = $targets->validTargets();

if ($targets->invalidTargets()->count() > 0) {
    echo Ansi::plain("\n# Dot Filer Target Summary\n\n");

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
    echo Ansi::yellow("Would you like to skip these errors and process only the valid targets?\n");
    confirm_prompt();
}

