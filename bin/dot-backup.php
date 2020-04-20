#!/usr/bin/php
<?php

# bootstrap cli
use DotFiler\Targets;

require 'cli-bootstrap.php';

# parse arguments
[$targetFile, $backupRepo] = input(__FILE__, "<target-file> <backup-repo>");

$targets = Targets::fromFile($targetFile);