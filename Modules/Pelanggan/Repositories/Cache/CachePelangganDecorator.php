<?php

namespace Modules\Pelanggan\Repositories\Cache;

use Modules\Pelanggan\Repositories\PelangganRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CachePelangganDecorator extends BaseCacheDecorator implements PelangganRepository
{
    public function __construct(PelangganRepository $pelanggan)
    {
        parent::__construct();
        $this->entityName = 'pelanggan.pelanggans';
        $this->repository = $pelanggan;
    }
}
