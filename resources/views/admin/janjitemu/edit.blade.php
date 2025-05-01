@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.janjitemu.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.janjitemu.update", [$janjitemu->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('id_user') ? 'has-error' : '' }}">
                <label for="id_user">{{ trans('cruds.janjitemu.fields.id_user') }}*</label>
                <input type="text" id="id_user" name="janji_temu" class="form-control" value="{{ old('janji_temu', isset($janjitemu) ? $janjitemu->janji_temu : '') }}" required>
                @if($errors->has('janji_temu'))
                    <em class="invalid-feedback">
                        {{ $errors->first('janji_temu') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.janjitemu.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('price') ? 'has-error' : '' }}">
                <label for="price">{{ trans('cruds.janjitemu.fields.price') }}</label>
                <input type="number" id="price" name="price" class="form-control" value="{{ old('price', isset($janjitemu) ? $janjitemu->price : '') }}" step="0.01">
                @if($errors->has('price'))
                    <em class="invalid-feedback">
                        {{ $errors->first('price') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.janjitemu.fields.price_helper') }}
                </p>
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div>
@endsection