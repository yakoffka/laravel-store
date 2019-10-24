<?php

namespace App\Observers;

use App\Comment;

// Eloquent models fire several events, allowing you to hook into the following points in a model's lifecycle: 
//      retrieved : after a record has been retrieved.
//      creating : before a record has been created.
//      created : after a record has been created.
//      updating : before a record is updated.
//      updated : after a record has been updated.
//      saving : before a record is saved (either created or updated).
//      saved : after a record has been saved (either created or updated).
//      deleting : before a record is deleted or soft-deleted.
//      deleted : after a record has been deleted or soft-deleted.
//      restoring : before a soft-deleted record is going to be restored.
//      restored : after a soft-deleted record has been restored.

class CommentObserver
{
    /**
     * Handle the comment "saving" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function saving(Comment $comment)
    {
        $comment->body = str_replace(["\r\n", "\r", "\n"], '<br>', $comment->body);
    }

    /**
     * Handle the comment "saved" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function saved(Comment $comment)
    {
        $comment->createCustomevent();
        $comment->sendEmailNotification();
        // if ( $message ) {session()->flash('message', $message);}
    }

    /**
     * Handle the comment "creating" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function creating(Comment $comment)
    {
        // dd(__METHOD__);
    }

    /**
     * Handle the comment "created" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function created(Comment $comment)
    {
        // dd(__METHOD__);
    }

    /**
     * Handle the comment "updated" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function updated(Comment $comment)
    {
        // dd(__METHOD__);
    }

    /**
     * Handle the comment "deleted" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function deleted(Comment $comment)
    {
        // dd(__METHOD__);
    }

    /**
     * Handle the comment "restored" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function restored(Comment $comment)
    {
        // dd(__METHOD__);
    }

    /**
     * Handle the comment "force deleted" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function forceDeleted(Comment $comment)
    {
        // dd(__METHOD__);
    }
}
