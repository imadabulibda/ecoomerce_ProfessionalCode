<?php

namespace App\Observers;


use App\Models\MainCategory;

class MainCategoryObserver
{
    /**
     * Handle the main category "created" event.
     *
     * @param MainCategory $mainCategory
     * @return void
     */
    public function created(MainCategory $mainCategory)
    {
        //
    }

    /**
     * Handle the main category "updated" event.
     *
     * @param MainCategory $mainCategory
     * @return void
     */
    public function updated(MainCategory $mainCategory)
    {
        // to make mainCategory active status based of Main Vendor active status
        $mainCategory->vendors()->update(['active' => $mainCategory->active]);
    }

    /**
     * Handle the main category "deleted" event.
     *
     * @param MainCategory $mainCategory
     * @return void
     */
    public function deleted(MainCategory $mainCategory)
    {
        $mainCategory->categories()->delete(); // categories() its actually a relationship that gets the translations
    }

    /**
     * Handle the main category "restored" event.
     *
     * @param MainCategory $mainCategory
     * @return void
     */
    public function restored(MainCategory $mainCategory)
    {
        //
    }

    /**
     * Handle the main category "force deleted" event.
     *
     * @param MainCategory $mainCategory
     * @return void
     */
    public function forceDeleted(MainCategory $mainCategory)
    {
        //
    }
}
