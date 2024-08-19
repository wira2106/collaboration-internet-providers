<?php

namespace Modules\Peralatan\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Events\BuildingSidebar;
use Modules\User\Contracts\Authentication;

class RegisterPeralatanSidebar implements \Maatwebsite\Sidebar\SidebarExtender
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
            $group->item(trans('peralatan::peralatans.title.peralatans'), function (Item $item) {
                $item->icon('fa fa-wrench');
                $item->weight(100);
                $item->authorize(
                    $this->auth->hasAccess('peralatan.alats.index') or
                    $this->auth->hasAccess('peralatan.perangkats.index') or
                    $this->auth->hasAccess('peralatan.barangs.index')
                );
                $item->item(trans('peralatan::alats.title.alats'), function (Item $item) {
                    $item->weight(0);
                    $item->append('admin.peralatan.alat.create');
                    $item->route('admin.peralatan.alat.index');
                    $item->authorize(
                        $this->auth->hasAccess('peralatan.alats.index')
                    );
                });
                $item->item(trans('peralatan::perangkats.title.perangkats'), function (Item $item) {
                    $item->weight(0);
                    $item->append('admin.peralatan.perangkat.create');
                    $item->route('admin.peralatan.perangkat.index');
                    $item->authorize(
                        $this->auth->hasAccess('peralatan.perangkats.index')
                    );
                });
                // $item->item(trans('peralatan::barangs.title.barangs'), function (Item $item) {
                //     $item->weight(0);
                //     $item->append('admin.peralatan.barang.create');
                //     $item->route('admin.peralatan.barang.index');
                //     $item->authorize(
                //         $this->auth->hasAccess('peralatan.barangs.index')
                //     );
                // });     
// append




            });
        });

        return $menu;
    }
}
