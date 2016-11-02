<?php


namespace SergeySetti\Xparser\Types;

use Xparser\Post;
use Xparser\QueryPath\QueryPath;
use Xparser\Types;

class Comment extends AbstractType
{

    public function save($data)
    {
        $comment = \Comment::firstOrCreate(['source' => $data->get('source')]);
        $comment = $comment->setRawAttributes($data->toArray());
        return $comment->save();
    }

    public function all()
    {
        return QueryPath::withHTML($this->html, '.comment_placeholder');
    }
}