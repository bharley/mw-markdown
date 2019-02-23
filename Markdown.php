<?php
/**
 * Markdown
 * Markdown MediaWiki parser extension.
 *
 * This is a (rather simple) MediaWiki extension that uses erusev's Parsedown.
 *
 * @author    Blake Harley <blake@blakeharley.com>
 * @version   0.2
 * @copyright Copyright (C) 2014 Blake Harley
 * @license   MIT License
 * @link      https://github.com/bharley/mw-markdown
 */
 
// Prevent global hijackingengine
if (!defined('MEDIAWIKI')) die();

// Credits
$wgExtensionCredits['parserhook'][] = array(
    'name'         => 'Markdown',
    'description'  => 'Uses Markdown for wiki parsing',
    'version'      => '0.2',
    'author'       => 'Blake Harley',
    'url'          => 'https://github.com/bharley/mw-markdown',
    'license-name' => 'MIT',
);

// Available config options
$wgMarkdownDefaultOn    = true;
$wgMarkdownToggleFormat = '{{%s}}';
$wgMarkdownWikiLinks    = true;
$wgMarkdownExtra        = false;
$wgMarkdownHighlight    = false;
$wgMarkdownHighlightJs  = null;
$wgMarkdownHighlightCss = null;

// Hook
$wgHooks['ParserBeforeInternalParse'][] = 'MarkdownExtension::onParserBeforeInternalParse';
$wgHooks['BeforePageDisplay'][]         = 'MarkdownExtension::onBeforePageDisplay';

// Load Parsedown (https://github.com/erusev/parsedown)
require_once('Parsedown.php');
if (file_exists(__DIR__ . '/ParsedownExtra.php'))
{
    // Optionally, load Parsedown Extra (https://github.com/erusev/parsedown-extra)
    require_once('ParsedownExtra.php');
}

/**
 * Wrap the hook function in a class so we don't pollute the global namespace.
 */
class MarkdownExtension
{
    public static function onBeforePageDisplay(OutputPage &$out)
    {
        global $wgMarkdownHighlight;
        global $wgMarkdownHighlightJs;
        global $wgMarkdownHighlightCss;

        if ($wgMarkdownHighlight)
        {
            $out->addScriptFile($wgMarkdownHighlightJs);
            $out->addStyle($wgMarkdownHighlightCss);
            $out->addInlineScript('hljs.initHighlightingOnLoad();');
        }

        return true;
    }

    /**
     * If everything checks out, this hook will parse the given text for Markdown.
     *
     * @param Parser $parser MediaWiki's parser
     * @param string $text   The text to parse
     */
    public static function onParserBeforeInternalParse($parser, &$text)
    {
        global $wgMarkdownDefaultOn;

        if (static::shouldParseText($text))
        {
            if (!$wgMarkdownDefaultOn)
            {
                $text = substr($text, strlen(static::getSearchString()));
            }

            $text = static::parseMarkdown($parser, $text);

            return false;
        }
        else
        {
            if ($wgMarkdownDefaultOn)
            {
                $text = substr($text, strlen(static::getSearchString()));
            }

            return true;
        }
    }

    /**
     * Converts the given text into markdown.
     *
     * @param  Parser $parser MediaWiki's parser
     * @param  string $text   The text to parse
     * @return string         The parsed text
     */
    protected static function parseMarkdown($parser, $text)
    {
        global $wgMarkdownWikiLinks;

        $html = $text;

        // Post-Markdown wiki parsing
        $html = $parser->replaceVariables($html);
        $html = $parser->doDoubleUnderscore($html);

        // Parse Markdown
        $html = static::getParser()->text($html);

        // Attempt to use Wiki-style links if turned on
        if ($wgMarkdownWikiLinks)
        {
            $html = preg_replace_callback('#<a href="(.+?)"(?: title=".+?")?>(.+?)</a>#i', function ($matches) {
                list($match, $url, $text) = $matches;
                $external = (bool) preg_match('#^[a-z]+://#i', $url);

                return sprintf($external ? '[%s %s]' : '[[%s|%s]]', $url, $text);
            }, $html);

            $html = $parser->replaceInternalLinks($html);
            $html = $parser->replaceExternalLinks($html);
            $parser->replaceLinkHolders($html);
        }

        // Post-Markdown wiki parsing
        $html = $parser->formatHeadings($html, $text);
        $html = $parser->doMagicLinks($html);

        return $html;
    }

    /**
     * @param  string $text The text to check over for our tags if necessary
     * @return bool         Whether or not to parse the given text
     */
    protected static function shouldParseText($text)
    {
        global $wgMarkdownDefaultOn;

        $search = static::getSearchString();

        return (($wgMarkdownDefaultOn && strpos($text, $search) !== 0)
            || (!$wgMarkdownDefaultOn && strpos($text, $search) === 0));
    }

    /**
     * @return string The search string
     */
    protected static function getSearchString()
    {
        global $wgMarkdownDefaultOn;
        global $wgMarkdownToggleFormat;

        return sprintf($wgMarkdownToggleFormat, $wgMarkdownDefaultOn ? 'WIKI' : 'MARKDOWN');
    }

    /**
     * @return Parsedown
     */
    protected static function getParser()
    {
        static $parser;
        global $wgMarkdownExtra;

        if (!$parser)
        {
            $parser = $wgMarkdownExtra ? new ParsedownExtra : new Parsedown;
        }

        return $parser;
    }
}

