<?php namespace DotFiler\Commands;

use GetOpt\GetOpt;
use GetOpt\Command;
use GetOpt\Operand;
use DotFiler\DotFiler;
use DotFiler\RepoPath;
use DotFiler\TargetFile;
use DotFiler\TextFormatting\TextTable;

final class Backup extends Command
{
    public function __construct()
    {
        parent::__construct('backup', [$this, 'handle']);

        $this->addOperands(
            [
                Operand::create('target-file', Operand::REQUIRED),
                Operand::create('repo-path', Operand::REQUIRED),
            ]
        )->setShortDescription(
            'Backup targets.'
        )->setDescription(
            'Process all targets in the target-file such that they\'re stored in the repo-path and referenced by a symlink'
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
        
        $results = $dotFiler->processBackup();

        echo TextTable::make()
                      ->withTitle('Backup Results')
                      ->withHeaders('Path', 'Message')
                      ->withRows(
                          $results->toArray()
                      )->toString();
    }
}