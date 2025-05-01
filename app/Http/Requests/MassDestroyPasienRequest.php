<?php

namespace App\Http\Requests;

use App\Models\Pasien;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyPasienRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('pasien_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:appointments,id',
        ];
    }
}
