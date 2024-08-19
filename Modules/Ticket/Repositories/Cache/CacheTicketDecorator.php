<?php

namespace Modules\Ticket\Repositories\Cache;

use Modules\Ticket\Repositories\TicketRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheTicketDecorator extends BaseCacheDecorator implements TicketRepository
{
    public function __construct(TicketRepository $ticket)
    {
        parent::__construct();
        $this->entityName = 'ticket.tickets';
        $this->repository = $ticket;
    }
}
