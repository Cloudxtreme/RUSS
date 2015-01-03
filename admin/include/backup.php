<?php
require_once("constants.php");


/* backup the db OR just a table */

    $tables = '*';
	$link = mysql_connect(DB_SERVER,DB_USER,DB_PASS);
	mysql_select_db(DB_NAME,$link);

	//get all of the tables
	if($tables == '*')
	{
		$tables = array();
		$result = mysql_query('SHOW TABLES');
		while($row = mysql_fetch_row($result))
		{
			$tables[] = $row[0];
		}
	}
	else
	{
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}

	//cycle through
	foreach($tables as $table)
	{
		$result = mysql_query('SELECT * FROM '.$table);
		$num_fields = mysql_num_fields($result);

		$return.= 'DROP TABLE '.$table.';';
		$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
		$return.= "\n\n".$row2[1].";\n\n";

		for ($i = 0; $i < $num_fields; $i++)
		{
			while($row = mysql_fetch_row($result))
			{
				$return.= 'INSERT INTO '.$table.' VALUES(';
				for($j=0; $j<$num_fields; $j++)
				{
					$row[$j] = addslashes($row[$j]);
					$row[$j] = preg_replace("/\n/","/\\n/",$row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					if ($j<($num_fields-1)) { $return.= ','; }
				}
				$return.= ");\n";
			}
		}
		$return.="\n\n\n";
	}

	//save file

   $supportsGzip = strpos( $_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip' ) !== false;

    if ( $supportsGzip ) {
    $content = gzencode( trim( preg_replace( '/\s+/', ' ', $return ) ), 9);
    $handle = fopen('../backup/db-backup-'.(date("Y-m-d_h-i-s",time())).'.sql.gz','w+');
        fwrite($handle,$content);
	    fclose($handle);
    } else {
        $handle = fopen('../backup/db-backup-'.(date("Y-m-d_h-i-s",time())).'.sql','w+');
        fwrite($handle,$return);
	    fclose($handle);
    }
    header("Location: ../index.php?id=7&cl=2");

?>