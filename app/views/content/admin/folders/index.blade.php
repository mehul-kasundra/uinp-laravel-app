@extends('containers.admin')

@section('main')

    <h1 class="fa fa-folder-o pull-left"> All folders</h1>
    {{ link_to('admin/folders/create/', 'Add new folder', array('class'=>'pull-right btn btn-primary top20 left10')) }}

    <?php 
        $search_fields = array_flip($table_fields);
        $search_fields = array_combine($search_fields, $search_fields);
    ?>
    {{ Form::open(array('role' => 'form', 'url' => 'admin/folders', 'method' =>'get', 'class' => 'pull-right top20 table-search-form')) }}
        {{ Form::select('field',$search_fields,'',array('class'=>'form-control pull-left')) }} 
        {{ Form::text('search','',array('class'=>'form-control pull-left')) }}
        {{ Form::submit('Search',array('class'=>'btn btn-info')) }}
    {{ Form::close() }}  

    @if ($folders->count())
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    {{ Common_helper::sorting_table_fields($table_fields) }}
                    <th style="min-width: 130px;">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($folders as $folder)
                    <tr>
                        <td>{{ $folder->title }}</td>
                        <td>{{ $folder->alias }}</td>
                        <td>{{ $folder->user }}</td>
                        <td>{{ $folder->created_at }}</td>
                        <td>{{ $folder->updated_at }}</td>
                        <td>
                            {{ link_to('admin/folders/edit/'.$folder->id, 'Edit', array('class' => 'btn btn-info btn-xs pull-left left10', 'title'=>'edit')) }}
                            @if($folder->undeletable==0)
                                {{ Form::open(array('url' => 'admin/folders/destroy/' . $folder->id, 'method' => 'DELETE')) }}
                                    {{ Form::submit('Delete', array('class' => 'btn btn-danger btn-xs left10','onclick'=>'return confirm(\'Delete folder?\')?true:false;'))}}
                                {{ Form::close() }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $folders->links() }}    
    @endif

@stop