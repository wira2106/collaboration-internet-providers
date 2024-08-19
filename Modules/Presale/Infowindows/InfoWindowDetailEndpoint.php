<?php

namespace Modules\Presale\Infowindows;

use Illuminate\Support\Facades\Auth;

trait InfoWindowDetailEndpoint
{
    public function html()
    {
        $wilayah = $this->wilayah();
        $totalPresale = $this->totalPresale();
        $nama_end_point = isset($this->nama_end_point) ? $this->nama_end_point : null;
        $lat_end_point = isset($this->lat_end_point) ? $this->lat_end_point : null;
        $lon_end_point = isset($this->lon_end_point) ? $this->lon_end_point : null;
        $province = isset($wilayah->province) ? $wilayah->province : null;
        $regency = isset($wilayah->regency) ? $wilayah->regency : null;
        $district = isset($wilayah->district) ? $wilayah->district : null;
        $village = isset($wilayah->village) ? $wilayah->village : null;

        $html = "<div >
                        <table class='table-informasi'>
                            <tr><td><b>Nama</b></td><td>:</td><td>$nama_end_point</td></tr>
                            <tr><td><b>Lat</b></td><td>:</td><td>$lat_end_point</td></tr>
                            <tr><td><b>Long</b></td><td>:</td><td> $lon_end_point</td></tr>
                            <tr><td><b>Provinsi</b></td><td>:</td><td>$province</td></tr>
                            <tr><td><b>Kabupaten</b></td><td>:</td><td>$regency</td></tr>
                            <tr><td><b>Kecamatan</b></td><td>:</td><td>$district</td></tr>
                            <tr><td><b>Kelurahan</b></td><td>:</td><td>$village</td></tr>
                        </table>
                        <br>";


        // Button
        $html .= "<div class='btn-edit-marker-odp d-flex justify-content-between'>";

        if ($this->settlement_at == null && (!Auth::user()->hasRoleName('Admin ISP'))) {
            $html .= "<button type='button' onclick='pilihEndpoint($this->end_point_id)' class='btn-action-marker btn btn-info btn-sm'><i class='fa fa-check'></i></button>";
        }

        $html .= "<button type='button' onclick='editLokasiMarker(this)' data-id='$this->end_point_id' data-type='1'  class='btn-action-marker btn btn-primary btn-sm'><i class='fa fa-arrows-alt'></i></button>";

        $html .= "<button type='button' onclick='editDataMarker($this->end_point_id,1)' class='btn-action-marker btn btn-warning btn-sm'><i class='fa fa-edit'></i></button>";

        $html .= "<button type='button' id='btnHapusEndpoint' onclick='hapusDataMarker($this->end_point_id,1)' class='btn-action-marker btn btn-danger btn-sm'><i class='fa fa-trash'></i></button>";



        $html .= "<div style='position:relative;display:inline-block'>
                        <button type='button' onclick='showInterkoneksiPresale($this->end_point_id,1)' class='btn-action-marker btn btn-primary btn-sm'><i class='fa fa-home'></i></button>
                        <span class='bg-white text-primary span-jumlah-rumah'>$totalPresale</span>
                    </div>";


        $html .= "</div>";
        // End Button
        $html .= "</div>";
        //End Container
        return trim(preg_replace('/\s+/', ' ', $html));
    }
}
