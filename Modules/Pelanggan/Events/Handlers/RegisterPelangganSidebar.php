<?php

namespace Modules\Pelanggan\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Events\BuildingSidebar;
use Modules\User\Contracts\Authentication;

class RegisterPelangganSidebar implements \Maatwebsite\Sidebar\SidebarExtender
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
            $group->item(trans('pelanggan::pelanggans.title.pelanggans'), function (Item $item) {
                $item->icon('fa fa-user');
                $item->weight(40);
                $item->route('admin.pelanggan.pelanggans.index');
                $item->authorize(
                     /* append */
                     $this->auth->hasAccess('pelanggan.salesorders.index') or 
                     $this->auth->hasAccess('pelanggan.surveys.index') or 
                     $this->auth->hasAccess('pelanggan.installations.index') or
                     $this->auth->hasAccess('pelanggan.activations.index') or 
                     $this->auth->hasAccess('pelanggan.pelanggans.index')

                );
                // $item->item(trans('pelanggan::pelanggans.title.pelanggans'), function (Item $item) {
                //     $item->weight(0);
                //     $item->append('admin.pelanggan.pelanggan.create');
                //     $item->route('admin.pelanggan.pelanggan.index');
                //     $item->authorize(
                //         $this->auth->hasAccess('pelanggan.pelanggans.index')
                //     );
                // });
                // $item->item(trans('pelanggan::salesorders.title.salesorders'), function (Item $item) {
                //     $item->weight(0);
                //     $item->route('admin.pelanggan.salesorder.index');
                //     $item->authorize(
                //         $this->auth->hasAccess('pelanggan.salesorders.index')
                //     );
                // });
                // $item->item(trans('pelanggan::surveys.title.surveys'), function (Item $item) {
                //     $item->weight(0);
                //     $item->route('admin.pelanggan.survey.index');
                //     $item->authorize(
                //         $this->auth->hasAccess('pelanggan.surveys.index')
                //     );
                // });
                // $item->item(trans('pelanggan::installations.title.installations'), function (Item $item) {
                //     $item->weight(0);
                //     $item->route('admin.pelanggan.installation.index');
                //     $item->authorize(
                //         $this->auth->hasAccess('pelanggan.installations.index')
                //     );
                // });
                // $item->item(trans('pelanggan::activations.title.activations'), function (Item $item) {
                //     $item->weight(0);
                //     $item->route('admin.pelanggan.activation.index');
                //     $item->authorize(
                //         $this->auth->hasAccess('pelanggan.activations.index')
                //     );
                // });
                // append
            });
        });

        return $menu;
    }
}
