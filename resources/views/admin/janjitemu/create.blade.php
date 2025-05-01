@extends('layouts.admin')
@section('styles')
@endsection
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.janjitemu.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.janjitemu.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group {{ $errors->has('user_id') ? 'has-error' : '' }}">
                <label for="user_id">{{ trans('cruds.janjitemu.fields.nama_pasien') }}*</label>
                {{-- <input placeholder="{{ Auth::user()->name }}" type="text" id="nama_pasien" name="nama_pasien" class="form-control" value="{{ old('nama_pasien', isset($janjitemu) ? $janjitemu->nama_pasien : '') }}" required disabled> --}}
                {{-- <input placeholder="Isi Nama Pasien" type="text" id="nama_pasien" name="nama_pasien" class="form-control" value="{{ old('nama_pasien', isset($janjitemu) ? $janjitemu->nama_pasien : '') }}"> --}}
                <select name="user_id" id="user_id" class="form-control select2" required>
                    @foreach($pasiens as $id => $pasien)
                        <option value="{{ $id }}" {{ (isset($janjitemu) && $janjitemu->pasien ? $janjitemu->pasien->id : old('user_id')) == $id ? 'selected' : '' }}>{{ $pasien }}</option>
                    @endforeach
                </select>
                @if($errors->has('user_id'))
                    <em class="invalid-feedback">
                        {{ $errors->first('user_id') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.janjitemu.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('keluhan') ? 'has-error' : '' }}">
                <label for="keluhan">{{ trans('cruds.janjitemu.fields.keluhan') }}</label>
                {{-- <input placeholder="{{ Auth::user()->name }}" type="text" id="keluhan" name="keluhan" class="form-control" value="{{ old('keluhan', isset($janjitemu) ? $janjitemu->keluhan : '') }}" required disabled> --}}
                <input placeholder="isi keluhan anda" type="text" id="keluhan" name="keluhan" class="form-control" value="{{ old('keluhan', isset($janjitemu) ? $janjitemu->keluhan : '') }}">
                @if($errors->has('keluhan'))
                    <em class="invalid-feedback">
                        {{ $errors->first('keluhan') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.janjitemu.fields.name_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('tanggal') ? 'has-error' : '' }}" data-provide="datepicker">
                <label for="tanggal">{{ trans('cruds.janjitemu.fields.tanggal') }}</label>
                {{-- <input placeholder="{{ Auth::user()->name }}" type="text" id="tanggal" name="tanggal" class="form-control" value="{{ old('tanggal', isset($janjitemu) ? $janjitemu->tanggal : '') }}" required disabled> --}}
                <input id="tanggal" type="datetime-local" name="tanggal" class="form-control" value="{{ old('tanggal', isset($janjitemu) ? $janjitemu->tanggal : '') }}">
                @if($errors->has('tanggal'))
                    <em class="invalid-feedback">
                        {{ $errors->first('tanggal') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.janjitemu.fields.name_helper') }}
                </p>
            </div>

            <div>
                <input class="btn btn-success" type="submit" value="{{ trans('cruds.janjitemu.fields.global_save') }}">
            </div>
        </form>


    </div>
</div>
@endsection

@section('scripts')
@endsection

