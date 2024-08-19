<?php

namespace Modules\Ticket\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Events\BuildingSidebar;
use Modules\User\Contracts\Authentication;

class RegisterTicketSidebar implements \Maatwebsite\Sidebar\SidebarExtender
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
            $group->item(trans('ticket::tickets.title.tickets'), function (Item $item) {
                $item->icon('fa fa-clipboard-list ');
                $item->weight(70);
                // $item->route('admin.ticket.index');
                $item->authorize(
                     /* append */
                     $this->auth->hasAccess('ticket.tickets.index')
                );
                
                $item->item(trans('ticket::tickets.title.ticket sla'), function (Item $item) {
                    $item->weight(0);
                    // $item->icon('fa fa-users');
                    $item->route('admin.ticket.sla.index');
                });
    
                $item->item(trans('ticket::tickets.title.ticket suspend'), function (Item $item) {
                    $item->weight(0);
                    // $item->icon('fa fa-users');
                    $item->route('admin.ticket.suspend.index');
                });
                
            });
        });

        return $menu;
    }
}