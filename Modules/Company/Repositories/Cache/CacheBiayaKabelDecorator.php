<?php

namespace Modules\Company\Repositories\Cache;

use Modules\Company\Repositories\BiayaKabelRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheBiayaKabelRepository extends BaseCacheDecorator implements BiayaKabelRepository
{
    public function __construct(BiayaKabelRepository $staff)
    {
        parent::__construct();
        $this->entityName = 'company.biayakabel';
        $this->repository = $staff;
    }
}
