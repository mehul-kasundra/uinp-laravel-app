@extends('containers.admin')
 
@section('title') Edit muble @stop
 
@section('main')

    @if (Request::segment(3)=='create')
        {{ Form::open(array('role' => 'form', 'url' => 'admin/mumble/store')) }}
    @else
        {{ Form::model($mumbleItem, array('role' => 'form', 'url' => 'admin/mumble/update/' . $mumbleItem->id, 'method' => 'PUT')) }}
    @endif
 
    <div style="overflow: hidden;">
        <h1 class="fa fa-microphone"> {{Request::segment(3)=='create'?'Add':'Edit'}} mumble item</h1>

        <div class='form-group pull-right top20'>
            {{ Form::submit('Save', array('class' => 'btn btn-primary')) }}
        </div>
    </div>

    <div class="row">
        <div class="col-md-10">
            <div class='form-group '>
                {{ Form::label('content', 'Mumble text') }}
                {{ Form::textarea('content', null, array('placeholder' => 'Mumble item text', 'class' => 'form-control')) }}
            </div>
        </div>
        <div class="col-md-2">
            <div class='form-group '>
                {{ Form::label('type', 'Type') }}
                {{ Form::select('type', array('first'=>'First','last'=>'Last'), null, array('class' => 'form-control')) }}
            </div>
        </div>
    </div>
  
    {{ Form::close() }}

@stop
