<?php


namespace Xparser\Types;


use Xparser\QueryPath\QueryPath;

class Comment extends AbstractType
{

    public function save($data)
    {
        $comment = Xparser\Comment::firstOrCreate(['source' => $data->get('source')]);
        $comment = $comment->setRawAttributes($data->except(['skip'])->toArray());
        /** @var Xparser\Comment $comment */
        
        return $comment->save();
    }

    public function all()
    {
        return QueryPath::withHTML($this->html, '.comment_placeholder');
    }
}