<?php namespace DotFiler\Commands;

use GetOpt\GetOpt;
use GetOpt\Command;
use GetOpt\Operand;
use DotFiler\DotFiler;
use DotFiler\RepoPath;
use DotFiler\TargetFile;
use DotFiler\Procedures\Error;
use DotFiler\Procedures\Result;
use DotFiler\TextFormatting\Ansi;
use DotFiler\TextFormatting\TextTable;

final class Restore extends Command
{
    public function __construct()
    {
        parent::__construct('restore', [$this, 'handle']);

        $this->addOperands(
            [
                Operand::create('target-file', Operand::REQUIRED),
                Operand::create('repo-path', Operand::REQUIRED),
            ]
        )->setShortDescription(
            'Restore targets.'
        )->setDescription(
            'Restore targets by replacing existing host files with symlinks to the repo.'
        );
    }

    public function handle(GetOpt $cli)
    {
        $targetFile = TargetFile::fromString(
            $cli->getOperand('target-file')
        );

        $repoPath = RepoPath::fromString(
            $cli->getOperand('repo-path')
        );

        $dotFiler = new DotFiler($targetFile, $repoPath);

        if ($dotFiler->restorableTargets()->all()->count() == 0) {
            echo Ansi::red("Could not find targets in need of restoration in '{$targetFile->toString()}'.\n");
            exit;
        }

        $results = $dotFiler->processRestore();

        if ($results->all()->count() == 0) {
            echo Ansi::yellow("No targets were processed.");
            exit;
        }

        $styledResults =
            $results->all()
                    ->map(
                        fn(Result $result) => [
                            $result->target()->path(),
                            $result instanceof Error
                                ? (string) Ansi::red($result->message())
                                : (string) Ansi::green($result->message()),
                        ]
                    )->toArray();

        echo TextTable::make()
                      ->withTitle('Restore Results')
                      ->withHeaders('Path', 'Message')
                      ->withRows(
                          $styledResults
                      )->toString();
    }
}