<?php namespace DotFiler;

use DotFiler\Procedures\Results;
use DotFiler\TextFormatting\Ansi;
use DotFiler\Collections\Dictionary;
use DotFiler\Collections\Collection;

final class Recommendations
{
    public static function fromResults(Results $results): Dictionary
    {
        return new static($results->all());
    }

    public static function fromTargetStatuses(Collection $statuses): Dictionary
    {
        return Dictionary::of(
            $statuses->toArray()
        )->map(
            function ($index, $targetBackupRestore) {
                [$targetPath, $backupStatus, $restoreStatus] = $targetBackupRestore;
                
                $recommendation = '';
                
                if ($backupStatus == "not ready for management" && $restoreStatus == "unmanaged") {
                    if ( ! is_writable($targetPath)) {
                        $recommendation = 'You do not have permission to change this target. Consider running with sudo.';
                    }
                }
                
                if ( ! $recommendation) return [null => null];
                
                return [$targetPath => $recommendation];
            }
        )->filter();
    }
}