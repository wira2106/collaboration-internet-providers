<?php

namespace Modules\Presale\Events\Handlers;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Events\BuildingSidebar;
use Modules\User\Contracts\Authentication;

class RegisterPresaleSidebar implements \Maatwebsite\Sidebar\SidebarExtender
{
    /**
     * @var Authentication
     */
    protected $auth;

    /**
     * @param Authentication $auth
     *
     * @internal param Guard $guard
     */
    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    public function handle(BuildingSidebar $sidebar)
    {
        $sidebar->add($this->extendWith($sidebar->getMenu()));
    }

    /**
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group(trans('core::sidebar.menu'), function (Group $group) {
            
            if(Auth::user()->hasRoleName('Admin ISP')) {
                $sidebarName = trans('presale::presales.title.order');
                $icon = "fa fa-shopping-cart";
            } else {
                $sidebarName = trans('presale::presales.title.sidebar');
                $icon = "fa fa-map-marker";
                
            }

            $group->item($sidebarName, function (Item $item) use ($icon)  {
                $item->icon($icon);
                $item->weight(30);
                $item->route('admin.presale.presale.index');
                $item->authorize(
                    $this->auth->hasAccess('presale.presales.index') or 
                    $this->auth->hasAccess('presale.endpoints.index')
                );
            });
        });
        return $menu;
    }
}
