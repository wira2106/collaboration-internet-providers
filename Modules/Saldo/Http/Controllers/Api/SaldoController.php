<?php

namespace Modules\Saldo\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class SaldoController extends AdminBaseController
{
   public function getSaldo() {
       $saldo = Auth::user()->company()->saldo();
       if($saldo) {
            $saldo = $saldo->saldo;
       } else {
           $saldo = 0;
       }

       return $saldo;
   }
}
