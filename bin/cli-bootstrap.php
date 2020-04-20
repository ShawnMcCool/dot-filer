<?php

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * This both parses the input as well as manages usage explanation.
 *
 * input(__FILE__, "<firstName> <lastName>")
 *
 * @param $script typically just __FILE__
 * @param $parameters a string with <> tags named for documentation.
 * @return array
 */
function input($script, $parameters)
{
    $argumentCount = (int)substr_count($parameters, '<');
    $arguments = $_SERVER['argv'];

    if (count($arguments) < $argumentCount + 1) {
        $path = str_replace(realpath('.') . '/', '', $script);
        echo "Usage: php {$path} {$parameters}\n";
        exit(0);
    }

    return array_slice($arguments, 1, $argumentCount);
}

function confirm_prompt(string $question = "Are you sure you want to do this?  Type 'yes' to continue: ")
{
    echo $question;
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    if (trim($line) != 'yes') {
        echo "aborted by user...\n\n";
        exit;
    }
}