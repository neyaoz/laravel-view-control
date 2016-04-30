<?php
namespace Rephole\ViewControl\Vendor\Wa72\HtmlPageDom;

use Wa72\HtmlPageDom\HtmlPageCrawler as BaseHtmlPageCrawler;

class HtmlPageCrawler extends BaseHtmlPageCrawler
{

    /**
     * Get an HtmlPageCrawler object from a HTML string, DOMNode, DOMNodeList or HtmlPageCrawler
     *
     * This is the equivalent to jQuery's $() function when used for wrapping DOMNodes or creating DOMElements from HTML code.
     *
     * @param string|HtmlPageCrawler|\DOMNode|\DOMNodeList|array $content
     * @return static
     * @api
     */
    public static function create($content)
    {
        if ($content instanceof HtmlPageCrawler) {
            return $content;
        }

        return new static($content);
    }

    /**
     * @param string $selector
     * @return static
     */
    public function find($selector)
    {
        return parent::filter($selector);
    }

}