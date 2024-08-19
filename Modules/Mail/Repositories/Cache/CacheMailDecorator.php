<?php

namespace Modules\Mail\Repositories\Cache;

use Modules\Mail\Repositories\MailRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheMailDecorator extends BaseCacheDecorator implements MailRepository
{
    public function __construct(MailRepository $mail)
    {
        parent::__construct();
        $this->entityName = 'mail.mails';
        $this->repository = $mail;
    }
}
