<?php

namespace App\Http\Controllers\Admin;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Employee;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAppointmentRequest;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Service;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AppointmentsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('appointment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = Appointment::with(['client', 'employee', 'services'])->select(sprintf('%s.*', (new Appointment)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'appointment_show';
                $editGate      = 'appointment_edit';
                $deleteGate    = 'appointment_delete';
                $crudRoutePart = 'appointments';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });
            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->addColumn('client_name', function ($row) {
                return $row->client ? $row->client->name : '';
            });

            $table->addColumn('employee_name', function ($row) {
                return $row->employee ? $row->employee->name : '';
            });

            $table->editColumn('price', function ($row) {
                return $row->price ? $row->price : "";
            });
            $table->editColumn('comments', function ($row) {
                return $row->comments ? $row->comments : "";
            });
            $table->editColumn('services', function ($row) {
                $labels = [];

                foreach ($row->services as $service) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $service->name);
                }

                return implode(' ', $labels);
            });

            $table->rawColumns(['actions', 'placeholder', 'client', 'employee', 'services']);

            return $table->make(true);

        }

        return view('admin.appointments.index');
    }

    public function create()
    {
        abort_if(Gate::denies('appointment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clients = Client::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $employees = Employee::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $services = Service::all()->pluck('name', 'id');

        return view('admin.appointments.create', compact('clients', 'employees', 'services'));
    }
    private function getAppointment($id)
    {
        abort_if(Gate::denies('appointment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            $decryptedId = decrypt($id);
        } catch (DecryptException $e) {
            // Handle decryption error gracefully (e.g., return 400 Bad Request)
            return abort(400, 'Invalid appointment ID');
        }
        $appointment = Appointment::with(['client', 'employee', 'services'])->findOrFail($decryptedId);
        return $appointment;
    }
    private function editAppointment($id)
    {
        abort_if(Gate::denies('appointment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            $decryptedId = decrypt($id);
        } catch (DecryptException $e) {
            // Handle decryption error gracefully (e.g., return 400 Bad Request)
            return abort(400, 'Invalid appointment ID');
        }
        // $appointment->load('client', 'employee', 'services');
        $appointment = Appointment::with(['client', 'employee', 'services'])->findOrFail($decryptedId);
        return $appointment;
    }

    public function store(StoreAppointmentRequest $request)
    {
        $appointment = Appointment::create($request->all());
        $appointment->services()->sync($request->input('services', []));

        return redirect()->route('admin.appointments.index');
    }

    public function edit($id)
    {
        abort_if(Gate::denies('appointment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $appointment = $this->editAppointment($id);
        $clients = Client::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $employees = Employee::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $services = Service::all()->pluck('name', 'id');

        $appointment->load('client', 'employee', 'services');

        return view('admin.appointments.edit', compact('clients', 'employees', 'services', 'appointment'));
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        $appointment->update($request->all());
        $appointment->services()->sync($request->input('services', []));
        return redirect()->route('admin.appointments.index');
    }

    public function show($id)
    {
        abort_if(Gate::denies('appointment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $appointment = $this->getAppointment($id);

        $appointment->load('client', 'employee', 'services');

        return view('admin.appointments.show', compact('appointment'));
    }

    public function destroy(Appointment $appointment)
    {
        abort_if(Gate::denies('appointment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $appointment->delete();

        return back();
    }

    public function massDestroy(MassDestroyAppointmentRequest $request)
    {
        Appointment::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
