<?php

namespace Modules\Presale\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class EndpointLocationTransformer extends Resource
{
    public function toArray($request)
    {
        $disable = $this->settlement_at ? false : true;
        
        switch ($this->tipe) {
            case 'JB':
                if($disable) {
                    $icon = "/modules/presale/img/jb-marker-disable.ico";
                } else {
                    $icon = "/modules/presale/img/jb-marker.ico";
                }

                $icon = url($icon);
                break;
            
            case 'Fixing Slack':
                if($disable) {
                    $icon = "/modules/presale/img/fixing-slack-marker-disable.ico";
                } else {
                    $icon = "/modules/presale/img/fixing-slack-marker.ico";
                }

                $icon = url($icon);
                break;
            
            default:
                if($disable) {
                    $icon = "/modules/presale/img/odp-marker-disable.ico";
                } else {
                    $icon = "/modules/presale/img/odp-marker.ico";
                }

                $icon = url($icon);
                break;
        }
        return [
            'name' => $this->nama_end_point,
            'end_point_id' => $this->end_point_id,
            'tipe' => $this->tipe,
            'icon' => $icon,
            'position' => [
                'lat' => $this->lat_end_point,
                'lng' => $this->lon_end_point,
            ],
            'visibility' => true,
            'disable' => $disable
        ];
    }
}
