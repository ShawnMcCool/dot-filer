#!/usr/bin/php
<?php

# bootstrap cli
use DotFiler\TextOutput\Ansi;
use DotFiler\TextOutput\AnsiCodes;
use DotFiler\Targets\ValidTargetPath;
use DotFiler\Targets\TargetValidation;
use DotFiler\Targets\InvalidTargetPath;
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
        function (ValidTargetPath $path) {
            echo Ansi::green($path->toString() . "\n");
        }
    );

    echo Ansi::format("\n## Invalid Targets\n", AnsiCodes::$underline, AnsiCodes::$red);

    $invalidTargets->each(
        function (InvalidTargetPath $path) {
            echo Ansi::red($path->toString() . "\n");
        }
    );

    echo Ansi::plain("\nYou must resolve the invalid targets by either removing them from the target-file or ensuring that the file or directory exists.\n\n");
    exit;
}