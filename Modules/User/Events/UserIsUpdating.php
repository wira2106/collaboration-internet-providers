<?php

namespace Modules\User\Events;

use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;
use Modules\Media\Contracts\StoringMedia;
use Modules\User\Entities\UserInterface;

final class UserIsUpdating extends AbstractEntityHook implements EntityIsChanging, StoringMedia
{

    /**
     * @var UserInterface
     */
    private $user;
    private $data;


    public function __construct(UserInterface $user, array $data)
    {
        $this->user = $user;
        $this->data = $data;
        parent::__construct($data);
    }

    public function getEntity() {
        return $this->user;
    }

    public function getSubmissionData()
    {
        return $this->data;
    }

    /**
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }
}
