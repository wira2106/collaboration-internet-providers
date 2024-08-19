<?php

namespace Modules\Core\Traits;

use Illuminate\Support\Facades\DB;
use Modules\Company\Entities\Rating as EntitiesRating;

trait Rating
{
    public $total_rating = 10;

    public function generateRating($company_id) {
        $total_rating_company = EntitiesRating::where('penerima_rating_id' ,$company_id)->avg('rating');
        DB::table('companies')
        ->where('company_id', $company_id)
        ->update([
            'rating' => $total_rating_company
        ]);
    }

    public function createRating($waktu, $company_id, $description = "", $day_kompensasi = 1, $type = "openaccess") {
        DB::beginTransaction();
        try {
            $dayOnSecond = 86400;
            $totalDay = ceil($waktu / $dayOnSecond) - $day_kompensasi;
            $totalDay = $totalDay < 0 ? 0 : $totalDay;
            $point_rating = $this->total_rating - $totalDay;
            EntitiesRating::create([
                'tipe_rating' => $type,
                'pemberi_rating_id' => 1,
                'penerima_rating_id' => $company_id,
                'rating' => $point_rating,
                'description' => $description
            ]);
    
            $this->generateRating($company_id);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
