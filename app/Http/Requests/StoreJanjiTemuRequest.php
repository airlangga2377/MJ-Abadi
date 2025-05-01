<?php

namespace App\Http\Requests;

use App\Models\JanjiTemu;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreJanjiTemuRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('janjitemu_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'user_id'   => [
                'required',
                'integer',
            ],
            'keluhan' => [
                'required',
                'string',
            ],
            'tanggal'  => [
                'required',
            ],
        ];
    }
}
