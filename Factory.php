<?php

namespace Conradoojr\ThiefLinks;

use Conradoojr\ThiefLinks\Url;
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

    public function __construct()
    {
        $this->availableSiteList =[
            'http://www.seriesonlinehd.org' => __NAMESPACE__.'\Crawler\SeriesOnlineHd',
        ];
    }

    public function search($term)
    {
        $result = [];
        foreach ($this->availableSiteList as $key => $site) {
            $availableClass = $this->availableSiteList[$key];
            $availableObject = new $availableClass($key);
            $lowerAvailableClassName = strtolower($availableObject->getClassName());
            $result[$lowerAvailableClassName] =$availableObject->search($term);
        }
        return $result;
    }

    public function createCrawler($url)
    {
        $this->url = new Url();
        $this->url->fill($url);

        if (!array_key_exists($this->url->domain, $this->availableSiteList)) {
            throw new \InvalidArgumentException($this->url->domain .  ' is not available');
        }
        $className = $this->availableSiteList[$this->url->domain];

        return new $className($this->url->full);
    }
}
