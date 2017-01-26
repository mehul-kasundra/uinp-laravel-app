@extends('containers.admin')
@section('main')

    <h1 class="fa fa-bars pull-left"> All menus</h1>
    {{ link_to('admin/menus/create/', 'Add new menu', array('class'=>'pull-right btn btn-primary top20 left10')) }}

    <?php 
        $search_fields = array_flip($table_fields);
        $search_fields = array_combine($search_fields, $search_fields);
    ?>
    {{ Form::open(array('role' => 'form', 'url' => 'admin/menus', 'method' =>'get', 'class' => 'pull-right top20 table-search-form')) }}
        {{ Form::select('field',$search_fields,'',array('class'=>'form-control pull-left')) }} 
        {{ Form::text('search','',array('class'=>'form-control pull-left')) }}
        {{ Form::submit('Search',array('class'=>'btn btn-info')) }}
    {{ Form::close() }}  

    @if ($menus->count())
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    {{ Common_helper::sorting_table_fields($table_fields) }}
                    <th style="width: 110px;">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($menus as $menu)
                    <tr>
                        <td>{{ $menu->name }}</td>
                        <td>{{ $menu->created_at }}</td>
                        <td>{{ $menu->updated_at }}</td>
                        <td>
                            {{ link_to('admin/menus/edit/'.$menu->id, 'Edit', array('class' => 'btn btn-info btn-xs pull-left', 'title'=>'edit')) }}

                            {{ Form::open(array('url' => 'admin/menus/destroy/' . $menu->id, 'method' => 'DELETE')) }}
                                {{ Form::submit('Delete', array('class' => 'btn btn-danger btn-xs left10','onclick'=>'return confirm(\'Delete menu?\')?true:false;'))}}
                            {{ Form::close() }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $menus->links() }}
    @endif

@stop