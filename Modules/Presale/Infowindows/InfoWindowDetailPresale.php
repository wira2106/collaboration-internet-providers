<?php

namespace Modules\Presale\Infowindows;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Presale\Entities\PresaleClass;
use Modules\Presale\Entities\StatusPresale;

trait InfoWindowDetailPresale
{
    private $status_name = '';
    private $class_name = '';
    public function html()
    {
        $this->status_name = StatusPresale::find($this->status_id)->name;
        $this->class_name = PresaleClass::find($this->class_id)->class_name;

        $html = "<div id='infoWindowRumah$this->presale_id'>";

        $html .= $this->tableInformation();

        $html .= "<br>";

        $html .= $this->btnAction();

        $html .= "</div>";

        return trim(preg_replace('/\s+/', ' ', $html));
    }

    private function tableInformation()
    {
        $classBtnStatusRumah = $this->status_id != 2 ? 'btn-default' : 'btn-primary';
        $functionBtnStatusRumah = '';
        if (Auth::user()->getRoleName() !== "Admin ISP" && $this->hasAccessPresale()) {
            $functionBtnStatusRumah = $this->status_id != 2 ? "onclick='setStatusRumah($this->presale_id)'" : '';
        }


        $keterangan = $this->keterangan ? "<tr><td valign='top'><b>keterangan</b></td><td valign='top'>:</td><td valign='top'>$this->keterangan</td></tr>" : '';

        $html = "<table class='table-informasi'>
                    <tr>
                        <td colspan='3' style='padding-bottom:5px;'><button type='button' class='btn $classBtnStatusRumah btn-sm' style='width:100%' $functionBtnStatusRumah>Status - $this->status_name</button>
                        </td>
                    </tr>                
                ";

        if ($this->hasAccessPresale()) {
            $html .= "
                <tr>
                    <td valign='top'>
                        <b>Site ID</b>
                    </td>
                    <td valign='top'>:</td>
                    <td valign='top'>
                        <b>$this->site_id</b> 
                        <a class='text-primary' href='#!' onclick='copyToClipboard(\"$this->site_id\")'>copy</a>
                    </td>
                </tr>
                <tr>
                    <td valign='top'>
                        <b>Tipe</b>
                    </td>
                    <td valign='top'>:</td>
                    <td valign='top'>
                        $this->class_name <b>$this->nama_gang</b>
                    </td>
                </tr>
            ";
        }


        $html .= "								  
                    <tr>
                        <td valign='top'>
                            <b>Alamat</b>
                        </td>
                        <td valign='top'>:</td>
                        <td valign='top'>
                            $this->address
                        </td>
                    </tr>
                    <tr>
                        <td valign='top'>
                            <b>Jarak ke endpoint</b>
                        </td>
                        <td valign='top'>:</td>
                        <td valign='top'>$this->panjang_kabel M</td>
                    </tr>
                    $keterangan
                                    
                </table>";
        return $html;
    }

    private function btnAction()
    {
        $html = "<div class='btn-edit-marker-odp d-flex justify-content-center'>";
        $userCanEdit = Auth::user()->hasAccess('presale.presales.edit');
        if ($userCanEdit && $this->hasAccessPresale()) {
            $html .= "<button type='button' onclick='editLokasiMarker(this)' data-id='$this->presale_id' data-type='2' class='btn btn-primary btn-sm btn-action-marker' style='margin-left:-3.3px;'><i class='fa fa-arrows-alt'></i></button>";
        }


        if ($userCanEdit && $this->hasAccessPresale()) {
            $html .= "<button type='button' onclick='editDataMarker($this->presale_id,2)' class='btn-action-marker btn btn-warning btn-sm'><i class='fa fa-edit'></i></button>";

            $html .= "<button type='button' onclick='hapusDataMarker($this->presale_id,2)' class='btn-action-marker btn btn-danger btn-sm'><i class='fa fa-trash'></i></button>";
        }



        if (Auth::user()->getRoleName() === "Admin ISP") {
            $html .= "<button type='button' onclick='showRoutes($this->presale_id)' class='btn btn-info btn-sm mx-1'><i class='fa fa-level-up-alt mr-1'></i>" . trans('presale::presales.jalur kabel') . "</button>
            ";
            $html .= "<el-button type='primary' icon='fa fa-shopping-cart' onclick=\"showFormPelanggan($this->presale_id)\" class='btn btn-primary btn-sm mx-1' style='margin-left:-3.3px;'><i class='fa fa-shopping-cart mr-1'> </i> " . trans('presale::presales.order') . "</el-button>";
        } else {
            $html .= "<button type='button' onclick='showRoutes($this->presale_id)' class='btn btn-primary btn-sm btn-action-marker'><i class='fa fa-level-up-alt'></i></button>
            ";
        }

        if (!$this->hasAccessPresale() && !Auth::user()->hasAllAccessCompanies() && Auth::user()->getRoleName() === "Admin OSP") {
            $html .= "
                <button type='button' onclick=\"addListConfirmation($this->presale_id)\" class='btn btn-info btn-sm btn-action-marker' style='margin-left:-3.3px;'><i class='fa fa-check'></i></button>
            ";
        }

        $html .= "</div>";

        return $html;
    }
}
