@extends('containers.admin')
 
@section('title') Create menu @stop

@section('styles')
    <link href="{{ asset('assets/css/menu_create.css') }}" rel="stylesheet">
@stop

@section('scripts')
    <script type="text/javascript" src="{{ asset('packages/jquery-ui/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery.mjs.nestedSortable.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/menu_create.js') }}"></script>
@stop
 
@section('main')

@if (Request::segment(3)=='create')
    {{ Form::open(array('role' => 'form', 'url' => 'admin/menus/store')) }}
@else
    {{ Form::model($menu, array('role' => 'form', 'url' => 'admin/menus/update/' . $menu->id, 'method' => 'PUT')) }}
@endif

<div class='col-md-12'>
    <h1 class='fa fa-bars'> {{Request::segment(3)=='create'?'Add':'Edit'}} menu</h1>
    <div class="form-group pull-right top20">
        {{ Form::submit('Save', array('class' => 'btn btn-primary pull-left right5')) }}
    </div>
</div>

@if ($errors->has())
    @foreach ($errors->all() as $error)
        <div class='col-md-12 bg-danger alert'>{{ $error }}</div>
    @endforeach
@endif 

<div class='col-md-4'>

    <div class='form-group'>
         <label>Menu name</label>
        {{ Form::text('name', null, array('class' => 'form-control', 'required'=>'required')) }}
    </div>
    
    <div class="panel panel-default">
        <div class="panel-heading">Menu item settings</div>
        <div class="panel-body">
            <label>Menu item title</label>
            <input type="text" class="menu_item_title form-control">
            <br>
            <label>Menu item link</label>
            <input type="text" class="menu_item_link form-control" id="menu_item_link">
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Menu visualisation</div>
        <div class="panel-body">
            @if (Request::segment(3)=='create')
                <div class="menu_cont"><ul class="sortable"></ul></div>
            @else
                <div class="menu_cont">{{ $menu->content }}</div>
            @endif
        </div>
    </div> 

    <div class="btn btn-default add_menu_item">Add menu item</div>   
 
</div>

<div class='col-md-8'>
    <label>Menu html <a class="toggle_menu_html">(show)</a></label>
    <div class='form-group menu_html_cont'>
        {{ Form::textarea('content', null, array('class' => 'form-control menu_html')) }}
    </div>

    <div class='form-group menu_file_tree'>
        {{ $file_tree }}
    </div>    
</div>
{{ Form::close() }}

<script>
    $(document).ready(function(){
        $('.toggle_menu_html').on('click',function(){
            if($(this).text()=='(show)'){
                $('.menu_html_cont').show();
                $(this).text('(hide)');
            } else {
                $('.menu_html_cont').hide();
                $(this).text('(show)');
            }
        });
    });
</script>
@stop