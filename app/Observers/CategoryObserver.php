<?php

namespace App\Observers;

use App\Category;

class CategoryObserver
{
    /**
     * Handle the comment "creating" event.
     *
     * @param  \App\Category  $category
     * @return void
     */
    public function creating(Category $category)
    {
        info(__METHOD__);
        $category->setUuid()->setTitle()->setSlug()->attachSingleImage()->setCreator();
    }

    /**
     * Handle the comment "created" event.
     *
     * @param  \App\Category  $category
     * @return void
     */
    public function created(Category $category)
    {
        info(__METHOD__);
        $category->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the comment "updating" event.
     *
     * @param  \App\Category  $category
     * @return void
     */
    public function updating(Category $category)
    {
        info(__METHOD__);
        $category->setTitle()->setSlug()->attachSingleImage()->setEditor();
    }

    /**
     * Handle the comment "updated" event.
     *
     * @param  \App\Category  $category
     * @return void
     */
    public function updated(Category $category)
    {
        info(__METHOD__);
        $category->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the comment "deleting" event.
     *
     * @param  \App\Category  $category
     * @return void
     */
    public function deleting(Category $category)
    {
        info(__METHOD__);
    }

    /**
     * Handle the comment "deleted" event.
     *
     * @param  \App\Category  $category
     * @return void
     */
    public function deleted(Category $category)
    {
        info(__METHOD__);
        $category->createCustomevent()->sendEmailNotification()->setFlashMess();
    }
}
