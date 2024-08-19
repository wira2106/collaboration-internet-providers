<?php

namespace Modules\Peralatan\Repositories\Cache;

use Modules\Peralatan\Repositories\PerangkatRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CachePerangkatDecorator extends BaseCacheDecorator implements PerangkatRepository
{
    public function __construct(PerangkatRepository $perangkat)
    {
        parent::__construct();
        $this->entityName = 'peralatan.perangkats';
        $this->repository = $perangkat;
    }
}
