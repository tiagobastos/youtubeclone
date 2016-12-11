<?php 

namespace App\Http\ViewComposers;

use Auth;
use Illuminate\View\View;

/**
 * NavigationComposer will share data in all instances of the view, through ComposerServiceProvider.
 * The view must be loaded 
 *  
 */
class NavigationComposer
{
    public function compose(View $view)
    {
        if (!Auth::check()) {
            return;
        }

        $view->with('channel', Auth::user()->channel->first());
    }
}