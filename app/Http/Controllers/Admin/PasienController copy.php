<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Pasien;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPasienRequest;
use App\Http\Requests\StorePasienRequest;
use App\Http\Requests\UpdatePasienRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Models\Admin\JanjiTemu; 
use App\Http\Controllers\Traits\MediaUploadingTrait;

class PasienController extends Controller
{
    use MediaUploadingTrait;

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Pasien::with(['janjitemu','user'])->select(sprintf('%s.*', (new Pasien)->table));

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'pasien_show';
                $editGate = 'pasien_edit';
                $deleteGate = 'pasien_delete';
                $crudRoutePart = 'pasien';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });
            $table->editColumn('janji_temu', function ($row) {
                $labels = [];

                foreach ($row->janjiTemu as $janjitemu) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $janjitemu->tanggal);
                }

                return implode(' ', $labels);
            });

            // $table->editColumn('tanggal', function ($row) {
            //     if ($row->janjitemu && $row->janjitemu->tanggal) {
            //         return $row->janjitemu->tanggal;
            //     }

            //     return '-';
            // });
            // $table->editColumn('keluhan', function ($row) {
            //     if ($row->janjitemu && $row->janjitemu->keluhan) {
            //         return $row->janjitemu->keluhan;
            //     }

            //     return '-';
            // });
            // $table->editColumn('nama_pasien', function ($row) {
            //     if ($row->janjitemu && $row->janjitemu->user_id) {
            //         return $row->janjitemu->user->name;
            //     }

            //     return '-';
            // });

            $table->editColumn('penanganan', function ($row) {
                return $row->penanganan ? $row->penanganan : '';
            });
            $table->editColumn('keterangan', function ($row) {
                return $row->keterangan ? $row->keterangan : '';
            });
            $table->editColumn('diagnosa', function ($row) {
                return $row->diagnosa ? $row->diagnosa : '';
            });

            $table->editColumn('photo', function ($row) {
                if ($photo = $row->photo) {
                    return sprintf(
                        '<a href="%s" target="_blank"><img src="%s" width="50px" height="50px"></a>',
                        $photo->url,
                        $photo->thumbnail
                    );
                }

                return '';
            });

            $table->addColumn('usia', function ($row) {
                if ($row->user && $row->user->tanggal_lahir) {
                    return $this->hitungUmur($row->user->tanggal_lahir);
                }
                return '-';
            })->make(true);

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.pasien.index');
    }

    private function hitungUmur($tanggal_lahir)
    {
        $birthDate = Carbon::parse($tanggal_lahir);
        $today = Carbon::now();
        return $today->diffInYears($birthDate);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('pasien_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.pasien.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StorePasienRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePasienRequest $request)
    {
        $pasien = Pasien::create($request->all());

        return redirect()->route('admin.pasien.index');
    }
}