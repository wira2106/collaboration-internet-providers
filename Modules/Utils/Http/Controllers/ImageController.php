<?php

namespace Modules\Utils\Http\Controllers;

use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Intervention\Image\ImageManagerStatic as Image;

class ImageController extends AdminBaseController
{
  public static function uploadFoto($foto, $x = null, $y = null, $width = null, $height = null)
  {
    $extension = "";
    $extension = $foto->getClientOriginalExtension();
    $filename = date('YmdHis') . rand(0, 9999);
    try {

      if ($extension != 'png' && $extension != 'jpg') {
        $filename = $filename . ".png";
      } else {
        $filename = $filename . "." . $extension;
      }


      $image = $foto;
      // upload ke thumbnail
      $destinationPath = 'uploadgambar/thumbnail';
      $img = Image::make($image->getRealPath());
      if ($extension == 'jpg' || $extension == 'png') {
        $img = $img->resize(500, 500, function ($constraint) {
          $constraint->aspectRatio();
        });
      }
      $img->save($destinationPath . '/' . $filename);
      // upload ke uploadgambar
      $destinationPath = 'uploadgambar';
      if ($x != null && $y != null && $width != null && $height != null) {
        if ($extension == 'jpg' || $extension == 'png') {
          $img->crop($width, $height, $x, $y);
        }
        $img->save($destinationPath . '/' . $filename);
      } else {
        $foto->move($destinationPath, $filename);
      }
    } catch (\Exception $exception) {
      $destinationPath = 'uploadgambar';
      $filename = $filename . "." . $extension;
      $foto->move($destinationPath, $filename);
    }

    return $filename;
  }

  public static function uploadFotoSurvey($foto, $x = null, $y = null, $width = null, $height = null)
  {
    $extension = "";
    $extension = $foto->getClientOriginalExtension();
    $filename = date('YmdHis') . '-Survey-' . rand(0, 9999);
    try {

      if ($extension != 'png' && $extension != 'jpg') {
        $filename = $filename . ".png";
      } else {
        $filename = $filename . "." . $extension;
      }


      $image = $foto;
      // upload ke thumbnail
      $destinationPath = 'uploadgambar/thumbnail';
      $img = Image::make($image->getRealPath());
      if ($extension == 'jpg' || $extension == 'png') {
        $img = $img->resize(500, 500, function ($constraint) {
          $constraint->aspectRatio();
        });
      }
      $img->save($destinationPath . '/' . $filename);
      // upload ke uploadgambar
      $destinationPath = 'uploadgambar';
      if ($x != null && $y != null && $width != null && $height != null) {
        if ($extension == 'jpg' || $extension == 'png') {
          $img->crop($width, $height, $x, $y);
        }
        $img->save($destinationPath . '/' . $filename);
      } else {
        $foto->move($destinationPath, $filename);
      }
    } catch (\Exception $exception) {
      $destinationPath = 'uploadgambar';
      $filename = $filename;
      $foto->move($destinationPath, $filename);
    }

    return $filename;
  }
}