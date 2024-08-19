<?php

return [
    "wilayah.wilayahs" => [
        "index" => "wilayah::wilayahs.list resource",
        "create" => "wilayah::wilayahs.create resource",
        "update" => "wilayah::wilayahs.update resource",
        "destroy" => "wilayah::wilayahs.destroy resource"
    ],
    "wilayah.pengajuans" => [
    	"index" => "wilayah::pengajuans.list resource",
        "create" => "wilayah::pengajuans.create resource",
        "update" => "wilayah::pengajuans.update resource",
        "destroy" => "wilayah::pengajuans.destroy resource",
        "confirm" => "wilayah::pengajuans.confirm resource",
        "approve" => "wilayah::pengajuans.approve resource",
    ],
];
