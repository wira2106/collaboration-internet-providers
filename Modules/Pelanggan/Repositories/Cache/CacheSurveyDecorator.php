<?php

namespace Modules\Pelanggan\Repositories\Cache;

use Modules\Pelanggan\Repositories\SurveyRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheSurveyDecorator extends BaseCacheDecorator implements SurveyRepository
{
    public function __construct(SurveyRepository $survey)
    {
        parent::__construct();
        $this->entityName = 'pelanggan.surveys';
        $this->repository = $survey;
    }
}
