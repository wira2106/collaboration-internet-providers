<?php

namespace Modules\Company\Repositories\Cache;

use Modules\Company\Repositories\CompanyRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheCompanyDecorator extends BaseCacheDecorator implements CompanyRepository
{
    public function __construct(CompanyRepository $company)
    {
        parent::__construct();
        $this->entityName = 'company.companies';
        $this->repository = $company;
    }
}
