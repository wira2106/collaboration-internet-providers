<?php

namespace Modules\Presale\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;
use Modules\Presale\Entities\Endpoint;
use Modules\Presale\Entities\Presale;

class PresaleIsCreating extends AbstractEntityHook implements EntityIsChanging
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    private $presale;
    private $data;
    public function __construct(Presale $presale, array $data)
    {
        $this->presale = $presale;
        $this->data = $data;
        parent::__construct($data);
        if(!$this->presale->presale_id) $this->generateSSIDCode();
        if($presale->end_point_id != $this->getAttribute('end_point_id')) $this->generateSSIDCode();
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

    public function generateSSIDCode() {
        $urutan = 1;
        $queryGetID = Presale::where('end_point_id', $this->getAttribute('end_point_id'))->orderBy('site_id', 'desc')->first();
        if($queryGetID) {
        $urutan  = substr($queryGetID->site_id, -4); 
            (int)$urutan++;
        }
        $urutan  = str_pad($urutan, 4, "0", STR_PAD_LEFT);
        $namaodp = Endpoint::select('nama_end_point')->where('end_point_id',$this->getAttribute('end_point_id'))->first()->nama_end_point;
        $siteID = $namaodp.$urutan;
        $this->setAttributes([
            'site_id' => $siteID,
            'created_by' => Auth::user()->id
        ]);

    }
}
