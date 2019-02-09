<?php

$emails = array();
$lines = file(dirname(__FILE__)."/".$argv[1]);
foreach($lines as $line)
{
	$line = substr($line, 1);
	$line = substr($line, 0, strlen($line)-2);
	$line = str_replace(',,',',"",',$line);
	$line = str_replace(',,',',"",',$line);
	$fields = explode('","', $line);

	if($fields[0]=='Family Name')
	{
		continue;
	}

	//do kids here so they can be overwritten by parents if same email
	$kid_id = 13;
	while($kid_id)
	{
		if(isset($fields[$kid_id]))
		{
			if($fields[$kid_id])
			{
				$emails[$fields[$kid_id]] = $fields[$kid_id-2];
			}
			$kid_id = $kid_id + 3;
		}
		else
		{
			$kid_id = false;
		}
	}

	if($fields[3])
	{
		$emails[$fields[3]] = $fields[1];
	}

	if(isset($fields[7]) && $fields[7])
	{
		$emails[$fields[7]] = $fields[5];
	}
	if(isset($fields[10]) && $fields[10])
	{
		$emails[$fields[10]] = $fields[8];
	}
}

$str_export = '';
foreach($emails as $email=>$name)
{
	$str_export = $str_export.'"'.$email.'","'.$name.'"'."\n";
}

file_put_contents(dirname(__FILE__)."/".$argv[2], $str_export);