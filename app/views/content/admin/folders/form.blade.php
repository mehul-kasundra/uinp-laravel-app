@extends('containers.admin')
 
@section('title') Create folder @stop
 
@section('main')

    @if (Request::segment(3)=='create')
        {{ Form::open(array('role' => 'form', 'url' => 'admin/folders/store')) }}
    @else
        {{ Form::model($folder, array('role' => 'form', 'url' => 'admin/folders/update/' . $folder->id, 'method' => 'PUT')) }}
    @endif
 
    <div style="overflow: hidden;">
        <h1 class="fa fa-edit"> {{Request::segment(3)=='create'?'Add':'Edit'}} folder</h1>

        <div class='form-group pull-right top20'>
            {{ Form::submit('Save', array('class' => 'btn btn-primary')) }}
        </div>
    </div>

    <div class="row">
        <div class='form-group col-md-5'>
            {{ Form::label('title', 'Title') }}
            {{ Form::text('title', null, array('placeholder' => 'Title', 'class' => 'form-control', 'id' => 'folder-title-input')) }}
        </div>
        <div class="col-md-1 generate-alias">
            <a class="btn btn-default" onClick="generateSlug()" style="margin-top:25px">Generate</a>
        </div>

        <div class='form-group col-md-6'>
            {{ Form::label('alias', 'Alias') }}
            {{ Form::text('alias', null, array('placeholder' => 'Alias', 'class' => 'form-control', 'id' => 'folder-alias-input')) }}
        </div>


        <div class='col-md-12'>
            <!-- SEO -->
            @include('content.admin.seo.main')
            <!-- end seo -->
        </div>

        <div class='form-group col-md-12'>
            {{ Form::textarea('text', null, array('class' => 'form-control folder_desc')) }}
        </div>
    </div>

    <div class='form-group'>        
        {{ $tree }}
    </div>  
 
    {{ Form::close() }}

@stop

@section('scripts')
    <script type="text/javascript" src="{{ asset('packages/tinymce/tinymce.min.js') }}"></script>
    <script>
    tinymce.init({
        selector: ".folder_desc",
        height : 300,
        plugins: [
            "advlist autolink lists link responsivefilemanager image charmap print preview anchor textcolor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste jbimages, pagebreak"
        ],
        external_filemanager_path:"/packages/filemanager/",
        filemanager_title:"Менеджер файлов" ,
        external_plugins: { "filemanager" : "/packages/tinymce/plugins/responsivefilemanager/plugin.min.js"},
        pagebreak_separator: "<pagebreak>",
        //outdent indent
        toolbar: "insertfile undo redo | styleselect | fontsizeselect | fontselect | backcolor | forecolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image jbimages responsivefilemanager",
        relative_urls: false,
        //resize: false,
        forced_root_block : 'div'
    });

    function generateSlug () {
        var str = $('#folder-title-input').val();
        if(str.length>1){
            var space = '_';
            str = str.toLowerCase();
            var transl = {
                'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'e', 'ж': 'zh', 
                'з': 'z', 'и': 'i', 'й': 'j', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n',
                'о': 'o', 'п': 'p', 'р': 'r','с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h',
                'ц': 'c', 'ч': 'ch', 'ш': 'sh', 'щ': 'sh','ъ': '', 'ы': 'y', 'ь': '', 'э': 'e', 'ю': 'yu', 'я': 'ya'
            }
            var link = '';
            for (var i = 0; i < str.length; i++) {
                if(/[а-яё]/.test(str.charAt(i))) { //если текущий символ - русская буква, то меняем его
                    link += transl[str.charAt(i)];
                } else if (/[a-z0-9]/.test(str.charAt(i))) {
                    link += str.charAt(i); //если текущий символ - английская буква или цифра, то оставляем как есть
                } else {
                    if (link.slice(-1) !== space) link += space; // если не то и не другое то вставляем space
                }
            }
            $('#folder-alias-input').val(link);
        }
    }
    </script>
@stop