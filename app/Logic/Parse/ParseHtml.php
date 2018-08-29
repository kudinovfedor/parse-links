<?php

namespace App\Logic\Parse;

use Yangqi\Htmldom\Htmldom;

class ParseHtml
{
    /**
     * @var string
     */
    public $url;

    /**
     * @var mixed
     */
    public $site;

    /**
     * @var array
     */
    public $links = [];

    /**
     * @var array
     */
    public $filter_links = [];

    /**
     * @var array
     */
    public static $processed = [];

    /**
     * @var array
     */
    public static $not_processed = [];

    /**
     * ParseHtml constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
        $this->site = parse_url($this->url);
    }

    public function getLinks(string $url): array
    {
        $html = new Htmldom($url);

        foreach ($html->find('a') as $link) {

            //$links[] = parse_url($link->href);
            //$links[] = $link->href . $link->plaintext;
            if ($link->href) {
                $this->links[] = rtrim($link->href, '/');
            }
        }

        return $this->links;

    }

    public function filterLinks(array $links): array
    {
        $this->filter_links = $this->fixRelativeLinks($links, $this->site);
        $this->filter_links = $this->filterExcludeFragment($this->filter_links);
        $this->filter_links = $this->filterExcludeExternalLinks($this->filter_links, $this->site['host']);
        $this->filter_links = $this->filterExcludeImages($this->filter_links);
        $this->filter_links = $this->filterExcludeDocuments($this->filter_links);
        $this->filter_links = $this->filterUnique($this->filter_links);

        return $this->filter_links;
    }

    public function links()
    {
        $this->getLinks($this->url);

        return $this->filterLinks($this->links);
    }

    public function filterExcludeFragment(array $arr): array
    {
        return array_filter($arr, function ($value) {

            $fragment = parse_url($value, PHP_URL_FRAGMENT);

            return $fragment === null;

        }, ARRAY_FILTER_USE_BOTH);
    }

    public function fixRelativeLinks(array $links, array $site): array
    {
        $relative_links = [];

        foreach ($links as $key => $url) {
            $link = parse_url(trim($url));

            if (
                (!isset($link['scheme']) && !isset($link['host']))
                &&
                (isset($link['path']) && !empty($link['path']) && $link['path'] !== '/')
            ) {
                $path = $link['path'];

                if (0 !== strpos($path, '/')) {
                    $path = '/' . $path;
                }

                $relative_links[] = sprintf('%s://%s%s', $site['scheme'], $site['host'], $path);

            } elseif (!isset($link['scheme']) && isset($link['host'])) {
                $relative_links[] = sprintf('%s:%s', $site['scheme'], $url);

            } else {
                $relative_links[] = $url;
            }
        }

        return $relative_links;
    }

    public function filterUnique(array $arr): array
    {
        return array_unique($arr);
    }

    public function filterOnlyExternalLinks(array $arr, string $site_host): array
    {
        return array_filter($arr, function ($value) use ($site_host) {

            $link_host = parse_url($value, PHP_URL_HOST) ?? false;

            return $link_host !== $site_host;

        }, ARRAY_FILTER_USE_BOTH);
    }

    public function filterExcludeExternalLinks(array $arr, string $site_host): array
    {
        return array_filter($arr, function ($value) use ($site_host) {

            $link_host = parse_url($value, PHP_URL_HOST) ?? false;

            return $link_host === $site_host;

        }, ARRAY_FILTER_USE_BOTH);
    }

    public function filterExcludeImages(array $arr): array
    {
        return array_filter($arr, function ($value) {

            return 1 !== preg_match('/\.(?:jpe?g|png|gif|bmp)/i', $value);

        }, ARRAY_FILTER_USE_BOTH);
    }

    public function filterExcludeDocuments(array $arr): array
    {
        return array_filter($arr, function ($value) {

            return 1 !== preg_match('/\.(?:doc|pdf)/i', $value);

        }, ARRAY_FILTER_USE_BOTH);
    }
}
