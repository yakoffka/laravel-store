<?php

namespace App\Observers;

use App\Category;

class CategoryObserver
{
    /**
     * Handle the comment "creating" event.
     *
     * @param Category $category
     * @return void
     */
    public function creating(Category $category)
    {
        $category->setUuid()->setTitle()->setSlug()->attachSingleImage()->setCreator();
    }

    /**
     * Handle the comment "created" event.
     *
     * @param Category $category
     * @return void
     */
    public function created(Category $category)
    {
        $category->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the comment "updating" event.
     *
     * @param Category $category
     * @return void
     */
    public function updating(Category $category)
    {
        $category->setTitle()->setSlug()->attachSingleImage()->setEditor();
    }

    /**
     * Handle the comment "updated" event.
     *
     * @param Category $category
     * @return void
     */
    public function updated(Category $category)
    {
        $category->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the comment "deleting" event.
     *
     * @param Category $category
     * @return void
     */
    public function deleting(Category $category)
    {
    }

    /**
     * Handle the comment "deleted" event.
     *
     * @param Category $category
     * @return void
     */
    public function deleted(Category $category)
    {
        $category->createCustomevent()->sendEmailNotification()->setFlashMess();
    }
}
