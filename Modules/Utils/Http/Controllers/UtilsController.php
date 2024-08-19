<?php

namespace Modules\Utils\Http\Controllers;

use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class UtilsController extends AdminBaseController
{
    public function gzipEncode($data) {
      header("Content-Type: text/javascript");
      header("Content-Encoding: gzip");
      return gzencode(json_encode($data));
    }
}
