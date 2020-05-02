<?php namespace DotFiler\TextFormatting;

use DotFiler\Collections\Collection;

final class TextRecordListing
{
    /** @var Collection */
    private $records;
    /** @var string */
    private $title = '';
    /** @var int */
    private $width;

    private function __construct(int $width)
    {
        $this->records = Collection::empty();
        $this->width = $width;
    }

    public function withRecords(Collection $records): TextRecordListing
    {
        $this->records = $records;
        return $this;
    }

    public function withTitle(string $title): TextRecordListing
    {
        $this->title = $title;
        return $this;
    }

    public function toString(): string
    {

        $title = empty($this->title) ? '' : $this->centerText($this->title);

        $line = $this->makeHorizontalLine();

        $output = $title . $line;

        if ($this->records->isEmpty()) {
            $output .= $this->makeDataRow("No results found.");
            $output .= $line;
            return $output;
        }

        foreach ($this->records as $record) {
            $recordRows = explode("\n", $record);
            foreach ($recordRows as $row) {
                 $output .= $this->makeDataRow(trim($row, "\n"));
            }
            $output .= $line;
        }

        return $output;
    }

    private function centerText(string $text): string
    {
        $stripped = $this->stripAnsi($text);
        $halfWidth = ceil($this->width / 2);
        $halfTextWidth = intdiv(strlen($stripped), 2);

        return str_repeat(' ', $halfWidth-$halfTextWidth) . $text . str_repeat(' ', $halfWidth-$halfTextWidth) . "\n";
    }

    public function toMarkdown(): string
    {
        return "```\n" . $this->toString() . "```";
    }

    private function makeDataRow(string $text): string
    {
        $row = '';
        foreach (str_split($text, $this->width - 2) as $line) {
            $row .= "| " . $this->padColumn($line, $this->width - 2) . " |\n";
        }
        return $row;
    }

    private function padColumn(string $text, int $width)
    {
        $ansiCharacterPadding = strlen($text) - strlen($this->stripAnsi($text));
        return str_pad($text, $width + $ansiCharacterPadding);
    }

    private function stripAnsi(string $text): string
    {
        $text = preg_replace('/\x1b(\[|\(|\))[;?0-9]*[0-9A-Za-z]/', "", $text);
        $text = preg_replace('/\x1b(\[|\(|\))[;?0-9]*[0-9A-Za-z]/', "", $text);
        return preg_replace('/[\x03|\x1a]/', "", $text);
    }

    private function makeHorizontalLine(): string
    {
        return '+' . str_repeat('-', $this->width) . '+' . "\n";
    }

    public static function width(int $width): TextRecordListing
    {
        return new static($width);
    }
}