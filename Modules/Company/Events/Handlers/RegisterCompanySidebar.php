<?php

namespace Modules\Company\Events\Handlers;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Events\BuildingSidebar;
use Modules\User\Contracts\Authentication;

class RegisterCompanySidebar implements \Maatwebsite\Sidebar\SidebarExtender
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
            $group->item(trans('company::companies.title.companies'), function (Item $item) {
                $item->icon('fa fa-building');
                $item->weight(10);
                $item->authorize(
                     /* append */
                    $this->auth->hasAccess('company.companies.index') or
                    $this->auth->hasAccess('company.paketberlangganans.index') or
                    $this->auth->hasAccess('company.biaya_kabel.index') or
                    $this->auth->hasAccess('company.slot_instalasi.index') or 
                    $this->auth->hasAccess('company.companies.detail')

                );
                $item->item(trans('company::companies.title.profile'), function (Item $item) {
                    $item->weight(0);
                    $item->route('admin.company.profile.index');
                    $item->authorize(
                        $this->auth->hasAccess('company.companies.detail')
                    );
                });
                $item->item(trans('company::companies.list resource'), function (Item $item) {
                    $item->weight(0);
                    $item->route('admin.company.company.index');
                    $item->authorize(
                        $this->auth->hasAccess('company.companies.index')
                    );
                });
                $item->item(trans('company::paketberlangganans.title.paketberlangganans'), function (Item $item) {
                    $item->weight(0);
                    $item->route('admin.company.paketberlangganan.index');
                    $item->authorize(
                        $this->auth->hasAccess('company.paketberlangganans.index')
                    );
                });
                 $item->item(trans('company::biaya_kabel.index resource'), function (Item $item) {
                    $item->weight(0);
                    $item->route('admin.company.biayakabel.index');
                    $item->authorize(
                        $this->auth->hasAccess('company.biaya_kabel.index')
                    );
                });
                $item->item(trans('company::slot_instalasi.index resource'), function (Item $item) {
                    $item->weight(0);
                    $item->route('admin.company.slotinstalasi.index');
                    $item->authorize(
                        $this->auth->hasAccess('company.slot_instalasi.index')
                    );
                });
                
            });
        });

        return $menu;
    }
}
