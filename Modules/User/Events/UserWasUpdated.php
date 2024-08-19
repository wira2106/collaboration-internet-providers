<?php

namespace Modules\User\Events;

use Modules\Media\Contracts\StoringMedia;

class UserWasUpdated implements StoringMedia
{
    public $user;
    private $data;

    public function __construct($user, $data)
    {
        $this->user = $user;
        $this->data = $data;
    }

    public function getEntity()
    {
        return $this->user;
    }

    public function getSubmissionData()
    {
        return $this->data;
    }
}
