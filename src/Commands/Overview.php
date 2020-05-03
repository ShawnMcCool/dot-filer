<?php namespace DotFiler\Commands;

use GetOpt\GetOpt;
use GetOpt\Command;
use GetOpt\Operand;
use DotFiler\DotFiler;
use DotFiler\Targets\RepoPath;
use DotFiler\Targets\TargetFile;
use DotFiler\TextFormatting\TextTable;

final class Overview extends Command
{
    public function __construct()
    {
        parent::__construct('overview', [$this, 'handle']);

        $this->addOperands(
            [
                Operand::create('target-file', Operand::REQUIRED),
                Operand::create('repo-path', Operand::REQUIRED),
            ]
        )->setShortDescription(
            'Show the management status of targets.'
        )->setDescription(
            'Show each target and their current management status.'
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
        
        echo "\n" . TextTable::make()
                             ->withTitle('Targets')
                             ->withHeaders('Path', 'Backup Status', 'Restore Status')
                             ->withRows(
                                 $dotFiler->allTargetStatuses()
                             )->toString();
    }
}