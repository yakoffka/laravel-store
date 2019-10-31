<?php

namespace App\Observers;

use App\Comment;

class CommentObserver
{
    /**
     * Handle the comment "creating" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function creating(Comment $comment)
    {
        info(__METHOD__);
        $comment->body = str_replace(["\r\n", "\r", "\n"], '<br>', $comment->body);
    }

    /**
     * Handle the comment "created" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function created(Comment $comment)
    {
        info(__METHOD__);
        $comment->name = $comment->id;
        $comment->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the comment "updating" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function updating(Comment $comment)
    {
        info(__METHOD__);
        $comment->body = str_replace(["\r\n", "\r", "\n"], '<br>', $comment->body);
    }

    /**
     * Handle the comment "updated" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function updated(Comment $comment)
    {
        info(__METHOD__);
        $comment->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the comment "deleting" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function deleting(Comment $comment)
    {
        info(__METHOD__);
    }

    /**
     * Handle the comment "deleted" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function deleted(Comment $comment)
    {
        info(__METHOD__);
        $comment->createCustomevent()->sendEmailNotification()->setFlashMess();
    }
}
