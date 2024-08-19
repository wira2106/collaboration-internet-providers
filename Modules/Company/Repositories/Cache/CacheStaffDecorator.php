<?php

namespace Modules\Company\Repositories\Cache;

use Modules\Company\Repositories\StaffRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheStaffDecorator extends BaseCacheDecorator implements StaffRepository
{
    public function __construct(StaffRepository $staff)
    {
        parent::__construct();
        $this->entityName = 'company.staff';
        $this->repository = $staff;
    }
}
