#!/usr/bin/php
<?php

# bootstrap cli
use DotFiler\Targets\UnvalidatedTargets;

require 'cli-bootstrap.php';

# parse arguments
[$targetFile, $backupRepo] = input(__FILE__, "<target-file> <backup-repo>");

$targets = UnvalidatedTargets::fromFile($targetFile);

dd($targets);