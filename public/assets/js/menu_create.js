$(document).ready(function(){

        $('.menu_cont').children('ul').addClass('sortable ui-sortable');
        $('.menu_cont a').after('<span class="pull-right delete_menu_item fa fa-trash-o" title="Delete menu item"></span>');

        $('.sortable').nestedSortable({
            listType: 'ul',
            handle: 'a',
            items: 'li',
        });

        $('.add_menu_item').on('click',function(){
            $('.sortable').append('<li><a href="#">Menu item title</a><span class="pull-right delete_menu_item fa fa-trash-o" title="Delete menu item"></span></li>');
            getMenuHtml();
        });

        $('.menu_cont').on('click','.delete_menu_item',function(){
            $(this).parent().remove();
            getMenuHtml();
        });

        $('.menu_cont').on('click','a',function(event){
            event.preventDefault();
            $('.menu_item_title').val($(this).text());
            $('.menu_item_link').val($(this).attr('href'));
            $('a').removeClass('selected');
            $(this).addClass('selected');           
        });

        $('.menu_item_title').on('keyup',function(){
            $('a.selected').text($(this).val());
            getMenuHtml();
        });

        $('.menu_item_link').on('keyup',function(){
            if($(this).val()==''){
                link = "#"
            } else {
                link = $(this).val();
            }
            $('a.selected').attr('href',link);
            getMenuHtml();
        });

        $('.menu_file_tree').on('click','tr',function(){
            if($(this).data('type') != undefined){
                $('.menu_file_tree tr').css('outline','none');
                $(this).css('outline','solid 2px #5bc0de');

                $.ajax({
                    type:'post',
                    url:'/admin/dashboard/parents_links',
                    data: {
                        id: $(this).data('id'),
                        type: $(this).data('type')
                    },
                    success: function(res){
                        $('#menu_item_link').val(res);
                        $('a.selected').attr('href',res);
                        getMenuHtml();
                    }
                })               
            }
        });

    });

function getMenuHtml(){ 
    htmlVar = $('.menu_cont').clone();
    htmlVar.find('ul').removeAttr('class');
    htmlVar.find('li').removeAttr('class');
    htmlVar.find('a').removeAttr('class');
    htmlVar.find('span').remove();
    htmlVar.find('ul:empty').remove(); 
    $('.menu_html').val(htmlVar.html());       
}