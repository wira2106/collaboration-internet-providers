<?php

namespace Modules\Analytic\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Events\BuildingSidebar;
use Modules\User\Contracts\Authentication;

class RegisterAnalyticSidebar implements \Maatwebsite\Sidebar\SidebarExtender
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
            $group->item(trans('analytic::analytics.title.analytics'), function (Item $item) {
                $item->icon('fa fa-copy');
                $item->weight(80);
                $item->authorize(
                    $this->auth->hasAccess('analytic.analytics.index')
                );
                $item->item(trans('pelanggan::pelanggans.title.pelanggans'), function (Item $item) {
                    $item->weight(0);
                    $item->route('admin.analytic.pelanggan.index');
                });
            });
        });
        return $menu;
    }
}