#!/usr/bin/php
<?php

# bootstrap cli
require 'cli-bootstrap.php';

# parse arguments
list($targetFile, $backupRepo) = input(__FILE__, "<target-file> <backup-repo>");

$targets = Targets::fromFile($targetFile);