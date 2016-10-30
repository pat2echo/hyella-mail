<dl>
	<dt>Attachments</dt>
	<?php $key = "attached_files"; if( isset( $data["file_info"][ $key ] ) && $data["file_info"][ $key ] ){
		$docs = explode(":::", $data["file_info"][ $key ] );
		$returning_html_data = "";
		
		if ( is_array($docs) ){
			foreach($docs as $k_doc => $v_doc){
				if( file_exists($data[ "pagepointer" ].$v_doc) ){
					$get_ext[1] = '';
					$get_ext = explode(".",$v_doc);
					
					switch( $get_ext[1] ){
					case "jpg":
					case "jpeg":
					case "png":
					case "gif":
					case "svg":
						$returning_html_data .= '<dd><img src="'.$site_url.$v_doc.'" height="60" /></dd>';
					break;
					}
					
					$returning_html_data .= '<dd><a href="'.$site_url.$v_doc.'"  class="view-file-link-1" target="_blank"  title="Click here to view uploaded files">&rarr; File '.($k_doc+1).' [ '.$get_ext[1].' ] '.number_format((filesize($data[ "pagepointer" ].$v_doc)/(1024*1024)),2).' MB</a></dd>';
					
				}
			}
		}
		echo $returning_html_data;
	} ?>
	
	<hr />
	<dd>Last Modified</dd> 
	<dd><i class="icon-time"></i> <?php $key = "modification_date"; if( isset( $data["file_info"][ $key ] ) && $data["file_info"][ $key ] )echo date( "d-M-Y", doubleval( $data["file_info"][ $key ] ) ); ?></dd>
	<dd><i class="icon-user"></i> <?php $key = "modified_by"; if( isset( $data["file_info"][ $key ] ) && $data["file_info"][ $key ] ){
		$d = get_site_user_details( array( "id" => $data["file_info"][ $key ] ) );
		if( isset( $d["firstname"] ) && isset( $d["lastname"] ) ){
			echo $d["firstname"] . " " . $d["lastname"];
		}
	}?>
	</dd>
	<br />
	<dd>Created</dd>
	<dd><i class="icon-time"></i> <?php $key = "creation_date"; if( isset( $data["file_info"][ $key ] ) && $data["file_info"][ $key ] )echo date( "d-M-Y", doubleval( $data["file_info"][ $key ] ) ); ?></dd>
	<dd><i class="icon-user"></i> <?php $key = "created_by"; if( isset( $data["file_info"][ $key ] ) && $data["file_info"][ $key ] ){
		$d = get_site_user_details( array( "id" => $data["file_info"][ $key ] ) );
		if( isset( $d["firstname"] ) && isset( $d["lastname"] ) ){
			echo $d["firstname"] . " " . $d["lastname"];
		}
	}?></dd>
	
	<hr />
	<dt>Description</dt>
	<dd><?php $key = "description"; if( isset( $data["file_info"][ $key ] ) && $data["file_info"][ $key ] )echo $data["file_info"][ $key ]; ?></dd>
 </dl>