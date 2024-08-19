<?php

namespace Modules\Analytic\Repositories\Cache;

use Modules\Analytic\Repositories\AnalyticRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheAnalyticDecorator extends BaseCacheDecorator implements AnalyticRepository
{
    public function __construct(AnalyticRepository $analytic)
    {
        parent::__construct();
        $this->entityName = 'analytic.analytics';
        $this->repository = $analytic;
    }
}
