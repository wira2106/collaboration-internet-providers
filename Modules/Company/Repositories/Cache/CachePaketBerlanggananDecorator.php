<?php

namespace Modules\Company\Repositories\Cache;

use Modules\Company\Repositories\PaketBerlanggananRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CachePaketBerlanggananDecorator extends BaseCacheDecorator implements PaketBerlanggananRepository
{
    public function __construct(PaketBerlanggananRepository $paketberlangganan)
    {
        parent::__construct();
        $this->entityName = 'company.paketberlangganans';
        $this->repository = $paketberlangganan;
    }
}
