<?php

namespace Modules\Pelanggan\Repositories\Cache;

use Modules\Pelanggan\Repositories\SalesOrderRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheSalesOrderDecorator extends BaseCacheDecorator implements SalesOrderRepository
{
    public function __construct(SalesOrderRepository $salesorder)
    {
        parent::__construct();
        $this->entityName = 'pelanggan.salesorders';
        $this->repository = $salesorder;
    }
}
