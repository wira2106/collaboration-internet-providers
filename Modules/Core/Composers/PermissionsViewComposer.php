<?php

namespace Modules\Core\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class PermissionsViewComposer
{
    public function compose(View $view)
    {
        $user   = Auth::User();
        // detail user
        $view->with('user_detail', $user);
        // user_permission
        $view->with('user_permission', $user->permissions());
        // roles user
        $view->with('user_roles', $user->getRoles()->first());
        // company user
        $view->with('user_company', $user->company());        

    }

    
}
