<?php

/**
 * @deprecated Replaced by DTO + Repository pattern in src/FrontAccounting/{DTO,Repository}/
 */
$path_to_root="../..";

class fa_references
{
	//type found by systypes::...
	function get_next( $type )
	{
		return references::get_next( $type );
	}
}

?>
