<?php
namespace ThiefLinks\Crawler;

interface ICrawler {
    public function getAllLinks();
    public function getLinkByEpisode($pathEpisode);
}
