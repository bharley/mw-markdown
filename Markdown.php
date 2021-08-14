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
 * @license   MIT
 * @link      https://github.com/bharley/mw-markdown
 */

if ( function_exists( 'wfLoadExtension' ) ) {
	wfLoadExtension( 'Markdown' );
	wfWarn(
		'Deprecated PHP entry point used for Markdown extension. Please use wfLoadExtension instead, ' .
		'see https://www.mediawiki.org/wiki/Extension_registration for more details.'
	);
	return;
} else {
	die( 'This version of the Markdown extension requires MediaWiki 1.25+' );
}
