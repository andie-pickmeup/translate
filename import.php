<?php

	require 'vendor/autoload.php';

	use Google_Client;
	use Google_Service_Drive;
	use GuzzleHttp;


	$sp_key = "1GpiX5HcvTc4fz6uQ1ROgcmU0mBB2uQfy9CLiq1rYMgo";
//        $sp_key = "1_4Wv1_Oi81h_SCyX1kQ1m63RKF1Bg_9RBibRi2SJcTA";
        $url = "https://spreadsheets.google.com/feeds/cells/{$sp_key}/1/public/basic?alt=json";

        // UA
        $userAgent = "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.9) Gecko/20100315 Firefox/3.5.9";
        $curl = curl_init();
        // set URL
        curl_setopt($curl, CURLOPT_URL, $url);

        // setting curl options
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);// return page to the variable
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($curl, CURLOPT_TIMEOUT, 30000); // times out after 4s
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);

        // grab URL and pass it to the variable
        $str = curl_exec($curl);
        curl_close($curl);

        // extract pure JSON from response 
        $data = json_decode($str, true);

        // https://spreadsheets.google.com/feeds/cells/0Am9NwGgzBIuBdDhSQ3FKMjRDZjAyYlZscUhmNUdKQnc/1/public/basic/R1C2
        $id_marker = "https://spreadsheets.google.com/feeds/cells/{$sp_key}/1/public/basic?alt=json";
        $entries   = $data["feed"]["entry"];

        $res = array();

        foreach($entries as $entry) {
           $content = $entry["content"];
           $ind = str_replace($id_marker."R", "", $entry["id"]['$t']);
           $ii  = explode("C", $ind);
           $res[$ii[0]-1][$ii[1]-1] = $entry["content"]['$t'];
        }
        $table = $data['feed']['entry'];
        $lettertonumber = array();
        $keysTable = array();
	$componentTable = array();
        $i = 0;
        foreach($table as $value) {
            if (substr($value['title']['$t'],-1) != '1') {
                break;
            }
            $lettertonumber[substr($value['title']['$t'],0,1)] = $i;


            $map[$i] = array("key" => $value['content']['$t'], "array" => array());
            $i++;
        }

        $i = 0;
        foreach($table as $value) {
            if ($i > 1) {
                if (substr($value['title']['$t'],0,1) == 'A') {
                    array_push( $keysTable , $value['content']['$t']); 
                }
		if (substr($value['title']['$t'],0,1) == 'B') {
		    $componentTable[ (substr($value['title']['$t']  , 1)) - 2] = $value['content']['$t'];
		}
            }
            $i++;
        }

        $itemCount = count($table);
        $keyCount = count($map);

        for ($i=$keyCount;$i<$itemCount;$i++) {
            $headerIdx = $lettertonumber[substr($table[$i]['title']['$t'],0,1)];
            $keyIdx = substr($table[$i]['title']['$t'],1)-2; // -2 because google starts from 1 and the title is also 1

                array_push ( $map[$headerIdx]['array'] , array("key" => $keysTable[$keyIdx], "component" => $componentTable[$keyIdx], "value" => $table[$i]['content']['$t']));
        }


        $map = array_splice($map, 2, count($map));

	// print_r($map);

	for ($i=0;$i<count($map);$i++) {
	    $lankey = $map[$i]['key'];
	    for ($j=0;$j<count($map[$i]['array']);$j++) {
	    	echo $map[$i]['array'][$j]['key'] . "," . $map[$i]['array'][$j]['value'] . "," . $lankey .  "\n";

	    }
		
	}
