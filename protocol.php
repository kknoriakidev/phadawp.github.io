<?php



function lgsl_query_live($ip, $q_port, $request)
{			
	$server = lgsl_query_direct($ip, $q_port, $request);

	if (!$server)
	{
		$status      = 0;
		$server      = array();
		$server['s'] = array('name' => 'Сервер отключен', 'map' => '-', 'players' => 0, 'playersmax' => 0, 'status' => 0);
		$server['p'] = array();
		
		return $server;
	}

	if (isset($server['s']))
	{
		$tmp = str_replace("\\", "/", $server['s']['map']); // REMOVE ANY
		$tmp = explode("/", $tmp);                          // FOLDERS
		$server['s']['map'] = htmlspecialchars(array_pop($tmp), ENT_QUOTES);              // FROM MAP

		$server['s']['playersmax'] = max(0, intval($server['s']['playersmax']));
		$server['s']['status']  = 1;
	}
	
	return $server;
}

function lgsl_query_direct($ip, $q_port, $request)
{

	global $lgsl_fp;

	$lgsl_fp = @fsockopen("udp://$ip", $q_port, $errno, $errstr, 1);

	if (!$lgsl_fp) return FALSE;

    stream_set_timeout($lgsl_fp, 0, 500000);

	stream_set_blocking($lgsl_fp, TRUE);

	$server = array();

	if (strpos($request, "s") !== FALSE && !isset($server['s']))
	{
	  $server = call_user_func("lgsl_query", "s", $server);

	  if (!$server) { @fclose($lgsl_fp); return FALSE; }
	}

	if (strpos($request, "p") !== FALSE && !isset($server['p']))
	{

	  if (isset($server['s']) && !$server['s']['players']) // SERVER EMPTY SO SKIP REQUESTING PLAYER DETAILS
		$server['p'] = array();
	  else
		$server = call_user_func("lgsl_query", "p", $server);

	  if (!$server) { @fclose($lgsl_fp); return FALSE; }
	}

	@fclose($lgsl_fp);

	return $server;
}

function lgsl_query($request, $server)
{

    global $lgsl_fp;
	
      if ($request == "p")
      {
        fwrite($lgsl_fp, "\xFF\xFF\xFF\xFF\x55\xFF\xFF\xFF\xFF"); // PREVIOUSLY "\xFF\xFF\xFF\xFF\x57"

        $challenge_packet = fread($lgsl_fp, 4096);

        if (!$challenge_packet) 
			return FALSE;

        $challenge_code = substr($challenge_packet, 5, 4);
      }

          if ($request == "s") fwrite($lgsl_fp, "\xFF\xFF\xFF\xFF\x54Source Engine Query\x00");
      elseif ($request == "p") fwrite($lgsl_fp, "\xFF\xFF\xFF\xFF\x55{$challenge_code}");

    $packet_temp  = array();
    $packet_type  = 0;
    $packet_count = 0;
    $packet_total = 4;

    do
    {
      $packet = fread($lgsl_fp, 4096);
      $packet_temp[$packet_count] = $packet;
      $packet_count ++;

          if (substr($packet, 0,  4) == "\xFF\xFF\xFF\xFF") { $packet_total = 1;                     $packet_type = 1; } // SINGLE PACKET - HL1 OR HL2
      elseif (substr($packet, 9,  4) == "\xFF\xFF\xFF\xFF") { $packet_total = ord($packet[8]) & 0xF; $packet_type = 2; } // MULTI PACKET  - HL1 ( TOTAL IS LOWER NIBBLE OF BYTE )
      elseif (substr($packet, 12, 4) == "\xFF\xFF\xFF\xFF") { $packet_total = ord($packet[8]);       $packet_type = 3; } // MULTI PACKET  - HL2
      elseif (substr($packet, 18, 2) == "BZ")               { $packet_total = ord($packet[8]);       $packet_type = 4; } // BZIP PACKET   - HL2
    }
    while ($packet && $packet_count < $packet_total);

    if ($packet_type == 0) return FALSE; // INCOMPLETE OR UNKNOWN RESPONSE

    $buffer = array();

    foreach ($packet_temp as $packet)
    {
          if ($packet_type == 1) { $packet_index = 0; }
      elseif ($packet_type == 2) { $packet_index = ord($packet[8]) >> 4; $packet = substr($packet, 9);  } // ( INDEX IS UPPER NIBBLE OF BYTE )
      elseif ($packet_type == 3) { $packet_index = ord($packet[9]);      $packet = substr($packet, 12); }
      elseif ($packet_type == 4) { $packet_index = ord($packet[9]);      $packet = substr($packet, 18); }

      $buffer[$packet_index] = $packet;
    }

    $buffer = implode("", $buffer);

    if ($packet_type == 4)
    {
      if (!function_exists("bzdecompress")) // REQUIRES http://php.net/bzip2
      {
        $server['e']['bzip2'] = "unavailable";
        return $server;
      }

      $buffer = bzdecompress($buffer);
    }

    $header = lgsl_cut_byte($buffer, 4);

    if ($header != "\xFF\xFF\xFF\xFF") return FALSE; // SOMETHING WENT WRONG

    $response_type = lgsl_cut_byte($buffer, 1);

    if ($response_type == "m") // HALF-LIFE 1 INFO
    {
		lgsl_cut_string($buffer);
		$server['s']['name']        = htmlspecialchars(lgsl_cut_string($buffer), ENT_QUOTES);
		$server['s']['map']         = htmlspecialchars(lgsl_cut_string($buffer), ENT_QUOTES);
		lgsl_cut_string($buffer);
		lgsl_cut_string($buffer);
		ord(lgsl_cut_byte($buffer, 1));
		$server['s']['appid']       = lgsl_unpack(lgsl_cut_byte($buffer, 2), "S");
		$server['s']['playersmax']  = intval(ord(lgsl_cut_byte($buffer, 1)));
    } 
	elseif ($response_type == "D") // SOURCE AND HALF-LIFE 1 PLAYERS
    {
		$returned = ord(lgsl_cut_byte($buffer, 1));

		$server['p'] = array();

		while ($buffer)
		{
			ord(lgsl_cut_byte($buffer, 1));
			$player_key = htmlspecialchars(lgsl_cut_string($buffer), ENT_QUOTES);
			$server['p'][$player_key]['frags'] = intval(lgsl_unpack(lgsl_cut_byte($buffer, 4), "l"));
			$server['p'][$player_key]['time']  = intval(lgsl_unpack(lgsl_cut_byte($buffer, 4), "f"));
		}
    }

    return $server;
}

