<?php
    $file = '../info.xml';
    
    $xml = @simplexml_load_file($file);
    
    if (!$xml) {
?>
<title>Error</title>
<h1>There are no songs saved.</h1>
<?php
    }else {
        
        /*$page = $_SERVER['PHP_SELF'];
        $sec = "60";
        header("Refresh: $sec; url=$page");*/
            
        function get_web_page($url) {
            $options = array(
                CURLOPT_RETURNTRANSFER => true,   // return web page
                CURLOPT_HEADER         => false,  // don't return headers
                CURLOPT_FOLLOWLOCATION => true,   // follow redirects
                CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
                CURLOPT_ENCODING       => "",     // handle compressed
                CURLOPT_USERAGENT      => "test", // name of client
                CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
                CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
                CURLOPT_TIMEOUT        => 120,    // time-out on response
            ); 
            
            $ch = curl_init($url);
            curl_setopt_array($ch, $options);
            
            $content  = curl_exec($ch);
            
            curl_close($ch);
            
            return $content;
        };
        
        $numsong = count($xml->song);
        $randnum = rand(1, $numsong);
        $eachtim = 0;
        
        $bodytext = '';
        
        foreach($xml->song as $song) {
            ++$eachtim;
            
            
            $response = get_web_page('http://itunes.apple.com/search?country=US&media=music&term='.urlencode($song->artist).'+'.urlencode($song->album));
            $resArr = array();
            $resArr = json_decode($response);
            if (!isset($resArr->results[0]->artworkUrl100) || empty($resArr->results[0]->artworkUrl60) || !$resArr->results[0]->artworkUrl60) {
                $urlartistreplace = urlencode(preg_replace("/(\s[a-zA-Z]+\.)?\s([a-zA-Z0-9_&#\-\\\\\/%\(\)]+)$/", '', preg_replace('/\+/', ' ', urlencode($song->artist))));
                $urlalbumreplace = urlencode(preg_replace("/(\s[a-zA-Z]+\.)?\s([a-zA-Z0-9_&#\-\\\\\/%\(\)]+)$/", '', preg_replace('/\+/', ' ', urlencode($song->album))));
                $response = get_web_page('http://itunes.apple.com/search?country=US&media=music&term='.$urlartistreplace.'+'.$urlalbumreplace);
                $resArr = array();
                $resArr = json_decode($response);
            };
            
            if ($eachtim == $randnum) {
                echo '<head><title>Saved Songs</title><link rel="shortcut icon" type="image/jpeg" href="'.$resArr->results[0]->artworkUrl60.'"><link rel="stylesheet" href="main.css"></head>';
            };
            
            $oldvar = $GLOBALS['bodytext'];
            $currentvar = '<div id="song"><div id="img"><img src="'.str_replace('100x100','400x400',$resArr->results[0]->artworkUrl100).'"></div><div id="allinfo"><div id="songname"><a target="_blank" href="https://www.google.com/search?q='.urlencode($song->artist).'+-+'.urlencode($song->songname).'">'.$song->songname.'</a></div><div id="album"><a target="_blank" href="https://www.google.com/search?q='.urlencode($song->artist).'+-+'.urlencode($song->album).'">'.$song->album.'</a></div><div id="artist"><a target="_blank" href="https://www.google.com/search?q='.urlencode($song->artist).'">'.$song->artist.'</a></div><div id="info"><div id="timeAdded"><div id="dayOfWeek">'.$song->info->timeAdded->dayOfWeek.'</div><div id="date"><div id="month">'.$song->info->timeAdded->date->month.'</div><div id="day">'.$song->info->timeAdded->date->day.'</div><div id="year">'.$song->info->timeAdded->date->year.'</div></div><div id="time"><div id="hour">'.$song->info->timeAdded->time->hour.'</div><div id="min">'.$song->info->timeAdded->time->min.'</div><div id="sec">'.$song->info->timeAdded->time->sec.'</div><div id="period">'.$song->info->timeAdded->time->period.'</div></div></div></div></div></div>';
            
            $GLOBALS['bodytext'] = $oldvar.$currentvar;
            
        };
        
        echo '<body>'.$bodytext.'</body>';
        
?>

<script>
    
    (function() {
        
        var currentXML = '';
        
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                currentXML = xhttp.responseText;
            
                updatePage(currentXML);
            };
        };
        xhttp.open("GET", "../info.xml", true);
        xhttp.send();
        
    })();
    
    function updatePage(currentXML) {
        
        var newXML = '';
        
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                newXML = xhttp.responseText;
                
                if (currentXML === newXML) {
                
                    setTimeout(function() {updatePage(currentXML)}, 1000);
                    
                }else {
                    
                    window.location.reload();
                    
                };
            };
        };
        xhttp.open("GET", "../info.xml", true);
        xhttp.send();
        
    };
    
</script>

<?php
        
    };
?>