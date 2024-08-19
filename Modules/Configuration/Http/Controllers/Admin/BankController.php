<?php

namespace Modules\Configuration\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Configuration\Entities\Configuration;
use Modules\Configuration\Http\Requests\CreateConfigurationRequest;
use Modules\Configuration\Http\Requests\UpdateConfigurationRequest;
use Modules\Configuration\Repositories\ConfigurationRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use DB;

class BankController extends AdminBaseController
{
	public function index(Request $req)
	{
		return view('configuration::admin.bank.index');
	}
}
