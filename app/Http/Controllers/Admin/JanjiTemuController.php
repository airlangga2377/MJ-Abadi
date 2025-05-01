<?php

namespace App\Http\Controllers\Admin;

use Gate;
use Carbon\Carbon;
use App\Models\User;
use App\Models\JanjiTemu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreJanjiTemuRequest;
use App\Http\Requests\UpdateJanjiTemuRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\MassDestroyJanjiTemuRequest;

class JanjiTemuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = JanjiTemu::with(['user'])->select(sprintf('%s.*', (new JanjiTemu)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            $table->editColumn('actions', function ($row) {
                $viewGate      = 'janjitemu_show';
                $editGate      = 'janjitemu_edit';
                $deleteGate    = 'janjitemu_delete';
                $pasienGate    = 'pasien_show';
                $crudRoutePart = 'janjitemu';

                return view('partials.DatatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'pasienGate',
                    'crudRoutePart',
                    'row'
                ));
            });
            $table->addColumn('user_id', function ($row) {
                return $row->user ? $row->user->name : '';
            });
            
            $table->addColumn('usia', function ($row) {
                if ($row->user) {
                    $birthDate = Carbon::parse($row->user->tanggal_lahir);
                    $today = Carbon::now();
                    return $today->diffInYears($birthDate);
                } else {
                    return '';
                }
            });
            
            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('keluhan', function ($row) {
                return $row->keluhan ? $row->keluhan : '';
            });
            $table->editColumn('tanggal', function ($row) {
                return $row->tanggal ? $row->tanggal : '';
            });
            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.janjitemu.index');
    }

    private function hitungUmur($tanggal_lahir)
    {
        $birthDate = Carbon::parse($tanggal_lahir);
        $today = Carbon::now();
        return $today->diffInYears($birthDate);
    }

    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(Gate::denies('janjitemu_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $pasiens = User::whereHas('roles', function ($q) {
            $q->where('title', 'pasien');
        })->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');


        return view('admin.janjitemu.create',compact('pasiens'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJanjiTemuRequest $request)
    {
        $janjitemu = JanjiTemu::create($request->all());



        return redirect()->route('admin.janjitemu.index');
    }

    /**
     * Display the specified resource.
     */
    public function edit(JanjiTemu $janjitemu)
    {
        abort_if(Gate::denies('janjitemu_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $janjitemu = JanjiTemu::findOrFail($decryptedId);

        return view('admin.janjitemu.edit', compact('janjitemu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update(UpdateJanjiTemuRequest $request, JanjiTemu $janjitemu)
    {
        $janjitemu->update($request->all());

        $appointment->user()->sync($request->input('user', []));
        return redirect()->route('admin.janjitemu.index');
    }

    /**
     * Update the specified resource in storage.
     */
    private function getJanjiTemu($id)
    {
        abort_if(Gate::denies('janjitemu_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            $decryptedId = decrypt($id);
        } catch (DecryptException $e) {
            // Handle decryption error gracefully (e.g., return 400 Bad Request)
            return abort(400, 'Invalid appointment ID');
        }

        $janjitemu = JanjiTemu::with(['user','pasien'])->findOrFail($decryptedId);
        return $janjitemu;
    }

    public function show($id)
    {
        $janjitemu = $this->getJanjiTemu($id);
        return view('admin.janjitemu.show', compact('janjitemu'));
    }

    public function pasienShow($id)
    {
        $janjitemu = $this->getJanjiTemu($id);
        // Add logic specific to handling patients here (optional)
        return view('admin.janjitemu.pasien.show', compact('janjitemu'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JanjiTemu $janjitemu)
    {
        abort_if(Gate::denies('janjitemu_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $janjitemu->delete();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function massDestroy(MassDestroyJanjiTemuRequest $request)
    {
        JanjiTemu::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
