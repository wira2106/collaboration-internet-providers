<?php

namespace Modules\Pelanggan\Repositories\Cache;

use Modules\Pelanggan\Repositories\ActivationRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheActivationDecorator extends BaseCacheDecorator implements ActivationRepository
{
    public function __construct(ActivationRepository $activation)
    {
        parent::__construct();
        $this->entityName = 'pelanggan.activations';
        $this->repository = $activation;
    }
}
