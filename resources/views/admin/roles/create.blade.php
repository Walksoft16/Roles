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
    <li>
        <a href="{{route('roles.index')}}">{{trans('Roles::roles.roles')}}</a>
    </li>
    <li class="active">{{trans('adminlte::adminlte.create')}}</li>
</ol>
@stop

@section('content_header_options')
<div class="x_content">
    <a href="{{route('roles.index')}}" class="btn btn-primary ">{{trans('adminlte::adminlte.return')}}</a>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header">
                <i class="fa fa-edit"></i>
                <h3 class="box-title">{{trans('Roles::roles.createRol')}}</h3>
            </div>
            <form id="form-obj" data-parsley-validate="" novalidate="" method="post" action="{{route('roles.store')}}">
                {{csrf_field()}}
                <div class="form-horizontal form-label-left">
                    <div class="form-group">
                        <label for="name" class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('adminlte::adminlte.name')}}<span class="required"> (*)</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="name" type="text" required="required" placeholder="{{trans('adminlte::adminlte.name')}}" class="form-control col-md-7 col-xs-12" maxlength="255" name="name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="category_parent" class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('adminlte::adminlte.state')}}<span class="required"> (*)</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select name="state" id="state" class="form-control" required="required">
                                <option value=""></option>
                                <option value="1">{{trans('adminlte::adminlte.active')}}</option>
                                <option value="0">{{trans('adminlte::adminlte.inactive')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('adminlte::adminlte.description')}}</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea id="description" class="form-control" rows="3" maxlength="255" name="description"></textarea>
                        </div>
                    </div>

                </div>
                <!--permits-->
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <i class="fa fa-key"></i>
                        <h3 class="box-title">Permits</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="box-group" id="accordion">
                            @foreach(config('permits.modules') as $module)
                            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                            <div class="panel box box-primary" style="border-top-color: #f7f7f7;">
                                <div class="box-header with-border" style="background-color:#f7f7f7; font-size: 18px;adding: 7px 10px; margin-top: 0;">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$module['id']}}" aria-expanded="false" class="collapsed" style="color: #444;">
                                            {{trans($module['module'])}}
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse{{$module['id']}}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="checkall-{{$module['slug']}}" id="checkall-{{$module['slug']}}" value="" class="checkall flat-red">
                                                    {{trans('adminlte::adminlte.all')}}
                                                </label>
                                            </div>
                                            @foreach($module['slugs'] as $slug)
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" class="flat-red" name="permits[{{$slug['slug']}}]" id="permits-{{$slug['slug']}}" value="{{$slug['name']}}">
                                                    <!--<input type="hidden" name="permits[{{$slug['slug']}}][slug]" value="{{$slug['slug']}}">-->
                                                    {{trans($slug['display'])}}
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
                <div class="ln_solid"></div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary center-block">{{trans('adminlte::adminlte.save')}}</button>
                </div>

            </form>
        </div>
    </div>
    <!-- /.col -->
</div>
@stop

@section('js')
<script type="text/javascript">
    $(function () {

        var checkAll = $('input.checkall');

        checkAll.on('ifChecked ifUnchecked', function (event) {
            if (event.type == 'ifChecked') {
                $(this).closest(".form-group").children().find("input[type=checkbox]").iCheck('check');
            } else {
                $(this).closest(".form-group").children().find("input[type=checkbox]").iCheck('uncheck');
            }
        });

        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        })
    });
</script>
@stop