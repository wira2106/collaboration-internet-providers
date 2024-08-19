<?php

namespace Modules\Saldo\Repositories\Cache;

use Modules\Saldo\Repositories\withdrawRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CachewithdrawDecorator extends BaseCacheDecorator implements withdrawRepository
{
    public function __construct(withdrawRepository $withdraw)
    {
        parent::__construct();
        $this->entityName = 'saldo.withdraws';
        $this->repository = $withdraw;
    }
}
