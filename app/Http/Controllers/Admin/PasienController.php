<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pasien;
use App\Models\JanjiTemu; 
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPasienRequest;
use App\Http\Requests\StorePasienRequest;
use App\Http\Requests\UpdatePasienRequest;
use Gate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;
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
        abort_if(Gate::denies('pasien_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            // $query = Pasien::with(['user', 'janji_temu'])->select(sprintf('%s.*', (new Pasien)->table)); // Assuming table names match model names
            $query = Pasien::with(['user', 'janji_temu'])->select(sprintf('%s.*', (new Pasien)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->addColumn('actions', function ($row) {
                $viewGate = 'pasien_show';
                $editGate = 'pasien_edit';
                $deleteGate = 'pasien_delete';
                $crudRoutePart = 'pasien';
                return view('partials.datatablesActions', compact('viewGate', 'editGate', 'deleteGate', 'crudRoutePart', 'row'));
            });

            $table->addColumn('tanggal', function ($row) {
                return $row->janji_temu ? $row->janji_temu->tanggal : '';
            });

            $table->addColumn('user_id', function ($row) {
                return $row->janji_temu ? $row->janji_temu->user->name : '';
            });

            $table->addColumn('usia', function ($row) {
                if ($row->janji_temu && $row->janji_temu->user->tanggal_lahir) {
                    return $this->hitungUmur($row->janji_temu->user->tanggal_lahir);
                }
                return '-';
            })->make(true);

            $table->addColumn('keluhan', function ($row) {
                return $row->janji_temu ? $row->janji_temu->keluhan : '';
            });

            $table->editColumn('diagnosa', function ($row) {
                if ($row->diagnosa) {
                    return $row->diagnosa;
                }
                return '';
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

            $table->editColumn('penanganan', function ($row) {
                if ($row->penanganan) {
                    return $row->penanganan;
                }
                return '';
            });

            $table->editColumn('keterangan', function ($row) {
                if ($row->keterangan) {
                    return $row->keterangan;
                }
                return '';
            });

            $table->rawColumns(['actions', 'placeholder', 'photo', 'tanggal', 'keluhan', 'user_id']); // Adjust as needed

            return $table->make(true);
        }

        return view('admin.pasien.index');
    }
    private function getRelatedData($row, $relationName, $field)
    {
        if (isset($row->$relationName) && $row->$relationName->$field) {
            return $row->$relationName->$field;
        }
        return '';
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
    public function create(StorePasienRequest $request)
    {
        abort_if(Gate::denies('pasien_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // $janjitemu = JanjiTemu::with('user')->get()->pluck('user.name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $janji_temu = JanjiTemu::all()->pluck('id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.pasien.create', compact('janji_temu'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StorePasienRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePasienRequest $request)
    {
        abort_if(Gate::denies('pasien_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $pasien = Pasien::create($request->all());

        if ($request->input('photo', false)) {
            $pasien->addMedia(storage_path('tmp/uploads/' . $request->input('photo')))->toMediaCollection('photo');
        }
        return redirect()->route('admin.pasien.index');
    }
    /**
     * Display the specified resource.
     */
    public function edit(Pasien $pasien)
    {
        abort_if(Gate::denies('pasien_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pasien = Pasien::findOrFail($decryptedId);

        return view('admin.pasien.edit', compact('pasien'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update(UpdatePasienRequest $request, Pasien $pasien)
    {
        $pasien->update($request->all());

        return redirect()->route('admin.pasien.index');
    }

    /**
     * Update the specified resource in storage.
     */
    private function getPasien($id)
    {
        abort_if(Gate::denies('pasien_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            $decryptedId = decrypt($id);
        } catch (DecryptException $e) {
            // Handle decryption error gracefully (e.g., return 400 Bad Request)
            return abort(400, 'Invalid appointment ID');
        }

        $pasien = Pasien::with(['user','janjitemu'])->findOrFail($decryptedId);
        return $pasien;
    }

    public function show($id)
    {
        $pasien = $this->getPasien($id);
        return view('admin.pasien.show', compact('janjitemu'));
    }

    // public function pasienShow($id)
    // {
    //     $janjitemu = $this->getJanjiTemu($id);
    //     // Add logic specific to handling patients here (optional)
    //     return view('admin.janjitemu.pasien.show', compact('janjitemu'));
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pasien $pasien)
    {
        abort_if(Gate::denies('pasien_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pasien->delete();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function massDestroy(MassDestroyPasienRequest $request)
    {
        Pasien::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}