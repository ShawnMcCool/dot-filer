<?php namespace DotFiler\TextFormatting;

use DotFiler\Collections\Collection;

final class Ansi
{
    private string $text;
    private Collection $tags;

    public function __construct(string $text, Collection $tags)
    {
        $this->text = $text;
        $this->tags = $tags;
    }

    public function __toString()
    {
        $openTag = $this->tags->reduce(
            function ($tagString, $tag) {
                return $tagString . "\033[{$tag}m";
            }, '');

        $closeTag = $this->tags->reduce(
            function ($tagString, $tag) {
                return $tagString . "\033[0m";
            }, '');

        return $openTag . $this->text . $closeTag;
    }

    public static function format(string $text, int ...$ansiCodes)
    {
        return new static($text, Collection::of($ansiCodes));
    }

    public static function red(string $text): Ansi
    {
        return new static($text, Collection::list(AnsiCodes::$red));
    }

    public static function green(string $text): Ansi
    {
        return new static($text, Collection::list(AnsiCodes::$green));
    }

    public static function yellow(string $text): Ansi
    {
        return new static($text, Collection::list(AnsiCodes::$yellow));
    }

    public static function blue(string $text): Ansi
    {
        return new static($text, Collection::list(AnsiCodes::$blue));
    }

    public static function magenta(string $text): Ansi
    {
        return new static($text, Collection::list(AnsiCodes::$magenta));
    }

    public static function cyan(string $text): Ansi
    {
        return new static($text, Collection::list(AnsiCodes::$cyan));
    }

    public static function white(string $text): Ansi
    {
        return new static($text, Collection::list(AnsiCodes::$white));
    }

    public static function bold(string $text): Ansi
    {
        return new static($text, Collection::list(AnsiCodes::$bold));
    }

    public static function italic(string $text): Ansi
    {
        return new static($text, Collection::list(AnsiCodes::$italic));
    }

    public static function underline(string $text): Ansi
    {
        return new static($text, Collection::list(AnsiCodes::$underline));
    }

    public static function blink(string $text): Ansi
    {
        return new static($text, Collection::list(AnsiCodes::$blink));
    }

    public static function plain(string $text): Ansi
    {
        return new static($text, Collection::empty());
    }
}