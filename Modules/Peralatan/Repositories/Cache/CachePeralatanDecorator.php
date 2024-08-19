<?php

namespace Modules\Peralatan\Repositories\Cache;

use Modules\Peralatan\Repositories\PeralatanRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CachePeralatanDecorator extends BaseCacheDecorator implements PeralatanRepository
{
    public function __construct(PeralatanRepository $peralatan)
    {
        parent::__construct();
        $this->entityName = 'peralatan.peralatans';
        $this->repository = $peralatan;
    }
}
