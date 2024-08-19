<?php

namespace Modules\Saldo\Repositories\Cache;

use Modules\Saldo\Repositories\topupRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CachetopupDecorator extends BaseCacheDecorator implements topupRepository
{
    public function __construct(topupRepository $topup)
    {
        parent::__construct();
        $this->entityName = 'saldo.topups';
        $this->repository = $topup;
    }
}
