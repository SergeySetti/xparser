<?php


namespace SergeySetti\Xparser\Parsers\Configs;


use Carbon\Carbon;
use Xparser\Helpers\Date;
use Xparser\Parsers\Configs\Config;
use Xparser\Types\Comment;
use Xparser\Types\Post;

class NewsTwoRu extends Config implements ConfigInterface
{

    public $siteId = 1;
    public $html;
    /**
     * @var \QueryPath $qp
     */
    public $qp;

    public function fields()
    {
        return [
            Post::class => [
                'title' => function(){
                    $text = $this->qp->withHTML($this->html, 'h2')
                        ->text();
                    return trim($text);
                },
                'source' => function(){
                    $href = $this->qp->withHTML($this->html, 'h2 a')
                        ->attr('href');
                    return $href;
                },
                'description' => function(){
                    $description = $this->qp->withHTML($this->html, '.news_description')
                        ->text();
                    return trim($description);
                },
                'image' => function(){
                    return $this->qp->withHTML($this->html, '.news_image img')
                        ->attr('src');
                },
                'comments_count' => function(){
                    $comments = $this->qp->withHTML($this->html, '.comments_ico')
                        ->text();
                    return intval(preg_replace("/[^\d]+/iu", '', $comments));
                },
                'author_name' => function(){
                    return $this->qp->withHTML($this->html, '.news_author a')
                        ->eq(1)
                        ->text();
                },
                'author_link' => function(){
                    return $this->qp->withHTML($this->html, '.news_author a')
                        ->eq(1)
                        ->attr('href');
                },
                'date' => function(){
                    $dateNode = $this->qp->withHTML($this->html, '.news_author');
                    $dateNode->remove('a');
                    $result = $dateNode->text();
                    $result = trim(preg_replace("/Добавил   /", '', $result));
                    $result = Date::rus2eng($result);
                    try{
                        $date = Carbon::parse($result);
                    }catch(\Exception $e){
                        $date = Carbon::now();
                    }
                    return $date;
                },
                'authority' => function(){
                    $node = $this->qp->withHTML(
                        $this->html,
                        '.vote_class .vote_text_middle_big a'
                    )->text();

                    return intval($node);
                },
                'tags' => function(){
                    $tags = [];
                    $nodes = $this->qp->withHTML(
                        $this->html, '[rel="tag"]');

                    foreach($nodes as $tag) {
                        $tags[] = mb_strtolower($tag->text());
                    }

                    return $tags;
                },
            ],
            Comment::class => [
                'source' => function($node){
                    $url = $node->find('font:contains(url)')->parent()->attr('href');
                    return trim($url);
                },
                'authority' => function($node){
                        return intval($node->find('.comment_score')->text());
                    },
                'author_link' => function($node){
                        return $node->find('.UserLink')->attr('href');
                    },
                'author_name' => function($node){
                    return $node->find('.UserLink')->text();
                },
                'date' => function($node){
                    $date = trim($node->find('.misc')->text());
                    preg_match('/\d+ [а-яА-Я]+ \d+/um', $date, $matches);
                    $date = array_get($matches, 0);
                    if($date) {
                        try{
                            $date = $date = Carbon::parse(Date::rus2eng($date));
                        }catch(\Exception $e){
                            $date = Carbon::now();
                        }
                    }
                    return $date;
                },
                'body' => function($node){
                    $comment = trim($node->find('.comment_text')->text());
                    return $comment;
                },
            ]
        ];
    }

}
