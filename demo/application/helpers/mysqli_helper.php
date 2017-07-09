<?php

	/*
	 * Limpiar conexiones mysql, aveces la llamada a sp produce 
	 * mas de un resulset que hay que limpiar
	 * 
	 * clean_mysqli_connection($this->db->conn_id); 
	 * 
	 */
	function clean_mysqli_connection( $dbc )
	{
		while( mysqli_more_results($dbc) )
		{
			if(mysqli_next_result($dbc))
			{
				$result = (object) mysqli_use_result($dbc);

				if( get_class($result) == 'mysqli_stmt' )
				{
					mysqli_stmt_free_result($result);
				}
				else
				{
					unset($result);
				}
			} 
		}
	}
