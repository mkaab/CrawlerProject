<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Crawler\Crawler;
use App\Controller;
use \Spatie\Crawler\CrawlObserver;

class TitleLogger extends CrawlObserver{

    private $pages =[];


    public function crawled(
        UriInterface $url,
        ResponseInterface $response,
        UriInterface $foundOnUrl = null
    )
    {

        $path = $url->getPath();
        $doc = new \DOMDocument();
        @$doc->loadHTML($response->getBody());
        $title = $doc->getElementsByTagName("title")[0]->nodeValue;

        $this->pages[] = [
            'path'=>$path,
            'title'=> $title
        ];
    }

    public function crawlFailed(
        UriInterface $url,
        RequestException $requestException,
        ?UriInterface $foundOnUrl = null
    )
    {
        echo 'failed';
    }

    public function finishedCrawling()
    {
        echo 'crawled ' . count($this->pages) . ' urls' . PHP_EOL;
        }
        foreach ($this->pages as $page){
            echo sprintf("Url  path: %s Page title: %s%s", $page['path'], $page['title'], PHP_EOL);
        }
    }

}


class LuckyController
{
    public function number()
    {
        $number = 'https://fellow-consulting.co.uk/'; //add your own URL
	Crawler::create()
    	->setCrawlObserver(new TitleLogger())
    	->startCrawling($number);

    }
}

