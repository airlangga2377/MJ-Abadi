<?php

namespace App\Http\Requests;

use App\Models\JanjiTemu;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateJanjiTemuRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('janjitemu_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'user_id'   => [
                'required',
            ],
            'keluhan' => [
                'required',
            ],
            'tanggal'  => [
                'required',
            ],
        ];
    }
}
