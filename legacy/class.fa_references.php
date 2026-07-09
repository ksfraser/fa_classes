<?php

/**
 * @deprecated 2026-07-09 Replaced by RefsDTO + RefsRepository (src/FrontAccounting/DTO/Refs.php, Repository/RefsRepository.php).
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
