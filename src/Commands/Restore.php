<?php namespace DotFiler\Commands;

use GetOpt\GetOpt;
use GetOpt\Command;
use GetOpt\Operand;
use DotFiler\DotFiler;
use DotFiler\RepoPath;
use DotFiler\TargetFile;
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

        $results = $dotFiler->processRestore();

        echo TextTable::make()
                      ->withTitle('Restore Results')
                      ->withHeaders('Path', 'Message')
                      ->withRows(
                          $results->toArray()
                      )->toString();
    }
}