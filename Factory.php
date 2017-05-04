<?php

namespace Conradoojr\ThiefLinks;

/**
 * class SimpleFactory.
 */
class Factory
{
    /**
     * @var array
     */
    protected $availableSiteList;
    protected $url;
    /**
     * You can imagine to inject your own type list or merge with
     * the default ones...
     */
    public function __construct($url)
    {
        $this->availableSiteList =[
            'www.seriesonlinehd.org' => __NAMESPACE__.'\Crawler\SeriesOnlineHd',
        ];

        $this->url = new Url();
        $this->url->fill($url);
    }

    public function createCrawler()
    {
        if (!array_key_exists($this->url->host, $this->availableSiteList)) {
            throw new \InvalidArgumentException("$this->url->host is not available");
        }
        $className = $this->availableSiteList[$this->url->host];

        return new $className($this->url->full);
    }
}
