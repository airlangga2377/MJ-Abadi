@can($viewGate)
    <a class="btn btn-xs btn-primary" href="{{ route('admin.' . 'janjitemu' . '.show', encrypt($row->id)) }}">
        {{ trans('global.view') }}
    </a>
@endcan
@can($editGate)
    <a class="btn btn-xs btn-info" href="{{ route('admin.' . $crudRoutePart . '.edit', encrypt($row->id)) }}">
        {{ trans('global.edit') }}
    </a>
@endcan
@can($deleteGate)
    <form action="{{ route('admin.' . $crudRoutePart . '.destroy', $row->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
    </form>
@endcan

@can($pasienGate)
    <a class="btn btn-xs btn-success" href="{{ route('admin.' . $crudRoutePart . '.pasien'  . '.show', encrypt($row->id)) }}">
        {{ trans('global.view_detail') }}
    </a>
@endcan