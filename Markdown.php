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

$wgAutoloadClasses['MarkdownExtension'] = __DIR__ . '/includes/MarkdownExtension.php';

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
