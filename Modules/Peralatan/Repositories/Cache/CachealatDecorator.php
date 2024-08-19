<?php

namespace Modules\Peralatan\Repositories\Cache;

use Modules\Peralatan\Repositories\AlatRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheAlatDecorator extends BaseCacheDecorator implements AlatRepository
{
    public function __construct(AlatRepository $alat)
    {
        parent::__construct();
        $this->entityName = 'peralatan.alats';
        $this->repository = $alat;
    }
}
