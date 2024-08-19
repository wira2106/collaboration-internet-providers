<?php

namespace Modules\Presale\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Modules\Presale\Entities\PresaleClass;

class ListPresaleTransformer extends Resource
{
    private $presale_class;
    private $icon_name_status = ['','','_active', '_exist', '_uncover', '_minat_tidak_tercover', '_blacklist', '_vip'];

    private $hasAllAccessCompanies;

    public function __construct($resource)
    {
        $this->hasAllAccessCompanies = Auth::user()->hasAllAccessCompanies();
        $this->presale_class = PresaleClass::where('deleted', 0)->get()->toArray();
        parent::__construct($resource);
    }
    
    public function toArray($request)
    {
        $gangNomor = "";
        if ($this->nama_gang != null && $this->nama_gang != "") {
            $gangNomor = "Gg. <span class='span-gang'>".$this->nama_gang."</span>";
        }
        if ($this->no_rumah != null && $this->no_rumah != "") {
        if ($gangNomor != "") {
            $gangNomor .= " ";
        }
            $gangNomor .= "No. <span class='span-nomor'>".$this->no_rumah."</span>";
        }
        if ($gangNomor != "") {
            $gangNomor = "(".$gangNomor.")";
        }

        return [
            'presale_id' => $this->presale_id,
            'icon' => $this->getIconUrl($this->class_id, $this->status_id),
            'gang_nomor' => $gangNomor,
            'class_name' => $this->className(), 
            'created_at' => $this->created_at,
            'address' => $this->address,
        ];
    }

    private function className() {
        if($this->hasAllAccessCompanies) {
            return $this->class_name;
        }
        return $this->hasAccess() ? $this->class_name : '*****';
    }

    private function getIconUrl($classId, $statusId) {
        if(!$this->hasAllAccessCompanies) {
            if(!$this->hasAccess()) {
                return url('/modules/presale/img/pengajuan.ico');
            }
        }
        
        $icon = $this->searchIconNameById($classId);
        $status = $this->icon_name_status[$statusId ? $statusId : 0];
        return url("/modules/presale/img/icon_marker/$icon"."$status.ico");
    }

    private function searchIconNameById($classId) {
        $key = array_search($classId, array_column($this->presale_class, 'class_id'));
        if($key !== false) {
            return $this->presale_class[$key]['icon'];
        }
        return null;
    }

    private function hasAccess() {
        return !($this->active_presale_id === null);
    }
}
