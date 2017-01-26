<?php

class Common_helper {

/**
 * Display table header with sorting links.
 *
 * @param  array  $table_fields
 * @return text (html)
 */
    public static function sorting_table_fields($table_fields) {

		$sort = Input::get('sort');
    	$order = Input::get('order');
    	$page = Input::get('page');
        $field = Input::get('field');
        $search = Input::get('search');

    	$order_class = $order=='desc'?'glyphicon glyphicon-chevron-up right5':'glyphicon glyphicon-chevron-down right5';    	
    	$order = $order=='desc'?'asc':'desc';
        if(!empty($page)){
            $order = $page?$order.'&page='.$page:$order;
        } 
        if(!empty($field)&&!empty($search)){
            $order = $order.'&field='.$field.'&search='.$search;
        }    	

    	$result = '';
    	foreach ($table_fields as $key => $val) {
    		$order_icon = $key==$sort?'<span class="'.$order_class.'"></span>':'';
    		$result.= '<th>'.$order_icon.'<a href="'.Request::url().'?sort='.$key.'&order='.$order.'">'.$key.'</a></th>';
    	}
    	return $result;
    }


/**
 * Save upploaded file
 *
 * @param  obj $files, string $type
 * @return array
 */
    public static function fileUpload($file,$folder,$name) {
        if(!empty($file)){
            $validator = Validator::make(
                array(
                    'attachment' => $file,
                    'extension'  => \Str::lower($file->getClientOriginalExtension()),
                ),
                array(
                    'attachment' => 'required|max:1000',
                    'extension'  => 'required|in:jpg,jpeg,bmp,png,gif',
                )
            ); 

            if($validator->fails()){
                return array('errors'=>$validator->messages());
            }
            $destinationPath = 'uploads/'.$folder.'/';
            if(!is_dir($destinationPath)){
                File::makeDirectory($destinationPath , 0775, true);
            }
            $filename = $name.'.'.$file->getClientOriginalExtension();
            $uploadSuccess = $file->move($destinationPath, $filename);
            if($uploadSuccess) {
                 $fileUploaded = $destinationPath.$filename;
            }else{
                $fileUploadErrors = $filename;
            }
        }     
        if(isset($fileUploadErrors)){
            return array('errors'=>'File upload error');
        }
        return array('name'=>$filename, 'path'=>$fileUploaded);
    }

/**
 * Cut string whithout break words
 *
 * @param  string $string, int $limit
 * @return string
 */
    public static function cropStr($string, $limit){
        $framePosition = stripos ($string,'<iframe');    
        if(!empty($framePosition)){
            $string = substr($string,0,$framePosition);   //берём все что до iframe
        }
        $substring_limited = substr($string,0, $limit);        //режем строку от 0 до limit  
        $result =  substr($substring_limited, 0, strrpos($substring_limited, ' ' ));    //берем часть обрезанной строки от 0 до последнего пробела
        return Common_helper::close_tags($result.'...');
    }  

/**
 * Close HTML tags
 *
 * @param  string $content
 * @return string
 */
    public static function close_tags($content)
    {
        $position = 0;
        $open_tags = array();
        //теги для игнорирования
        $ignored_tags = array('br', 'hr', 'img');
 
        while (($position = strpos($content, '<', $position)) !== FALSE)
        {
            //забираем все теги из контента
            if (preg_match("|^<(/?)([a-z\d]+)\b[^>]*>|i", substr($content, $position), $match))
            {
                $tag = strtolower($match[2]);
                //игнорируем все одиночные теги
                if (in_array($tag, $ignored_tags) == FALSE)
                {
                    //тег открыт
                    if (isset($match[1]) AND $match[1] == '')
                    {
                        if (isset($open_tags[$tag]))
                            $open_tags[$tag]++;
                        else
                            $open_tags[$tag] = 1;
                    }
                    //тег закрыт
                    if (isset($match[1]) AND $match[1] == '/')
                    {
                        if (isset($open_tags[$tag]))
                            $open_tags[$tag]--;
                    }
                }
                $position += strlen($match[0]);
            }
            else
                $position++;
        }
        //закрываем все теги
        foreach ($open_tags as $tag => $count_not_closed)
        {
            $content .= str_repeat("</{$tag}>", $count_not_closed);
        }
 
        return $content;
    }

    /**
     * Escape special chars for XML
     *
     * @param  string $string
     * @return string
     */
    public static function escapeXMLcars($string){
        $string = strip_tags($string);
        $string = str_replace("&laquo;","",$string);
        $string = str_replace("&raquo;","",$string);
        $string = str_replace(
            array("&",     "<",    ">",    '"',      "'"),
            array("&amp;", "&lt;", "&gt;", "&quot;", "&apos;"), 
            $string
        );
        return Common_helper::cropStr(strip_tags($string),999); 
    }
}