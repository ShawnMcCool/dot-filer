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

        if ($dotFiler->backupableTargets()->all()->count() == 0) {
            echo Ansi::red("There are currently no targets staged for backup in '{$targetFile->toString()}'.\n");
            exit;
        }

        $results = $dotFiler->processBackup();

        $styledResults =
            $results->all()
                    ->map(
                        fn(Result $result) => [
                            $result->target()->path(), (string)
                            $result instanceof Error
                                ? Ansi::red($result->message())
                                : Ansi::green($result->message())
                            ,
                        ]
                    )->toArray();
        
        echo TextTable::make()
                      ->withTitle('Backup Results')
                      ->withHeaders('Path', 'Message')
                      ->withRows(
                          $styledResults
                      )->toString();
        
        
    }
}