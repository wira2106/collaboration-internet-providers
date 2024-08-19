<?php

namespace Modules\Pelanggan\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Pelanggan\Entities\Survey;
use Modules\Pelanggan\Http\Requests\UpdateSurveyRequest;
use Modules\Pelanggan\Repositories\SurveyRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Pelanggan\Transformers\SlotInstalasiPengajuan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Analytic\Events\Pelanggan\AddJadwalInstalasi;
use Modules\Peralatan\Entities\Perangkat;
use Modules\Utils\Http\Controllers\ImageController;
use Modules\Pelanggan\Entities\Pelanggan;
use Modules\Analytic\Events\Pelanggan\SurveyPelanggan;
use Modules\Pelanggan\Transformers\DetailSurvey;

class SurveyController extends AdminBaseController
{
    /**
     * @var SurveyRepository
     */
    private $survey;

    public function __construct(SurveyRepository $survey)
    {
        parent::__construct();
        $this->survey = new Survey();
    }

    public function company_name()
    {
        $data = Auth::user()->company();
        return $data['name'];
    }

    public function index($pelanggan_id)
    {
        $pelanggan = Pelanggan::find($pelanggan_id);
        $perangkat = Perangkat::orderBy('nama_perangkat')->get();
        $survey = new DetailSurvey($this->survey->getSurvey($pelanggan_id));

        // option staff
        $option_teknisi = Pelanggan::getOptionTeknisi($pelanggan->osp_id);
        // list slot instalasi
        $slot_instalasi = DB::table('slot_instalasi')->where('company_id', $pelanggan->osp_id)
            ->whereNull('deleted_at')->get();
        // get status survey is finish
        $status_survey = $this->survey->getRangeJadwalPengajuanSurvey($pelanggan_id);
        // return list teknisi di pengajuan survey

        if ($survey == null) {
            $data = [
                $status_survey,
                $perangkat = null,
                $survey = null,
                $surveyStaff = null,
                $perangkatTambahan = null,
                SlotInstalasiPengajuan::collection($slot_instalasi),
                $option_teknisi
            ];
            return $data;
        }
        // end return list teknisi di pengajuan survey

        // staff survey
        $surveyStaff = $this->survey->getTeknisiSurvey($survey->id_survey);
        // perangkat
        $perangkatTambahan = DB::table('perangkat_tambahan')
            ->join('perangkat', 'perangkat_tambahan.perangkat_id', '=', 'perangkat.perangkat_id')
            ->where('survey_id', '=', $survey->id_survey)->get();

        $data = [
            $status_survey,
            $perangkat,
            $survey,
            $surveyStaff,
            $perangkatTambahan,
            SlotInstalasiPengajuan::collection($slot_instalasi),
            $option_teknisi
        ];

        return $data;
    }



    public function create()
    {
        return view('pelanggan::admin.surveys.create');
    }



