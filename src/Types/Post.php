<?php

namespace Xparser\Types;

use Xparser\QueryPath\QueryPath;

class Post extends AbstractType
{
    
    public function save($data)
    {
        $post = Post::firstOrCreate(['source' => $data->get('source')]);
        
        /** @var Post $post */
        $post->site_id = $this->siteConfig->siteId;
        $post->url = $data->get('url');
        $post->title = $data->get('title');
        $post->source = $data->get('source');
        $post->description = $data->get('description');
        $post->comments_count = $data->get('comments_count');
        $post->image =$data->get('image');
        $post->author_name = $data->get('author_name');
        $post->author_link = $data->get('author_link');
        $post->date = $data->get('date');
        $post->authority = $data->get('authority');

        $this->postProcessor($post);
        
        return $post->save();
    }

    public function all()
    {
        return QueryPath::withHTML($this->html, '#content')->get();
    }
}
