
<link rel="stylesheet" href="/packages/jstree/themes/default/style.css" />

<?php 	$oldFolderId = Input::old('folder_id');
		if(!empty($oldFolderId)){ 
			$parentId = $oldFolderId;
		} elseif(isset($parent)) { 
			$parentId = $parent;
		} else {
			$parentId = 0;
		}
?>

<div class="panel panel-default">
<!-- Default panel contents -->
  	<div class="panel-heading">{{ isset($parent)?'Select parent folder':'Folders tree' }}</div>
  	<div class="panel-body">
		<div class="col-md-{{isset($inMenuEdit)||isset($parent)?5:3}} jstree">	
			<ul>
				<li data-jstree='{"icon":"fa fa-folder-open-o" }'>						
					<a href="#" folderid="0" alias="/" created="2014-04-07 12:00:00" updated="2014-04-07 12:00:00" class="{{ $parentId==0?'jstree-clicked':'' }}">
						Root
					</a>

			    	<?php function getFoldersTree($folders, $parent_id, $current_folder_id, $globalParentId ){ ?>
			    		<ul>
							@foreach ($folders as $key => $folder)
								@if ($current_folder_id!=$folder->id)
									@if ($folder->parent_folder_id == $parent_id)
										<li data-jstree='{ "icon":"fa fa-folder-open-o" }'>											
											<a href="#"  class="{{ $folder->id==$globalParentId?'jstree-clicked':'' }}" alias="{{ $folder->alias }}" folderid="{{ $folder->id }}" created="{{ $folder->created_at }}", updated="{{ $folder->updated_at }}">
												{{ $folder->title }}
											</a>
											<?php unset($folders[$key]) ?>

											@if(!empty($folders))
												<?php getFoldersTree($folders, $folder->id, $current_folder_id, $globalParentId) ?>
											@endif
										</li>
								 	@endif
								@endif			
							@endforeach
						</ul>
					<?php } ?>

					<?php !isset($current_folder_id)? $current_folder_id = 0 : $current_folder_id ?>
					<?php getFoldersTree($folders, 0, $current_folder_id, $parentId) ?>
				</li>
			</ul>
		</div>
		<div class='col-md-{{isset($inMenuEdit)||isset($parent)?7:9}} folder_content'>
			@if (isset($parent))
				<div class="folder_info">
				<br>
					<label>Folder alias:</label> <span class="folder_alias"></span><br>
					<label>Folder name:</label> <span class="folder_name"></span><br>
					<label>Folder created:</label> <span class="folder_created"></span><br>
					<label>Folder updated:</label> <span class="folder_updated"></span><br>
					<input name="folder_id" type="hidden" id="parent_folder_id" value="{{ $parentId }}">				
				</div>				
			@endif
		</div>

  	</div>
</div>

<script src="/packages/jstree/jstree.min.js"></script>
<script type="text/javascript">

	var parent 		= {{ isset($parent)?'1':'0' }};
	var inMenuEdit 	= {{ isset($inMenuEdit)?'1':'0' }};	

	function getTreeList(id,page){
		$('.folder_content').html('<img style="display:block; margin:0 auto; opacity:0.1" src="/assets/images/loading.gif">');
		$.ajax({
			type:'post',
			url:'{{URL::to("/")}}/admin/dashboard/tree_list',
			data: {
				id:id,
				inMenuEdit:inMenuEdit,
				page:page
			},
			success:function(ret){
				$('.folder_content').html(ret);
			},
		});
	}

	function selectTreeNode(element){		
		if(parent == 0){
			getTreeList(element.attr('folderid'));
		} else {
			$('.folder_alias').text(element.attr('alias'));
			$('.folder_name').text(element.text());
			$('.folder_created').text(element.attr('created'));
			$('.folder_updated').text(element.attr('updated'));
			$('#parent_folder_id').val(element.attr('folderid'));
		}
	}


	$(function () {

		$('.jstree').on('loaded.jstree', function (e, data) {
			if(parent!=0){
				var selected = data.instance._data.core.selected;
			    var selected = data.instance.get_node(selected);

			    $('.folder_alias').text(selected.a_attr.alias);
				$('.folder_name').text(selected.text);
				$('.folder_created').text(selected.a_attr.created);
				$('.folder_updated').text(selected.a_attr.updated);
				$('#parent_folder_id').val(selected.a_attr.folderid);
			}		
		}).jstree({
			"core" : {
				"check_callback" : true,
				"themes" : {					
					"variant" : "large"
				}
			}
			//"plugins" : [ "dnd" ]
		});		

		if(parent == 0){		
			getTreeList(0);
		}

		$('.panel-body').on('click','.folder_content .pagination a',function(event){
			event.preventDefault();
			var selectedFolder = $('.jstree-clicked').attr('folderid');
			var page = $(this).attr('href').match(/page=(.*)/)[1];
			getTreeList(selectedFolder,page);
		});

		$('.panel-body').on('click','.jstree-anchor',function(){
			selectTreeNode($(this));
		});

	});
</script>
