<?php

return [
    "presale.presales" => [
        "index" => "presale::presales.list resource",
        "create" => "presale::presales.create resource",
        "edit" => "presale::presales.edit resource",
        "delete" => "presale::presales.destroy resource",
        "confirm" => "presale::presales.confirm resource"
    ],
    "presale.endpoints" => [
        "index" => "presale::endpoint.list resource",
        "create" => "presale::endpoint.create resource",
        "edit" => "presale::endpoint.edit resource",
        "delete" => "presale::endpoint.delete resource",
    ],    
];
