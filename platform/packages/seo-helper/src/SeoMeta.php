<?php

namespace Botble\SeoHelper;

use Botble\Base\Facades\BaseHelper;
use Botble\SeoHelper\Contracts\Entities\AnalyticsContract;
use Botble\SeoHelper\Contracts\Entities\DescriptionContract;
use Botble\SeoHelper\Contracts\Entities\MiscTagsContract;
use Botble\SeoHelper\Contracts\Entities\TitleContract;
use Botble\SeoHelper\Contracts\Entities\WebmastersContract;
use Botble\SeoHelper\Contracts\SeoMetaContract;

class SeoMeta implements SeoMetaContract
{
    /**
     * The Title instance.
     *
     * @var TitleContract
     */
    protected $title;

    /**
     * The Description instance.
     *
     * @var DescriptionContract
     */
    protected $description;

    /**
     * The MiscTags instance.
     *
     * @var MiscTagsContract
     */
    protected $misc;

    /**
     * The Webmasters instance.
     *
     * @var WebmastersContract
     */
    protected $webmasters;

    /**
     * The Analytics instance.
     *
     * @var AnalyticsContract
     *
     * @deprecated since 7.3.0 use ThemeSupport::renderGoogleTagManagerScript() instead.
     */
    protected $analytics;

    protected ?string $currentUrl = null;

    /**
     * Make SeoMeta instance.
     */
    public function __construct()
    {
        $this->title(new Entities\Title());
        $this->description(new Entities\Description());
        $this->misc(new Entities\MiscTags());
        $this->webmasters(new Entities\Webmasters());
        $this->analytics(new Entities\Analytics());
    }

    /**
     * Set the Title instance.
     *
     * @param TitleContract $title
     *
     * @return $this
     */
    public function title(TitleContract $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set the Description instance.
     *
     * @param DescriptionContract $description
     *
     * @return $this
     */
    public function description(DescriptionContract $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set the MiscTags instance.
     *
     * @param MiscTagsContract $misc
     *
     * @return $this
     */
    public function misc(MiscTagsContract $misc)
    {
        $this->misc = $misc;

        return $this;
    }

    /**
     * Set the Webmasters instance.
     *
     * @param WebmastersContract $webmasters
     *
     * @return $this
     */
    public function webmasters(WebmastersContract $webmasters)
    {
        $this->webmasters = $webmasters;

        return $this;
    }

    /**
     * Set the Analytics instance.
     *
     * @param AnalyticsContract $analytics
     *
     * @return $this
     *
     * @deprecated since 7.3.0 use ThemeSupport::renderGoogleTagManagerScript() instead.
     */
    protected function analytics(AnalyticsContract $analytics)
    {
        $this->analytics = $analytics;

        return $this;
    }

    /**
     * @param $code
     * @return $this
     */
    public function setGoogle($code)
    {
        $this->analytics->setGoogle($code);

        return $this;
    }

    /**
     * Get the title.
     *
     * @return string
     */
    public function getTitle()
    {
        $title = $this->title->getTitleOnly();

        if (! theme_option('show_site_name') && $title) {
            return $title;
        }

        return $this->title->getTitle();
    }

    public function getTitleOnly(): ?string
    {
        return $this->title->getTitleOnly();
    }

    /**
     * Set the title.
     *
     * @param string $title
     * @param string $siteName
     * @param string $separator
     *
     * @return $this
     */
    public function setTitle($title, $siteName = null, $separator = null)
    {
        if (! empty($title)) {
            $this->title->set($title);
        }

        if (! empty($siteName)) {
            $this->title->setSiteName($siteName);
        }

        if (! empty($separator)) {
            $this->title->setSeparator($separator);
        }

        return $this;
    }

    /**
     * Set the description content.
     *
     * @param string $content
     *
     * @return $this
     */
    public function setDescription($content)
    {
        $content = BaseHelper::cleanShortcodes($content);

        $this->description->set($content);

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description->getContent();
    }

    /**
     * Add a webmaster tool site verifier.
     *
     * @param string $webmaster
     * @param string $content
     *
     * @return $this
     */
    public function addWebmaster($webmaster, $content)
    {
        $this->webmasters->add($webmaster, $content);

        return $this;
    }

    /**
     * Set the current URL.
     *
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->currentUrl = $url;
        $this->misc->setUrl($url);

        return $this;
    }

    /**
     * Set the Google Analytics code.
     *
     * @param string $code
     *
     * @return $this
     */
    public function setGoogleAnalytics($code)
    {
        $this->analytics->setGoogle($code);

        return $this;
    }

    /**
     * Add a meta tag.
     *
     * @param string $name
     * @param string $content
     *
     * @return $this
     */
    public function addMeta($name, $content)
    {
        $this->misc->add($name, $content);

        return $this;
    }

    /**
     * Add many meta tags.
     *
     * @param array $meta
     *
     * @return $this
     */
    public function addMetas(array $meta)
    {
        $this->misc->addMany($meta);

        return $this;
    }

    /**
     * Set meta keywords.
     *
     * @param array|string $keywords
     * @return $this
     */
    public function setKeywords($keywords)
    {
        if (is_array($keywords)) {
            $keywords = implode(', ', array_unique(array_filter(array_map('trim', $keywords))));
        } else {
            $keywords = trim((string) $keywords);
        }

        if (!empty($keywords)) {
            $this->misc->add('keywords', $keywords);
        }

        return $this;
    }

    /**
     * Generate keywords from base keywords and cities.
     *
     * @param array $baseKeywords
     * @param array $cities
     * @param int $limit
     * @return array
     */
    public function generateKeywords(array $baseKeywords, array $cities = [], int $limit = 20): array
    {
        $out = [];

        foreach ($baseKeywords as $kw) {
            $kw = trim($kw);
            if ($kw === '') {
                continue;
            }

            $out[] = $kw;

            foreach ($cities as $city) {
                $city = trim($city);
                if ($city === '') {
                    continue;
                }

                $out[] = $kw . ' ' . $city;
                $out[] = $kw . ' in ' . $city;
            }
        }

        $out = array_values(array_unique($out));

        return array_slice($out, 0, max(0, $limit));
    }

    /**
     * Convenience method: generate keywords and set them as meta keywords.
     *
     * @param array $baseKeywords
     * @param array $cities
     * @param int $limit
     * @return $this
     */
    public function setGeneratedKeywords(array $baseKeywords, array $cities = [], int $limit = 20)
    {
        $keywords = $this->generateKeywords($baseKeywords, $cities, $limit);

        return $this->setKeywords($keywords);
    }

    public function getAnalytics(): AnalyticsContract
    {
        return $this->analytics;
    }

    /**
     * Render all seo tags.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Render all seo tags.
     *
     * @return string
     */
    public function render()
    {
        return implode(PHP_EOL, array_filter([
            $this->title->render(),
            $this->description->render(),
            $this->misc->render(),
            $this->webmasters->render(),
        ]));
    }
}
