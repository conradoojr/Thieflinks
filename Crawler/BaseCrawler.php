<?php
namespace Conradoojr\ThiefLinks\Crawler;

use Conradoojr\ThiefLinks\Url;

abstract class BaseCrawler implements ICrawler
{
    public $url;
    public function __construct($siteUrl)
    {
        $this->url = new Url();
        $this->url->fill($siteUrl);
    }
}
