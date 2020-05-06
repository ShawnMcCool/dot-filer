<?php namespace DotFiler\Commands;

use GetOpt\GetOpt;
use GetOpt\Command;
use GetOpt\Operand;
use DotFiler\DotFiler;
use DotFiler\RepoPath;
use DotFiler\TargetFile;
use DotFiler\Recommendations;
use DotFiler\TextFormatting\Ansi;
use DotFiler\Collections\Collection;
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

        if ($dotFiler->configuredTargets()->all()->count() == 0) {
            echo Ansi::red("Target file '{$targetFile->toString()}' contains no backup targets.\n");
            exit;
        }

        # Status Overview
        $statusRows = $this->stylize($dotFiler->allTargetStatuses());

        echo TextTable::make()
                      ->withTitle('Targets')
                      ->withHeaders('Path', 'Backup Status', 'Restore Status')
                      ->withRows(
                          $statusRows->toArray()
                      )->toString();

        # Recommendations
        $recommendations = Recommendations::fromTargetStatuses($dotFiler->allTargetStatuses())
                                          ->map(
                                              fn($target, $recommendation) => [$target => 
                                                  [$target, Ansi::red($recommendation)]
                                              ]
                                          )->toCollection()
                                          ->toArray();

        echo TextTable::make()
                      ->withTitle('Recommendations')
                      ->withHeaders('Path', 'Recommendation')
                      ->withRows(
                          $recommendations
                      )->toString();
    }

    private function stylize(Collection $allTargetStatuses): Collection
    {
        return
            $allTargetStatuses->map(
                function ($cols) {
                    [$path, $backup, $restore] = $cols;

                    if ($backup == 'managed' && $restore == 'managed') {
                        $path = Ansi::blue($path);
                    }

                    #Backup
                    if ($backup == 'managed') {
                        $backup = Ansi::blue($backup);
                    }
                    if ($backup == 'ready for management') {
                        $backup = Ansi::green($backup);
                    }
                    if ($backup == 'not ready for management') {
                        $backup = Ansi::red($backup);
                    }
                    if ($backup == 'target path not found') {
                        $backup = Ansi::red($backup);
                    }
                    if ($backup == 'target path is found but is in an unidentifiable state') {
                        $backup = Ansi::red($backup);
                    }

                    # Restore
                    if ($restore == 'unmanaged') {
                        $restore = Ansi::red($restore);
                        $path = Ansi::red($path);
                    }
                    if ($restore == 'can be restored') {
                        $restore = Ansi::green($restore);
                    }
                    if ($restore == 'managed') {
                        $restore = Ansi::blue($restore);
                    }

                    return [$path, $backup, $restore];
                });
    }
}