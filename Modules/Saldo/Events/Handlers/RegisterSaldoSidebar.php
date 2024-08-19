<?php

namespace Modules\Saldo\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Events\BuildingSidebar;
use Modules\User\Contracts\Authentication;

class RegisterSaldoSidebar implements \Maatwebsite\Sidebar\SidebarExtender
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
            $group->item(trans('saldo::saldos.title.saldo'), function (Item $item) {
                $item->icon('fa fa-dollar-sign');
                $item->weight(60);
                $item->authorize(
                    $this->auth->hasAccess('saldo.topups') or 
                    $this->auth->hasAccess('saldo.withdraws') or
                    $this->auth->hasAccess('saldo.mutasi')
                );

                $item->item(trans('saldo::saldos.title.topup'), function (Item $item) {
                    $item->weight(0);
                    $item->route('admin.saldo.topup.index');
                    $item->authorize(
                        $this->auth->hasAccess('saldo.topups')
                    );
    
                });

                $item->item(trans('saldo::saldos.title.withdraw'), function (Item $item) {
                    $item->weight(0);
                    $item->route('admin.saldo.withdraw.index');
                    $item->authorize(
                        $this->auth->hasAccess('saldo.withdraws')
                    );
    
                });

                 $item->item(trans('saldo::saldos.title.mutasi'), function (Item $item) {
                    $item->weight(0);
                    $item->route('admin.saldo.mutasi');
                    $item->authorize(
                        $this->auth->hasAccess('saldo.mutasi')
                    );
    
                });

            });
        });
        return $menu;
    }
}
