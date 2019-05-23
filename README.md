# Markdown MediaWiki Extension

## About

This is a relatively simple extension for [MediaWiki] that allows [Markdown] instead of
Wiki markup in articles. This extension uses [erusev]'s [Parsedown] and, optionally,
[Parsedown Extra] libraries.

## Installation

- Download this extension (grab the latest from [releases](https://github.com/bharley/mw-markdown/releases))
- Extract this extension into `$mw/extensions/Markdown` where `$mw` is that path to your MediaWiki installation
- Download [Parsedown] and place `Parsedown.php` in `$mw/extensions/Markdown`
- **(Optional)** Download [Parsedown Extra] and place `ParsedownExtra.php` in `$mw/extensions/Markdown`
- Add the following to `$mw/LocalSettings.php`:

```php
require_once("$IP/extensions/Markdown/Markdown.php");
```

## Usage

By default, all of your articles will now be parsed using Markdown instead of Wiki markup. If you have a wiki with articles already, you will either need to convert them to Markdown or put the `{{WIKI}}` tag at the very beginning of the article to tell this extension not to run.

You can make Markdown parsing optional by setting `$wgMarkdownDefaultOn` to `false` and adding `{{MARKDOWN}}` at the beginning of any articles you want Markdown parsing on. Look at the [settings](#settings) to see more about configuring this extension.

## Settings

There are a handful of settings available to configure this extension:

Setting                   | Since | Default  | Description
------------------------- | ----- | -------- | -----------
`$wgMarkdownDefaultOn`    | 0.1   | `true`   | If this is set to false, an article must start with `{{MARKDOWN}}` to be parsed for Markdown.
`$wgMarkdownToggleFormat` | 0.1   | `{{%s}}` | The `{{%s}}` syntax is used by MediaWiki for templates. While this should not be a problem in most cases, you can change the tag boundries if problems arise.
`$wgMarkdownWikiLinks`    | 0.1   | `true`   | If enabled, links will be run though WikiMedia's linkifier so it can properly generate local links among other things. This functionality is a little experimental, so disable it if it is causing problems.
`$wgMarkdownExtra`        | 0.1   | `false`  | If enabled, [Parsedown Extra] will be loaded and used. **Note:** Make sure Parsedown Extra is downloaded before enabling this.
`$wgMarkdownHighlight`    | 0.2   | `false`  | If enabled, attempts to load [Highlight.js].
`$wgMarkdownHighlightJs`  | 0.2   | `null`   | The path to the Highlight.js JavaScript file.
`$wgMarkdownHighlightCss` | 0.2   | `null`   | The path to the Highlight.js CSS file.

Settings should go in your `LocalSettings.php` file **after** including the extension.

### Example

```php
// LocalSettings.php
// ...
require_once("$IP/extensions/Markdown/Markdown.php");

$wgMarkdownExtra        = true;
$wgMarkdownHighlight    = true;
$wgMarkdownHighlightJs  = '//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.0/highlight.min.js';
$wgMarkdownHighlightCss = '//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.0/styles/default.min.css';
// ...
```



[MediaWiki]: https://www.mediawiki.org
[Markdown]: http://daringfireball.net/projects/markdown/
[erusev]: https://github.com/erusev
[Parsedown]: https://github.com/erusev/parsedown
[Parsedown Extra]: https://github.com/erusev/parsedown-extra
[Highlight.js]: https://highlightjs.org/
