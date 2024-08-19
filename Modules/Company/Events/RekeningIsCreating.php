<?php

namespace Modules\Company\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Company\Entities\BankAccount;
use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;

class RekeningIsCreating extends AbstractEntityHook implements EntityIsChanging
{
    use SerializesModels;

    private $rekening;
    private $data;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(BankAccount $rekening, array $data)
    {
        $this->rekening = $rekening;
        parent::__construct($data);
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
