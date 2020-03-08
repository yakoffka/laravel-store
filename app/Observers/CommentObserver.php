<?php

namespace App\Observers;

use App\Comment;

class CommentObserver
{
    /**
     * Handle the comment "creating" event.
     *
     * @param Comment $comment
     * @return void
     */
    public function creating(Comment $comment): void
    {
        $comment->setAuthor()->setName()->transformBody();
    }

    /**
     * Handle the comment "created" event.
     *
     * @param Comment $comment
     * @return void
     */
    public function created(Comment $comment): void
    {
        $comment->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the comment "updating" event.
     *
     * @param Comment $comment
     * @return void
     */
    public function updating(Comment $comment): void
    {
        $comment->transformBody();
    }

    /**
     * Handle the comment "updated" event.
     *
     * @param Comment $comment
     * @return void
     */
    public function updated(Comment $comment): void
    {
        $comment->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the comment "deleting" event.
     *
     * @param Comment $comment
     * @return void
     */
    public function deleting(Comment $comment): void
    {
    }

    /**
     * Handle the comment "deleted" event.
     *
     * @param Comment $comment
     * @return void
     */
    public function deleted(Comment $comment): void
    {
        $comment->createCustomevent()->sendEmailNotification()->setFlashMess();
    }
}
