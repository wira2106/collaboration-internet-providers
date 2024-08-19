<?php

namespace Modules\Configuration\Http\Controllers\Api;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Configuration\Entities\Configuration;

class ConfigurationController extends AdminBaseController
{
    public function __construct()
    {
    }

    public function index() {
        return response()->json([
            'errors' => false,
            'data' => Configuration::find(1)
        ]);
    }
}
