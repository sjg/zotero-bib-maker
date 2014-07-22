#!/usr/bin/php
<?php

    $zotero_key = "";
    $user_id = "";
    $collection_id = "";
    $filename = "./thesis.bib";

    // Don't edit below this line, unless you know what you're doing -----------------------------------------------------------

    $url_prefix = "&format=keys";
    $json_url_prefix = "&format=json";

    $item_link = "https://api.zotero.org/users/".$user_id."/collections/".$collection_id."/items?key=".$zotero_key.$url_prefix;
    $json_item_link = "https://api.zotero.org/users/".$user_id."/collections/".$collection_id."/items?key=".$zotero_key.$json_url_prefix;

    if(!is_connected()){
        echo "No Internet connection. Skipping Download ...\n";
        exit(0);
    }

    $thesis_items = file_get_contents($item_link);

    $json_items = file_get_contents($json_item_link);
    $json_array = json_decode($json_items, 1);

    $keyTitles = array();

    foreach( $json_array as $key => $values){
        $keyTitles[$json_array[$key]["key"]] = $json_array[$key]["data"]["title"];
    }

    echo "Getting bib items from collection '".$collection_id."'\n";

    if($thesis_items != ""){
        $item_array = preg_split ('/$\R?^/m', $thesis_items);
        
        if(count($item_array)==1){ $suffix = ""; } else { $suffix = "s"; }
            
        echo count($item_array)." item".$suffix." found.\n\n";
	    
        touch($filename);
        if (is_writable($filename)) {

            // In our example we're opening $filename in append mode.
            // The file pointer is at the bottom of the file hence
            // that's where $somecontent will go when we fwrite() it.
            if (!$handle = fopen($filename, 'w')) {
                echo "Cannot open file ($filename)";
                exit;
            }

            $count = 0;

            foreach ($item_array as $key => $keyID) {
                //Get bib entry
                $bibentry = "https://api.zotero.org/users/".$user_id."/items/".trim($keyID)."/?key=".$zotero_key."&format=bibtex";
                $bibitem = file_get_contents($bibentry);

                echo "Writing item: ".trim($keyTitles[trim($keyID)])." ...\n";
                
                if (fwrite($handle, $bibitem) === FALSE) {
                    echo "\t\tCannot write to file ($filename)";
                    exit;
                }else{
                    $count++;
                }
            }

            if($count==1){ $suffix = ""; } else { $suffix = "s"; }

            echo "\n";
            echo "Success, wrote ".$count." bib item".$suffix." to file ($filename).\n";

            fclose($handle);

        } else {
            echo "The file $filename is not writable\n";
        }
    }else{
        echo "Final Thesis Collection Empty\n";
        exit(0);
    }

    function is_connected(){
        $connected = fsockopen("www.google.com", 80); //website and port
        if ($connected){
            $is_conn = true; //action when connected
            fclose($connected);
        }else{
            $is_conn = false; //action in connection failure
        }
        return $is_conn;
    }
?>
