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
        // info($category->getDirty());
        $category
            ->setUuid()
            ->setTitle()
            ->setSlug()
            ->attachSingleImage()
            // ->setSeeable()
            // ->createCustomevent()
            // ->sendEmailNotification()
            ;
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
        // info($category->getDirty());
        $category
            // ->setSlug()
            // ->attachSingleImage()
            ->createCustomevent()
            ->sendEmailNotification()
            ;
        session()->flash('message', __('success_operation'));
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
        $category
            ->setSlug()
            ->attachSingleImage()
            // ->setSeeable()
            // ->setChildrenSeeable()
            // ->setSeeable()
            ;
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
        info($category->getDirty());
        $category->createCustomevent()->sendEmailNotification();
        session()->flash('message', __('success_operation'));

        // info($category);
        // info($category->seeable); 
        // info($category->getDirty()); 
        // info($category->getOriginal());
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
        $category->createCustomevent();
        // $category->sendEmailNotification(); 
        session()->flash('message', __('success_operation'));
    }
}
