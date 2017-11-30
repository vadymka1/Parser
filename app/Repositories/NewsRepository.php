<?php

namespace App\Repositories;

use App\Repositories\ParserInterface as ParserInterface;
use App\News;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Goutte\Client;
use GuzzleHttp\Client as Guzzle;


class NewsRepository implements ParserInterface
{
    /**
     * The attributes that are mass assignable.
     *
     * @var
     */

    public $link = 'https://www.segodnya.ua/regions/odessa.html';
    public $urls = [];

    /**
     * The main method thar parser data from an article and save in DB.
     *
     * @method
     */

    public function parserAction()
    {

        /**
         *
         * Request to articles page.
         *
         */

        foreach ($this->parserPages() as $url) {

            if ($url == "https://www.segodnya.ua/regions/odessa/archive.html") {
                break;
            }

            /**
             *
             * Request to receive HTML of list of news.
             *
             */

            $client = new Client();
            $crawler = $client->request('GET', "$url");

            /**
             *
             * Filtration data by div.
             *
             */

            $crawler->filter('div.news-block')->each(function ($node) {

                if ($node->filter('a')->count()) {

                    $months_name = [
                        'Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря', 'Сегодня', 'Вчера',
                    ];

                    $months_number = [
                        '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', Carbon::now()->toDateString(), Carbon::yesterday()->toDateString(),
                    ];

                    $date = explode(',', $node->filter('.date')->text());

                    /**
                     *
                     * Check and record date data.
                     *
                     */

                    if ($date[0] != 'Сегодня' && $date[0] != 'Вчера') {
                        $date = str_replace($months_name, $months_number, $date);
                        $md = explode(' ', $date[0]);
                        $hs = explode(':', $date[1]);

                        $date = Carbon::create(null, $md[1], $md[0], $hs[0], $hs[1])->toDateTimeString();

                        $current_date = Carbon::now()->startOfDay();
                        $formatted_date = Carbon::parse($date)->startOfDay();
                        $diff = $current_date->diffInDays($formatted_date);
                        if ($diff >= 5) {
                            return false ;
                        }
                    }
                    if ($date[0] == 'Сегодня' || $date[0] == 'Вчера') {
                        $date = str_replace($months_name, $months_number, $date);
                        $md = explode('-', $date[0]);
                        $hs = explode(':', $date[1]);

                        $date = Carbon::create($md[0], $md[1], $md[2], $hs[0], $hs[1])->toDateTimeString();
                    }

                    /**
                     *
                     * Search id of news.
                     *
                     */

                    $news = explode('_', $node->filter(' a .views ')->attr('id'));
                    $news_id = $news[5];

                    /**
                     *
                     * Check if id exist in DB.
                     *
                     */

                    $news = DB::table('news')->where('news_id', $news_id)->value('id');

                    if ($news == null){

                        /**
                         *
                         * Request to receive HTML of each news.
                         *
                         */

                        $client = new Client();
                        $crawler = $client->request('GET', "https://www.segodnya.ua" . $node->filter('a')->attr('href'));

                        /**
                         *
                         * Search and record tag.
                         *
                         */

                        $tags = trim($crawler->filter('div.tag ')->text());
                        $tags = preg_split('/\s+/', $tags);
                        $tags = implode(' ', $tags);

                        /**
                         *
                         * Search and record a number of views.
                         *
                         */

                        $cur_news = "https://www.segodnya.ua/exec/ajax/sunsite.php?article=$news_id&articles[$news_id]=$news_id";

                        $guzzle = new Guzzle();
                        $body = $guzzle->get($cur_news)->getBody();
                        $body = json_decode($body);

                        $views = $body->articleviews->{$news_id};

                        /**
                         *
                         * Create a new record .
                         *
                         */

                        $data = [
                            'news_id' => $news_id,
                            'link' => 'https://www.segodnya.ua' . $node->filter('a')->attr('href'),
                            'title' => $node->filter('h3')->text('text'),
                            'date' => $date,
                            'tags' => $tags,
                            'views' => $views,
                        ];

                        News::create($data);

                    }
                }
            });
        }
    }

    /**
     * The method that parses the number of pages .
     *
     * @method
     */

    public function parserPages()
    {
        $client = new Client();
        $crawler = $client->request('GET', $this->link);

        $this->urls = $crawler->filter('.pages > li')->each(function ($node){

            return 'https://www.segodnya.ua'.$node->filter('a')->attr('href');

        });

        return $this->urls;
    }

}