    public function store(Request $request)
    {
        // dd($request->all());
        // STAFF TEKNISI
        if ($request->get('teknisiInstalasi') !== null) {
            DB::table('instalation_staff')
                ->where('instalation_staff.instalasi_id', '=', $request->instalation_id)
                ->delete();
            $check = DB::table('instalasi')
                ->where('instalasi.pelanggan_id', '=', $request->pelanggan_id)
                ->count();
            $instalasi_id = null;
            if ($check == 0) {
                $instalasi_id = DB::table('instalasi')
                    ->insertGetId(['pelanggan_id' => $request->pelanggan_id,]);
            } else {
                $instalasi_id = DB::table('instalasi')
                    ->where('pelanggan_id', $request->pelanggan_id)->first()->instalasi_id;
            }
            $teknisiInsert = [];
            foreach ($request->get('teknisiInstalasi') as $teknisi) {
                array_push($teknisiInsert, [
                    'instalasi_id' => $instalasi_id,
                    'user_id' => $teknisi
                ]);
            }
            DB::table('instalation_staff')
                ->insert($teknisiInsert);
        }

        if ((int)$request->survey_finish == 1) {
            // send event
            $cekUpdate = DB::table('survey')->where('id', $request->survey_id)->first();
            if ($cekUpdate->status == 'active') {
                event(new SurveyPelanggan($cekUpdate->pelanggan_id));
            }
            // FOTO JALUR KABEL
            $fileFotoJalurKabel = [];
            if ($request->foto_jalur_kabel !== '' && $request->foto_jalur_kabel !== null) {
                foreach ($request->foto_jalur_kabel as  $value) {
                    if (!$value['new']) {
                        array_push($fileFotoJalurKabel, $value['name']);
                    }
                }
                if ($request->upload_jalur_kabel !== null && $request->upload_jalur_kabel !== '') {
                    foreach ($request->upload_jalur_kabel as  $value) {
                        array_push($fileFotoJalurKabel, ImageController::uploadFotoSurvey($value));
                    }
                }
            }

            // FOTO SIGNAL KABEL
            $fileFotoSignalKabel = [];
            if ($request->foto_signal_kabel !== '' && $request->foto_signal_kabel !== null) {
                foreach ($request->foto_signal_kabel as  $value) {
                    if (!$value['new']) {
                        array_push($fileFotoSignalKabel, $value['name']);
                    }
                }
                if ($request->upload_signal_kabel !== null && $request->upload_signal_kabel !== '') {
                    foreach ($request->upload_signal_kabel as  $value) {
                        array_push($fileFotoSignalKabel, ImageController::uploadFotoSurvey($value));
                    }
                }
            }

            // PERANGKAT TAMBAHAN
            if ($request->get('perangkat_tambahan') !== null && $request->get('perangkat_tambahan') !== '') {
                foreach ($request->get('perangkat_tambahan') as $perangkat) {
                    if ($perangkat['id'] !== null) {
                        DB::table('perangkat_tambahan')
                            ->where('id', '=', $perangkat['id'])
                            ->update([
                                'survey_id' => $request->survey_id,
                                'perangkat_id' => $perangkat['perangkat_id'],
                                'qty' => $perangkat['qty'],
                                'jenis_perangkat' => $perangkat['jenis_perangkat'],
                            ]);
                    } else {
                        DB::table('perangkat_tambahan')
                            ->insert([
                                'survey_id' => $request->survey_id,
                                'perangkat_id' => $perangkat['perangkat_id'],
                                'qty' => $perangkat['qty'],
                                'jenis_perangkat' => $perangkat['jenis_perangkat'],
                            ]);
                    }
                }
            }

            // TAMBAH KE PENGAJUAN INSTALASI
            if ($request->jadwalInstalasi != null) {
                if($request->jadwalInstalasi[0]['tgl_instalasi']!=null){
                    if (count($request->jadwalInstalasi) > 0) {
                        $insert = [
                            'pelanggan_id' => $request->pelanggan_id,
                            'type' => 'instalasi',
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => Auth::User()->id,
                            'status' => 'pending'
                        ];
                        $pengajuan_id = DB::table('pengajuan_jadwal')->insertGetId($insert);
    
                        $insert = [];
                        foreach ($request->jadwalInstalasi as $key => $val) {
                            $insert[] = [
                                "pengajuan_id" => $pengajuan_id,
                                "tgl_instalasi" => $val["tgl_instalasi"],
                                "slot_id" => $val["slot_id"],
                                "status" => $val["status"]
                            ];
                        }
                        DB::table("tanggal_pengajuan_instalasi")->insert($insert);
                    }
                    // DB::table("tanggal_pengajuan_instalasi")->insert($insert);
                    event(new AddJadwalInstalasi($request->pelanggan_id));
                }
            }

            // UPDATE ATAU INSERT SURVEY
            $updateSurvey = [
                'pelanggan_id' => $request->pelanggan_id,
                'tinggi_bangunan' => $request->tinggi_bangunan,
                'satuan_tinggi' => $request->satuan_tinggi,
                'foto_jalur_kabel' =>  json_encode($fileFotoJalurKabel),
                'foto_signal_kabel' => json_encode($fileFotoSignalKabel),
                'keterangan' => $request->keterangan,
                'status' => $request->status,
            ];
         
            DB::table('survey')->updateOrInsert(
                ['id' => $request->survey_id],
                $updateSurvey
            );

            if (DB::table('survey')->where('id', $request->survey_id)->count()) {
                Auth::user()->createLog(
                    trans('pelanggan::logpelanggan.crud.update.title.survey'),
                    trans('pelanggan::logpelanggan.crud.update.desc.survey', ['company' => $this->company_name()])
                );
            } else {
                Auth::user()->createLog(
                    trans('pelanggan::logpelanggan.crud.create.title.survey'),
                    trans('pelanggan::logpelanggan.crud.create.desc.survey', ['company' => $this->company_name()])
                );
            }
        }
        return response()->json([
            'status'=>$request->status,
            'errors' => false,
            'message' => trans('saldo::saldos.messages.success'),
        ]);
    }



    public function edit(Survey $survey)
    {
        return view('pelanggan::admin.surveys.edit', compact('survey'));
    }


    public function update(Survey $survey, UpdateSurveyRequest $request)
    {
        $this->survey->update($survey, $request->all());

        return redirect()->route('admin.pelanggan.survey.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('pelanggan::surveys.title.surveys')]));
    }

    public function updateTeknisi(Request $request, $survey_id)
    {
        $this->survey->updateTeknisiSurvey($request->teknisi, $survey_id);
        return response()->json([
            'errors' => false,
            'message' => trans('pelanggan::surveys.messages.simpan teknisi'),
        ]);
    }


    // public function destroy(Survey $survey)
    // {
    //     $this->survey->destroy($survey);

    //     return redirect()->route('admin.pelanggan.survey.index')
    //         ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('pelanggan::surveys.title.surveys')]));
    // }
}