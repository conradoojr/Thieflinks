<?php
namespace Conradoojr\ThiefLinks\Crawler;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use RuntimeException;

class SeriesOnlinex extends BaseCrawler
{
    public function __construct($siteUrl)
    {
        parent::__construct($siteUrl);
    }

    public function getClassName()
    {
        return str_replace('\\','',str_replace(__NAMESPACE__, '', __CLASS__));
    }

    /**
     * Get all seasons with all epsisodes
     * @return [array] [array of seasons with episodes]
     */
    public function getAllLinks()
    {
        $client = new Client([ 'base_uri' => $this->url->domain ]);
        $response = $client->request('GET', $this->url->path);
        $htmlSite = (string)$response->getBody();

        if($response->getStatusCode() != 200) {
            throw new RuntimeException('Status code different of 200');
        }

        $crawler = new DomCrawler($htmlSite);
        $urlIframe = $crawler->filter('#tab1 > iframe')->first()->attr('src');
        print_r($urlIframe);
        exit;


        return $result;
    }

    public function getLinkByEpisode($pathEpisode)
    {
        $result['name'] = __function__;
        return $result;
    }

    private function getPlayerLink($linkEpisode)
    {
        return 'string com o link do player';
    }

    public function search($term)
    {
        $indexOfResult = $this->getClassName();
        $result = [];
        $client = new Client([ 'base_uri' => $this->url->domain() ]);
        $response = $client->request('GET', '/', ['query' => ['s' => $term]]);
        $htmlSite = (string)$response->getBody();

        if($response->getStatusCode() != 200) {
            throw new RuntimeException('Status code different of 200');
        }
        $crawler = new DomCrawler($htmlSite);

        $i = 0;
        $lis = $crawler->filter('ul.post li');
        foreach ($lis as $ii => $li) {
            $li = new DomCrawler($li);

            if (count($li->children()) == 0) {
                continue;
            }

            $result[$i]['title'] = trim($li->filter('h2')->text());
            $result[$i]['image'] = trim($li->filter('img')->attr('src'));
            $result[$i]['link']  = trim($li->filter('a')->attr('href'));
            $result[$i]['type']  = strtolower(trim($li->filter('.calidad')->text()));
            $i++;
        }
        return $result;
    }
}
