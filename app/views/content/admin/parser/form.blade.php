@extends('containers.admin')
 
@section('title') Add parser data @stop
 
@section('main')

    @if (Request::segment(3)=='create')
        {{ Form::open(array('role' => 'form', 'url' => 'admin/parser/store')) }}
    @else
        {{ Form::model($parser, array('role' => 'form', 'url' => 'admin/parser/update/' . $parser->id, 'method' => 'PUT')) }}
    @endif
 
    <div style="overflow: hidden;">
        <h1 class="fa fa-edit"> {{Request::segment(3)=='create'?'Add':'Edit'}} parser data</h1>

        <div class='form-group pull-right top20'>
            {{ Form::submit('Save', array('class' => 'btn btn-primary')) }}
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="form-group col-md-12">
                {{ Form::label('url', 'RSS URL') }}
                {{ Form::text('url', null, array('placeholder' => 'URL', 'class' => 'form-control')) }}
            </div>
            <div class='form-group col-md-12'>
                {{ Form::label('author', 'Author') }}
                {{ Form::select('author', $users,isset($parser->author)?$parser->author:'',array('class'=>'form-control')); }}
            </div>
            <div class='form-group col-md-12'>
                {{ Form::label('min_chars', 'Minimal chars') }}
                {{ Form::text('min_chars',null,array('class'=>'form-control', 'placeholder' => 'Minimal chars',)); }}
            </div>
        </div>
        
        <div class="col-md-5">
            <div class="form-group col-md-4" style="margin-top:30px">                
                {{ Form::checkbox('publish', 1, null, array('id' => 'publish')) }}
                {{ Form::label('publish', 'Publish now') }}
            </div>
            <div class="form-group col-md-4" style="margin-top:30px">                
                {{ Form::checkbox('translate', 1, null, array('id' => 'translate')) }}
                {{ Form::label('translate', 'Translate') }}
            </div>
            <div class="form-group col-md-4" style="margin-top:30px">            
                {{ Form::checkbox('remove_links', 1, null, array('id' => 'remove_links')) }}
                {{ Form::label('remove_links', 'Remove links') }}
            </div>
            <div class="form-group col-md-4" style="margin-top:30px">                
                {{ Form::checkbox('disabled', 1, null, array('id' => 'disabled')) }}
                {{ Form::label('disabled', 'Disable') }}
            </div>
            <div class="form-group col-md-4" style="margin-top:30px">                
                {{ Form::checkbox('vk', 1, null, array('id' => 'vk')) }}
                {{ Form::label('vk', 'VK') }}
            </div>
            <div class="form-group col-md-5" style="margin-top:30px">                
                {{ Form::checkbox('only_with_images', 1, null, array('id' => 'only_with_images')) }}
                {{ Form::label('only_with_images', 'Only with images') }}
            </div>
        </div>
        <div class="form-group col-md-12" style="margin:0 15px 20px 15px">        
            {{ Form::label('parse_rules', 'Article parse rules') }}
            {{ Form::text('parse_rules', null, array('placeholder' => 'Leave empty if dont need', 'class' => 'form-control')) }}
        </div>
        <div class="form-group col-md-12" style="margin:0 15px 20px 15px">        
            {{ Form::label('meta_keywords', 'Keywords meta name') }}
            {{ Form::text('meta_keywords', null, array('placeholder' => 'Leave empty if dont need', 'class' => 'form-control')) }}
        </div> 
        <div class="form-group col-md-12" style="margin:0 15px 20px 15px">        
            {{ Form::label('remove_rule', 'Remove rule (regular expression)') }}
            {{ Form::text('remove_rule', null, array('placeholder' => 'Leave empty if dont need', 'class' => 'form-control')) }}
        </div> 
        <div class="form-group col-md-12" style="margin:0 15px">        
            {{ $tree }}
        </div> 
    </div>
 
    {{ Form::close() }}

@stop