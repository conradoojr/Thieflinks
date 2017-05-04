<?php
namespace ThiefLinks\Conradoojr\\Crawler;

interface ICrawler {
    public function getAllLinks();
    public function getLinkByEpisode($pathEpisode);
}
