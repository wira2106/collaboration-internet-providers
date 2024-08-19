<?php

namespace Modules\Presale\Repositories\Cache;

use Modules\Presale\Repositories\endpointRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheendpointDecorator extends BaseCacheDecorator implements endpointRepository
{
    public function __construct(endpointRepository $endpoint)
    {
        parent::__construct();
        $this->entityName = 'presale.endpoints';
        $this->repository = $endpoint;
    }
}
