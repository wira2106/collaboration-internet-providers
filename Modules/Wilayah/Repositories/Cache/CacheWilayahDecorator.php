<?php

namespace Modules\Wilayah\Repositories\Cache;

use Modules\Wilayah\Repositories\WilayahRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheWilayahDecorator extends BaseCacheDecorator implements WilayahRepository
{
    public function __construct(WilayahRepository $wilayah)
    {
        parent::__construct();
        $this->entityName = 'wilayah.wilayahs';
        $this->repository = $wilayah;
    }
}
