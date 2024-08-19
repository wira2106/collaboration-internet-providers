<?php

namespace Modules\Pelanggan\Repositories\Cache;

use Modules\Pelanggan\Repositories\InstallationRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheInstallationDecorator extends BaseCacheDecorator implements InstallationRepository
{
    public function __construct(InstallationRepository $installation)
    {
        parent::__construct();
        $this->entityName = 'pelanggan.installations';
        $this->repository = $installation;
    }
}
