<?php

namespace App\Http\Requests;

use App\Models\Pasien;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdatePasienRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('pasien_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'janji_temu_id'   => [
                'integer',
            ],
            'diagnosa' => [
                'required',
            ],
            'penanganan' => [
                'required',
            ],
            'keterangan' => [
                'required',
            ],
        ];
    }
}
