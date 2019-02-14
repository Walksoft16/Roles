@extends('adminlte::page')

@section('title',trans('Roles::roles.roles'))

@section('content_header')
<h1>
	{{trans('Roles::roles.roles')}}
</h1>
<ol class="breadcrumb">
    <li>
        <a href="{{route('admin')}}"><i class="fa fa-home"></i>{{trans('adminlte::adminlte.administration')}}</a>
    </li>
    <li>{{trans('Roles::roles.roles')}}</li>
</ol>
@stop

@section('content_header_options')
<div class="x_content">
    <a href="{{route('admin')}}" class="btn btn-primary ">{{trans('adminlte::adminlte.return')}}</a>
    @if($permits['create'])<a href="{{route('roles.create')}}" class="btn btn-primary">{{trans('adminlte::adminlte.create')}}</a>@endif
</div>
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
              <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <table id="datatable-buttons" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>{{trans('adminlte::adminlte.name')}}</th>
                        <th>{{trans('adminlte::adminlte.description')}}</th>
                        <th>{{trans('adminlte::adminlte.state')}}</th>
                        <th>{{trans('adminlte::adminlte.creationDate')}}</th>
                        @if($permits['edit'])<th style="text-align: center;">{{trans('adminlte::adminlte.edit')}}</th>@endif
                        @if($permits['delete'])<th style="text-align: center;">{{trans('adminlte::adminlte.delete')}}</th>@endif
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $rol )
                            <tr>
                                <td>{{ $rol->name }}</td>
                                <td>{{ $rol->description }}</td>
                                <td>{{ $rol->level == 1? "Activo" : "Inactivo" }}</td>
                                <td>{{ $rol->created_at }}</td>
                                @if($permits['edit'])<td style="text-align: center;"><a href="{{route('roles.edit',['id' => $rol->id ])}}"><i class="fa fa-edit fa-2x"></i></a></td>@endif
                                @if($permits['delete'])<td style="text-align: center;"><a href="{{route('roles.show',['id' => $rol->id ])}}"><i class="fa fa-remove fa-2x"></i></a></td>@endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
              </div>
            </div>
            <!-- /.box-body -->
         </div>
     </div>
</div>
@stop
@section('js')
<script>
  $(function () {
    $('#datatable-buttons').DataTable();
  })
</script>
 @stop