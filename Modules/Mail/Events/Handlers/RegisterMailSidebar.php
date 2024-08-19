<?php

namespace Modules\Mail\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Events\BuildingSidebar;
use Modules\User\Contracts\Authentication;

class RegisterMailSidebar implements \Maatwebsite\Sidebar\SidebarExtender
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
//         $menu->group(trans('core::sidebar.content'), function (Group $group) {
//             $group->item(trans('mail::mails.title.mails'), function (Item $item) {
//                 $item->icon('fa fa-copy');
//                 $item->weight(10);
//                 $item->authorize(
//                      /* append */
//                 );
//                 $item->item(trans('mail::mails.title.mails'), function (Item $item) {
//                     $item->weight(0);
//                     $item->append('admin.mail.mail.create');
//                     $item->route('admin.mail.mail.index');
//                     $item->authorize(
//                         $this->auth->hasAccess('mail.mails.index')
//                     );
//                 });
// // append

//             });
//         });

        return $menu;
    }
}
