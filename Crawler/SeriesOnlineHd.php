<?php
namespace ThiefLinks\Crawler;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use RuntimeException;

class SeriesOnlineHd extends BaseCrawler
{
    public function __construct($siteUrl)
    {
        parent::__construct($siteUrl);
    }

    /**
     * Get all seasons with all epsisodes
     * @return [array] [array of seasons with episodes]
     */
    public function getAllLinks()
    {
        $client = new Client([ 'base_uri' => $this->url->domain() ]);
        $response = $client->request('GET', $this->url->path);
        $htmlSite = (string)$response->getBody();

        if($response->getStatusCode() != 200) {
            throw new RuntimeException('Status code different of 200');
        }

        $crawler = new DomCrawler($htmlSite);

        $filter = $crawler->filter('.tab-ep-list');
        $result = [];

        $seasonNumber = 0;
        $episodeNumber = 0;
        $linkWithSubTitle = '';
        $linkWithVoiced ='';

        if (iterator_count($filter) > 0) {
            foreach ($filter as $i => $content) {
                $cralwer = new DomCrawler($content);
                $tds = $cralwer->filter('td');
                foreach ($tds as $key => $td) {
                    $cralwer = new DomCrawler($td);
                    $currentClass = $cralwer->attr('class');

                    //get seasons
                    if ($currentClass == 'ep-ntp') {
                        $seasonNumber = (int)$cralwer->text();

                        if ($seasonNumber!= 0 && !array_key_exists($seasonNumber, $result)) {
                            $result[$seasonNumber] = [];
                        }
                    }

                    //get episodes
                    if ($currentClass == 'ep-nep') {
                        $episodeNumber = filter_var($cralwer->text(), FILTER_SANITIZE_NUMBER_INT);

                        if ($episodeNumber!= 0) {
                            $result[$seasonNumber][$episodeNumber] = [];
                        }
                    }

                    //get link of voiced episode
                    if ($currentClass == 'ep-dub' && count($cralwer->filter('a')) > 0 ) {
                        $linkWithVoiced = explode($this->url->domain(), $cralwer->filter('a')->attr('href'))[1];
                        if ($linkWithVoiced != '') {
                            $result[$seasonNumber][$episodeNumber]['dublado'] = $linkWithVoiced;
                        }
                    }

                    //get link of episode with subtitle
                    if ($currentClass == 'ep-leg' && count($cralwer->filter('a')) > 0 ) {
                        $linkWithSubTitle = explode($this->url->domain(), $cralwer->filter('a')->attr('href'))[1];
                        if ($linkWithSubTitle != '') {
                            $result[$seasonNumber][$episodeNumber]['legendado'] = $linkWithSubTitle;
                        }
                    }
                }
            }
        }
        else {
            throw new RuntimeException('Got empty result processing the dataset!');
        }

        return $result;
    }

    public function getLinkByEpisode($pathEpisode)
    {
        $client = new Client([ 'base_uri' => $this->url->domain() ]);
        $response = $client->request('GET', $pathEpisode);
        $htmlSite = (string)$response->getBody();

        if($response->getStatusCode() != 200) {
            throw new RuntimeException('Status code different of 200');
        }

        $crawler = new DomCrawler($htmlSite);
        $filter = $crawler->filter('ul.player-opcoes li');
        $result = '';
        if (iterator_count($filter) > 0) {
            foreach ($filter as $i => $content) {
                $cralwer = new DomCrawler($content);
                if ( strtolower($cralwer->text()) != 'principal') {
                    continue;
                }

                $pathAndQuery = explode($this->url->domain(), $cralwer->filter('a')->attr('href'))[1];
                $result = $this->getPlayerLink($pathAndQuery);
            }
        }
        return $result;
    }

    private function getPlayerLink($linkEpisode)
    {
        $client = new Client([ 'base_uri' => $this->url->domain() ]);
        $response = $client->request('GET', $linkEpisode);
        $htmlSite = (string)$response->getBody();

        if($response->getStatusCode() != 200) {
            throw new RuntimeException('Status code different of 200');
        }

        $crawler = new DomCrawler($htmlSite);
        $filter = $crawler->filter('iframe.ps-iframe');
        return $filter->attr('src');
    }

}
