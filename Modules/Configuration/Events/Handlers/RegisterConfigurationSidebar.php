<?php

namespace Modules\Configuration\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Events\BuildingSidebar;
use Modules\User\Contracts\Authentication;

class RegisterConfigurationSidebar implements \Maatwebsite\Sidebar\SidebarExtender
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
            $group->item(trans('configuration::configurations.title.configurations'), function (Item $item) {
                $item->icon('fa fa-cogs');
                $item->weight(90);
                $item->authorize(
                     $this->auth->hasAccess('configuration.configurations.index') or 
                     $this->auth->hasAccess('configuration.bank.index')
                );

                $item->item(trans('configuration::configurations.title.configurations'), function (Item $item) {
                    $item->weight(0);
                    $item->route('admin.configuration.configuration.index');
                    $item->authorize(
                        $this->auth->hasAccess('configuration.configurations.index')
                    );
                });

                $item->item(trans('configuration::bank.title.bank'), function (Item $item) {
                    $item->weight(0);
                    $item->route('admin.configuration.bank.index');
                    $item->authorize(
                        $this->auth->hasAccess('configuration.bank.index')
                    );
                });

            });
        });

        return $menu;
    }
}
