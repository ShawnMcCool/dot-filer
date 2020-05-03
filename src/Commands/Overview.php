<?php namespace DotFiler\Commands;

use GetOpt\GetOpt;
use GetOpt\Command;
use GetOpt\Operand;
use DotFiler\DotFiler;
use DotFiler\RepoPath;
use DotFiler\TargetFile;
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

        $statusRows = $this->stylize($dotFiler->allTargetStatuses());
        
        echo "\n" . TextTable::make()
                             ->withTitle('Targets')
                             ->withHeaders('Path', 'Backup Status', 'Restore Status')
                             ->withRows(
                                 $statusRows->toArray()
                             )->toString();
    }

    private function stylize(Collection $allTargetStatuses): Collection
    {
        return
            $allTargetStatuses->map(
                function ($cols) {
                    [$path, $backup, $restore] = $cols;

                    // managed is blue
                    if ($backup == 'managed' && $restore == 'managed') {
                        $path = Ansi::blue($path);
                    }
                    if ($backup == 'managed') {
                        $backup = Ansi::blue($backup);
                    }
                    if ($restore == 'managed') {
                        $restore = Ansi::blue($restore);
                    }

                    return [$path, $backup, $restore];
                });
    }
}