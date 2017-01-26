@extends('containers.admin')

@section('styles')
    <style>
        .table th{
            min-width:0px;
        }
    </style>
@stop

@section('main')

    <h1 class="fa fa-gear pull-left"> parser2 Data</h1>
    {{ link_to('admin/parser2/parse/', 'Parse', array('class'=>'pull-right btn btn-success top20 left10')) }}
    {{ link_to('admin/parser2/create/', 'Add new url', array('class'=>'pull-right btn btn-primary top20 left10')) }}

    <?php 
        $search_fields = array_flip($table_fields);
        $search_fields = array_combine($search_fields, $search_fields);
    ?>
    {{ Form::open(array('role' => 'form', 'url' => 'admin/parser2', 'method' =>'get', 'class' => 'pull-right top20 table-search-form')) }}
        {{ Form::select('field',$search_fields,'',array('class'=>'form-control pull-left')) }} 
        {{ Form::text('search','',array('class'=>'form-control pull-left')) }}
        {{ Form::submit('Search',array('class'=>'btn btn-info')) }}
    {{ Form::close() }}  

    @if ($data->count())
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    {{ Common_helper::sorting_table_fields($table_fields) }}
                    <th style="width: 270px;">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->url }}</td>
                        <td>{{ $item->publish?'<span class="fa fa-check">':'<span class="fa fa-times">' }}</td>
                        <td>{{ $item->translate?'<span class="fa fa-check">':'<span class="fa fa-times">' }}</td>
                        <td>{{ $item->disabled?'<span class="fa fa-check">':'<span class="fa fa-times">' }}</td>
                        <td>{{ $item->username }}</td>
                        <td> 
                            {{ link_to('admin/parser2/parse/'.$item->id, 'Test', array('class' => 'btn btn-success btn-xs pull-left left10', 'title'=>'edit')) }}                       
                            {{ link_to('admin/parser2/parse/'.$item->id.'/links', 'Test Links', array('class' => 'btn btn-success btn-xs pull-left left10', 'title'=>'edit')) }}
                            {{ link_to('admin/parser2/edit/'.$item->id, 'Edit', array('class' => 'btn btn-info btn-xs pull-left left10', 'title'=>'edit')) }}
                            {{ Form::open(array('url' => 'admin/parser2/destroy/' . $item->id, 'method' => 'DELETE')) }}
                                {{ Form::submit('Delete', array('class' => 'btn btn-danger btn-xs left10','onclick'=>'return confirm(\'Delete item?\')?true:false;'))}}
                            {{ Form::close() }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $data->links() }}    
    @endif

@stop