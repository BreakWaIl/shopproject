<?php

function tree($arr,$pid=0,$lev=0){

	static $list =array();
	foreach ($arr as  $value) {
		if($value['panr_id']==$pid){
			$value['lev']==$lev;
			$list[]=$value;
			tree($arr,$value['id'],$lev+1)
		}


	}
	return $list;
}



?>