<?php
/**
 * Markdown
 * Markdown MediaWiki parser extension.
 *
 * This is a (rather simple) MediaWiki extension that uses erusev's Parsedown.
 *
 * @author    Blake Harley <blake@blakeharley.com>
 * @version   0.3
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
    'version'      => '0.3',
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

$wgAutoloadClasses['MarkdownExtension'] = __DIR__ . '/includes/MarkdownExtension.php';

// Hook
$wgHooks['ParserBeforeInternalParse'][] = 'MarkdownExtension::onParserBeforeInternalParse';
$wgHooks['BeforePageDisplay'][]         = 'MarkdownExtension::onBeforePageDisplay';

if (file_exists(__DIR__ . '/vendor/autoload.php'))
{
    require_once(__DIR__ . '/vendor/autoload.php');
}
