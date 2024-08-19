<?php

namespace Modules\Utils\Http\Controllers;

use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class FileController extends AdminBaseController
{
    public static function uploadFile($file)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = date('YmdHis') . rand(0, 9999);
        $destinationPath = 'uploadfile';
        try {

            if ($extension != 'pdf' && $extension != 'doc' && $extension != 'docx') {
                $filename = $filename . ".pdf";
            } else {
                $filename = $filename . "." . $extension;
            }
            $file->move($destinationPath, $filename);
        } catch (\Exception $exception) {
            dd($exception);
            // $destinationPath = 'uploadgambar';
            // $filename = $filename . "." . $extension;
            // $file->move($destinationPath, $filename);
        }

        return $filename;
    }

    public function remove()
    {
    }
}
