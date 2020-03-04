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
        $comment->setAuthor()->setName()->transformBody();
    }

    /**
     * Handle the comment "created" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function created(Comment $comment)
    {
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
        $comment->transformBody();
    }

    /**
     * Handle the comment "updated" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function updated(Comment $comment)
    {
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
    }

    /**
     * Handle the comment "deleted" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function deleted(Comment $comment)
    {
        $comment->createCustomevent()->sendEmailNotification()->setFlashMess();
    }
}
