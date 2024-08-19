<?php

namespace Modules\Wilayah\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Events\BuildingSidebar;
use Modules\User\Contracts\Authentication;
use Auth;

class RegisterWilayahSidebar implements \Maatwebsite\Sidebar\SidebarExtender
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
            $group->item(trans('wilayah::wilayahs.title.wilayahs'), function (Item $item) {
                $item->icon('fa fa-map');
                $item->weight(20);
                $item->authorize(
                     /* append */
                     $this->auth->hasAccess('wilayah.wilayahs.index') or
                     $this->auth->hasAccess('wilayah.pengajuans.index')
                );

                $item->item(trans('wilayah::wilayahs.title.wilayahs'), function (Item $item) {
                    $item->weight(3);
                    $item->route('admin.wilayah.wilayah.index');

                    
                    $item->authorize(
                        $this->auth->hasAccess('wilayah.wilayahs.index')
                    );
                });

                $item->item(trans('wilayah::pengajuans.title.pengajuans'), function (Item $item) {
                    $item->weight(3);
                    if (!Auth::User()->hasRoleName('Admin OSP')) {
                        $item->append('admin.wilayah.pengajuan.create');                        
                    }
                    $item->route('admin.wilayah.pengajuan.index');
                    $item->authorize(
                        $this->auth->hasAccess('wilayah.pengajuans.index')
                    );
                });
            });
        });
        return $menu;
    }
}
