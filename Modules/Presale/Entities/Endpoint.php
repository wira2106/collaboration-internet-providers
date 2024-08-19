<?php

namespace Modules\Presale\Entities;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Presale\Infowindows\InfoWindowDetailEndpoint;
use Modules\Presale\Transformers\PathlinePresaleTransformer;
use Modules\User\Entities\Sentinel\User;
use Modules\Wilayah\Entities\Wilayah;

class Endpoint extends Model
{
    use SoftDeletes, InfoWindowDetailEndpoint;
    private $wilayah;
    protected $table = 'end_point';
    public $translatedAttributes = [];
    protected $primaryKey = 'end_point_id';
    protected $fillable = [
        "wilayah_id",
        "nama_end_point",
        "tipe",
        "lat_end_point",
        "lon_end_point",
        "address",
        "created_by"
    ];

    public function wilayah()
    {
        $wilayah = Wilayah::select([
            'provinces.name as province',
            'regencies.name as regency',
            'districts.name as district',
            'villages.name as village',

        ])
            ->leftjoin('provinces', 'provinces.id', 'wilayah.provinces_id')
            ->leftjoin('regencies', 'regencies.id', 'wilayah.regencies_id')
            ->leftjoin('districts', 'districts.id', 'wilayah.districts_id')
            ->leftjoin('villages', 'villages.id', 'wilayah.villages_id')
            ->where('wilayah.wilayah_id', $this->wilayah_id)
            ->first();


        return $wilayah;
    }

    public function totalPresale()
    {
        return Presale::where('end_point_id', $this->end_point_id)->count();
    }

    public function hasAccess(User $user, Request $request)
    {
        if ($user->hasAllAccessCompanies()) return true;
        $wilayah_id = $request->wilayah_id ? $request->wilayah_id : $this->wilayah_id;
        $wilayah = Wilayah::find($wilayah_id);

        if (isset($wilayah) && $user->userCompanies->company_id === $wilayah->company_id) return true;

        abort(Response::HTTP_FORBIDDEN, "Forbidden");
    }

    public function deletePresale()
    {
        Presale::where($this->primaryKey, $this->end_point_id)->delete();
    }

    public function routes()
    {
        $data = Presale::where('end_point_id', $this->end_point_id)->whereNull('deleted_at')->get();

        return PathlinePresaleTransformer::collection($data);
    }

    public function serverPaginationFilteringFor(Request $request): LengthAwarePaginator
    {
        $endpoint = self::select([
            'nama_end_point',
            'address',
            'end_point_id'
        ]);
        if ($request->wilayah_id) {
            $endpoint = $endpoint->where('wilayah_id', $request->wilayah_id);
        }

        $searchQuery = $request->search;
        if ($searchQuery) {
            $endpoint = $endpoint->where(function ($query) use ($searchQuery) {
                $query->where('nama_end_point', 'like', "%$searchQuery%")
                    ->orWhere('address', 'like', "%$searchQuery%");
            });
        }

        if ($request->tipe) {
            $endpoint->where('tipe', $request->tipe);
        }

        return $endpoint->paginate($request->get('per_page', 20));
    }

    public function generateJarakBaruDenganPresale($lat, $lng)
    {
        $dataRumah = Presale::where('end_point_id', $this->end_point_id)->get();
        foreach ($dataRumah as $key => $val) {
            $id  = $val->presale_id;
            $jarakBaru = $this->jarakBaru($val->presale_id, $lat, $lng);
            Presale::where('presale_id', $id)->update(['panjang_kabel' => $jarakBaru]);
        }
    }

    public function jarakBaru($id_rumah, $lat, $lon)
    {
        $data           = (new Presale)->findById($id_rumah);
        $latodpBaru     = (float)$lat;
        $longodpBaru    = (float)$lon;
        $jarakDiData = $data->panjang_kabel;
        $jarakAsli = 0;
        $arrayLines = [];
        $idTitikTerakhir = 0;
        foreach ($data->route_line as $key => $val) {
            $arrayLines[] = ['lat' => $val->lat, 'lon' => $val->lon];
            $idTitikTerakhir = $val->jalur_kabel_id;
        }
        for ($i = 1; $i < count($arrayLines); $i++) {
            $jarakAsli += $this->distance($arrayLines[$i - 1]['lat'], $arrayLines[$i - 1]['lon'], $arrayLines[$i]['lat'], $arrayLines[$i]['lon']);
        }
        $jarakAsli = ceil($jarakAsli);
        $jarakTambahan = $jarakDiData - $jarakAsli;
        if ($jarakTambahan < 0) {
            $jarakTambahan = 0;
        }

        //tentuin jarak baru
        $jarakBaru = 0;
        DB::table('jalur_kabel')->where('jalur_kabel_id', $idTitikTerakhir)->update(['lat' => $latodpBaru, 'lon' => $longodpBaru]);
        $dataLines = DB::table('jalur_kabel')->where('presale_id', $id_rumah)->get();
        $arrayLines = [];
        foreach ($dataLines as $key => $val) {
            $arrayLines[] = ['lat' => $val->lat, 'lon' => $val->lon];
        }
        for ($i = 1; $i < count($arrayLines); $i++) {
            $jarakBaru += $this->distance($arrayLines[$i - 1]['lat'], $arrayLines[$i - 1]['lon'], $arrayLines[$i]['lat'], $arrayLines[$i]['lon']);
        }

        $jarakBaru += $jarakTambahan;

        return $jarakBaru;
    }

    public function distance($lat1, $lon1, $lat2, $lon2)
    {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;

            $meter = ($miles * 1.609344) * 1000;
            return $meter;
        }
    }

    public function company_id()
    {
        $wilayah = Wilayah::select('wilayah.company_id')->where('wilayah_id', $this->wilayah_id)->first();

        return $wilayah ? $wilayah->company_id : null;
    }

    public function infowindow()
    {
        return array_merge($this->toArray(), [
            'total_presale' => $this->totalPresale()
        ]);
    }
}
