<?php

namespace Modules\Dashboard\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Sidebar\AbstractAdminSidebar;

class RegisterDashboardSidebar extends AbstractAdminSidebar
{
    /**
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group(trans('core::sidebar.menu'), function (Group $group) {
            $group->item(trans('dashboard::dashboard.name'), function (Item $item) {
                $item->icon('fa fa-home');
                $item->weight(0);                
                $item->route('dashboard.index');
                $item->isActiveWhen(route('dashboard.index', null, false));
                $item->authorize(
                    $this->auth->hasAccess('dashboard.index')
                );
            });
        });

        return $menu;
    }
}