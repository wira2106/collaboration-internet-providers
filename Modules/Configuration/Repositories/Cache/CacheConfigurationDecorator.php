<?php

namespace Modules\Configuration\Repositories\Cache;

use Modules\Configuration\Repositories\ConfigurationRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheConfigurationDecorator extends BaseCacheDecorator implements ConfigurationRepository
{
    public function __construct(ConfigurationRepository $configuration)
    {
        parent::__construct();
        $this->entityName = 'configuration.configurations';
        $this->repository = $configuration;
    }
}
