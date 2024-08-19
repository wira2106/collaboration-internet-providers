<?php

namespace Modules\Presale\Repositories\Cache;

use Modules\Presale\Repositories\presaleRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CachepresaleDecorator extends BaseCacheDecorator implements presaleRepository
{
    public function __construct(presaleRepository $presale)
    {
        parent::__construct();
        $this->entityName = 'presale.presales';
        $this->repository = $presale;
    }
}
