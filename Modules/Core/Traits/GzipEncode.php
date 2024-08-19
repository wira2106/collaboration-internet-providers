<?php

namespace Modules\Core\Traits;

trait GzipEncode
{
    public function gzipEncode($data)
    {
        header("Content-Type: text/javascript");
		header("Content-Encoding: gzip");
		return gzencode(json_encode($data));
    }
}
