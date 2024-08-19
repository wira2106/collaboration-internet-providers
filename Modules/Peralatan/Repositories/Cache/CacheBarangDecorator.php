<?php

namespace Modules\Peralatan\Repositories\Cache;

use Modules\Peralatan\Repositories\BarangRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheBarangDecorator extends BaseCacheDecorator implements BarangRepository
{
    public function __construct(BarangRepository $barang)
    {
        parent::__construct();
        $this->entityName = 'peralatan.barangs';
        $this->repository = $barang;
    }
}
