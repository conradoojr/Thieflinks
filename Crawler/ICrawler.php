<?php
namespace Conradoojr\ThiefLinks\Crawler;

interface ICrawler {
    public function getAllLinks();
    public function getLinkByEpisode($pathEpisode);
    public function getClassName();
}