function lgsl_unpack($string, $format)
{
	list(,$string) = unpack($format, $string);

	return $string;
}

function lgsl_cut_byte(&$buffer, $length)
{
	$string = substr($buffer, 0, $length);

	$buffer = substr($buffer, $length);

	return $string;
}

function lgsl_cut_string(&$buffer, $end_marker = "\x00")
{
	$length = strpos($buffer, $end_marker);

	if ($length === FALSE)
	  $length = strlen($buffer);

	$string = substr($buffer, 0, $length);

	$buffer = substr($buffer, $length + strlen($end_marker));

	return $string;
}

function getString(&$packet){
	$str = "";
	$n = strlen($packet);
	for($i=0;($packet[$i]!=chr(0)) && ($i < $n);++$i)
		$str .= $packet[$i];
	$packet = substr($packet, strlen($str));
	return trim($str);
}

function getChar(&$packet){
	$char = $packet[0];
	$packet = substr($packet, 1);
	return $char;
}

function serverInfo($server) {
	list($ip,$port) = explode(":", $server);
	$fp = @fsockopen('udp://'.$ip, $port);
	if($fp) {
		stream_set_timeout($fp, 2);
		fwrite($fp,"\xFF\xFF\xFF\xFFTSource Engine Query\x00");
		$temp = fread($fp, 4);
		$status = socket_get_status($fp); 
		if($status['unread_bytes']>0) {
			$temp = fread($fp, $status['unread_bytes']);
			$version = ord(getChar($temp));
			$array = array();
			$array['status'] = "1";
			if($version == 109) {
				$array['ip'] = getString($temp);
				$temp = substr($temp, 1);
				$array['hostname'] = getString($temp);
				$temp = substr($temp, 1);
				$array['map'] = getString($temp);
				$temp = substr($temp, 1);
				getString($temp);
				$temp = substr($temp, 1);
				getString($temp);
				$temp = substr($temp, 1);
				$array['players'] = ord(getChar($temp));
				$array['maxplayers'] = ord(getChar($temp));
			} elseif($version == 73) {
				getChar($temp);
				$array['hostname'] = getString($temp);
				$temp = substr($temp, 1);
				$array['map'] = getString($temp);
				$temp = substr($temp, 1);
				getString($temp);
				$temp = substr($temp, 1);
				getString($temp);
				$temp = substr($temp, 3);
				$array['players'] = ord(getChar($temp));
				$array['maxplayers'] = ord(getChar($temp));
			}
		} else {
			$array['hostname'] = 'Сервер недоступен';
			$array['map'] = 'Сервер недоступен';
			$array['players'] = '0';
			$array['maxplayers'] = '0';
			$array['status'] = '0';
		}				
	}
	return $array;
}