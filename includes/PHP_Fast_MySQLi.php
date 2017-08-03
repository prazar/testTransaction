<?php
/*
|
|	## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ##
|	##																		##
|	##																		##
|	##				    	  FOR FAST WORK WITH MYSQL.		    			##
|	##				    THE SCRIPT WORKING ON MYSQLi SYSTEM.				##
|	##				  13/12/2014. THANK'S FOR USING MY SCRIPT.				##
|	##						 Sorry for my bad English.						##
|	##																		##
|	## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## 
|	Version: 1.2 FIXED SOME BUGS WITH ESCAPTION
|
|	EXAMPLES & USAGE
|	^^^^^^^^^^^^^^^^
|	Connecting to mysql:
|		$conn = connect("localhost", "username", "password", "database");
|		`````````````````````````````````````````````````````````````````
|		"$conn" is the return of original mysqli_connect(...);
|		With each call to connect(...) function, automatically will close previous connection and making new.
|
|	Sending Query:
|		$q = query('SELECT * FROM table WHERE id="1"');
|		```````````````````````````````````````````````
|		This code will send the query via last mysql Connection.
|		If query is SELECT "$q" will be fetched Array. Then use foreach to getting your data.
|		If query is INSERT "$q" will be INSERTED ID.
|		If query is other  "$q" will be original return of mysqli_query(...).
|
|		YOU CAN USE CALLBACK FUNCTION TO GET ORIGINAL MYSQLI_QUERY RETURN.
|		$q = query('SELECT * FROM table WHERE id="1"', function($rows, $original_query_return){
|			if (!$rows) return;				 		// Return if rows are not found (are empty).
|			print_r($rows); 				 		// Array	
|			print_r($original_query_return); 		// Array
|		});
|
|		$q = query($conn, 'SELECT * FROM table WHERE id="1"');
|		DON'T USE  ^^^^^  CONNECTION VARIABLE IN QUERY.
|
|		$q = query('SELECT * FROM table WHERE id="1"', function($id, $original_query_return){
|			echo $id; 				  		 		// String (if INSERT)
|			print_r($original_query_return); 		// Array (sometimes) depends on the query.
|		});
|
|	Make Query but not send: Why???
|		$q = query_wait('INSERT INTO table (`date`, `title`, `count`) VALUES ("$?", "$?", $?);');
|		THIS VARIABLES YOU CAN APPLY LATER ____________________________________^^____^^___^^_____
|
|	Applying "query_wait()" variables:
|		$q 	= query_wait('INSERT INTO table (`date`, `title`, `count`) VALUES ("$?", "$?", $?);');
|		$w 	= query_write($q, '13.12.2014', 'mysql', '1');
|		$id = query($w);		// This will send query_wait(...) with query_write(...) parameters.
|
|
|
|	______________________________
|	DETAILED METHOD (some details)
|	^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
|	connect("localhost", "username", "password", "database");
|	$q  = query_wait('INSERT INTO table (`date`, `title`, `count`) VALUES ("$?", "$?", $?);');
|	$w  = query_write($q, '13.12.2014', 'mysql', '1');
|	$id = query($w, function($id, $original_query){
|		echo $id; 				  		// String (if INSERT)
|		print_r($original_query); 		// Array (sometimes) depends on the query.
|	});
|
|	____________________________________________
|	ALSO CAN USE (FAST method WITHOUT VARIABLES)
|	^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
|	connect("localhost", "username", "password", "database");
|	query_wait('INSERT INTO table (`date`, `title`, `count`) VALUES ("$?", "$?", $?);');
|	query_write('13.12.2014', 'mysql', '1');
|	query();
|
*/

{
	// Last CONNECTION parameters, ex. $db_param["last_conn"].
	$db_param = array();

	// CHECK IF IS CONNECTED
	function connected(){
		return @$GLOBALS["db_param"]["last_conn"]->errno === 0;
	}


	// CONNECTING and SAVING CONNECTION as last connection.
	function connect($host, $user, $pass, $db){
		@mysqli_close($GLOBALS["db_param"]["last_conn"]);
		$conn = mysqli_connect($host, $user, $pass, $db);
		mysqli_query($conn, "SET NAMES 'utf8'");
		$GLOBALS["db_param"]["last_conn"] = $conn;
		return $conn;
	}

	function request_connection(){
		if (connected() or connect( $GLOBALS['host'],
									$GLOBALS['user'], 
									$GLOBALS['pass'], 
									$GLOBALS['base'] )) return true; else return false;
	}

	// QUERY with AUTODETECTING CONNECTION and CALLBACK.
	function query($sql=null, $callback=null){
		// IF USED query(function(){...});
		if (is_callable($sql) || count(func_get_args()) == 0){
			$sql = $GLOBALS["db_param"]["last_query_wait"][0];
		}
		
		$q = @mysqli_query($GLOBALS["db_param"]["last_conn"], $sql);

		// if query is INSERT.
		if (substr($sql, 0, 7) == "INSERT "){
			$return = $GLOBALS["db_param"]["last_conn"]->insert_id;
		} else

		// if query is SELECT.
		if (substr($sql, 0, 7) == "SELECT "){
	   		if (mysqli_num_rows($q) > 0){$data = array(); while ($row = @mysqli_fetch_assoc($q)){
	   			$return[] = $row;}
	   		} else {
	   			$return = false;
	   		}
		} 

		// if query is not SELECT or INSERT.
		else { $return = $q; }

		// CALLBACK (return, original_query_return).
		if (isset($callback)){
			$callback($return, $q);
		}

		return $return;
	}

	function query_wait($query){
		$GLOBALS["db_param"]["last_query_wait"] 	   = array($query);
		$GLOBALS["db_param"]["last_query_wait_static"] = array($query);
		return array($query);
	}

	function query_write(){
		$args = func_get_args();
		if (is_array($args[0]) && array_key_exists(0, $args[0])){
			$splited = explode('$?', $args[0][0]);
			unset($args[0]);
			foreach ($args as $i => $value){
				if (is_array($value) && !array_key_exists(0, $value)){
					$array = $value;
				    $_keys   = '(';
					$_values = '(';
				    foreach ($array as $key => $value){
				        if (is_string($value)) $value = "'".mysqli_real_escape_string($GLOBALS["db_param"]["last_conn"], $value)."'";
				        $_keys   .= "`$key`, ";
				        $_values .= "$value, ";
				    }
				    $_keys   = preg_replace("/\, $/", ')', $_keys);
				    $_values = preg_replace("/\, $/", ')', $_values);
				    $value = "$_keys VALUES $_values";
				}
				array_splice($splited, $i+$i-1, 0, $value);
			}
		} else {
			$splited = explode('$?', $GLOBALS["db_param"]["last_query_wait_static"][0]);
			foreach ($args as $i => $value) {
				if (is_array($value) && !array_key_exists(0, $value)){
					$array = $value;
				    $_keys   = '(';
					$_values = '(';
				    foreach ($array as $key => $value){
				        if (is_string($value)) $value = "'".mysqli_real_escape_string($GLOBALS["db_param"]["last_conn"], $value)."'";
				        $_keys   .= "`$key`, ";
				        $_values .= "$value, ";
				    }
				    $_keys   = preg_replace("/\, $/", ')', $_keys);
				    $_values = preg_replace("/\, $/", ')', $_values);
				    $value = "$_keys VALUES $_values";
				}
				array_splice($splited, $i+$i+1, 0, $value);
			}
		}
		$GLOBALS["db_param"]["last_query_wait"][0] = implode('', $splited);
		return implode('', $splited);
	}
}
?>