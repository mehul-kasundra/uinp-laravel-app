@extends('containers.admin')
 
@section('title') Tags @stop

@section('styles')
    <style type="text/css">
        .table th {
          min-width: 115px;
        }
    </style>
@stop
 
@section('main')
    <h1 class="fa fa-microphone"> Mumble</h1>

    {{ link_to('admin/mumble/create/', 'Add mumble row', array('class'=>'pull-right btn btn-primary top20 left10')) }}

    <?php 
        $search_fields = array_flip($table_fields);
        $search_fields = array_combine($search_fields, $search_fields);
    ?>
    {{ Form::open(array('role' => 'form', 'url' => 'admin/mumble', 'method' =>'get', 'class' => 'pull-right top20 table-search-form')) }}
        {{ Form::select('field',$search_fields,'',array('class'=>'form-control pull-left')) }} 
        {{ Form::text('search','',array('class'=>'form-control pull-left')) }}
        {{ Form::submit('Search',array('class'=>'btn btn-info')) }}
    {{ Form::close() }}  

    @if ($mumbleRows->count())
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    {{ Common_helper::sorting_table_fields($table_fields) }}
                    <th style="min-width: 130px;">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($mumbleRows as $item)
                    <tr>
                        <td>{{ $item->content }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->updated_at }}</td>
                        <td>
                            {{ link_to('admin/mumble/edit/'.$item->id, 'Edit', array('class' => 'btn btn-info btn-xs pull-left left10', 'title'=>'edit')) }}
                            {{ link_to('admin/mumble/delete/'.$item->id, 'Delete', array('class' => 'btn btn-danger btn-xs pull-left left10', 'title'=>'delete','onclick'=>'return confirm(\'Delete?\')?true:false;' )) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $mumbleRows->links() }}    
    @endif
@stop