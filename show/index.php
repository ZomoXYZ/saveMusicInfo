<?php
    $file = '../info.xml';
    
    $xml = @simplexml_load_file($file);
    
    if (!$xml) {
?>
<title>Error</title>
<h1>There are no songs saved.</h1>
<?php
    }else {
            
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
                echo '<head><title id="title">Saved Songs</title><link rel="shortcut icon" type="image/jpeg" href="'.$resArr->results[0]->artworkUrl60.'"><link rel="stylesheet" href="main.css"></head>';
            };
            
            $oldvar = $GLOBALS['bodytext'];
            $currentvar = '<div id="song"><div id="img"><img id="artwork" src="'.str_replace('100x100','1200x1200',$resArr->results[0]->artworkUrl100).'"></div><div id="allinfo"><div id="songinfo"><div id="songname"><a target="_blank" href="https://www.google.com/search?q='.urlencode($song->artist).'+-+'.urlencode($song->songname).'">'.$song->songname.'</a></div><div id="album"><a target="_blank" href="https://www.google.com/search?q='.urlencode($song->artist).'+-+'.urlencode($song->album).'">'.$song->album.'</a></div><div id="artist"><a target="_blank" href="https://www.google.com/search?q='.urlencode($song->artist).'">'.$song->artist.'</a></div></div><div id="info"><div id="timeAdded"><div id="dayOfWeek">'.$song->info->timeAdded->dayOfWeek.'</div><div id="date"><div id="month">'.$song->info->timeAdded->date->month.'</div><div id="day">'.$song->info->timeAdded->date->day.'</div><div id="year">'.$song->info->timeAdded->date->year.'</div></div><div id="time"><div id="hour">'.$song->info->timeAdded->time->hour.'</div><div id="min">'.$song->info->timeAdded->time->min.'</div><div id="sec">'.$song->info->timeAdded->time->sec.'</div><div id="period">'.$song->info->timeAdded->time->period.'</div></div></div></div></div></div>';
            
            $GLOBALS['bodytext'] = $oldvar.$currentvar;
            
        };
        
        echo '<body><div id="alert"></div>'.$bodytext.'</body>';
        
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
        
        checkVersion();
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
    
    function postRequest (url, params, success, error) {  
        var xhr = XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"); 
        xhr.open("GET", url, true); 
        xhr.onreadystatechange = function(){ 
            if ( xhr.readyState == 4 ) { 
                if ( xhr.status == 200 ) { 
                    success(xhr.responseText); 
                } else { 
                    error(xhr, xhr.status); 
                } 
            } 
        }; 
        xhr.onerror = function () { 
            error(xhr, xhr.status); 
        }; 
        xhr.send(params); 
    };
    
    
    var title = document.getElementById('title');
    var titleText;
    titleText = title.innerHTML;
    var times = 10;
    
    function checkVersion() {
        ++times;
        var version;
        var currentVersion;
        
        postRequest ('https://raw.githubusercontent.com/jaketr00/saveMusicInfo/master/version.txt?v='+times, null, function (response1) {
            
            postRequest ('../version.txt?v='+times, null, function (response2) {
                
                while (document.getElementById('alert').hasChildNodes()) {
                    document.getElementById('alert').removeChild(document.getElementById('alert').lastChild);
                }
                
                version = response1;
                currentVersion = response2;
                if (parseInt(currentVersion.replace(/\./g, '')) === parseInt(version.replace(/\./g, ''))) {
                    title.innerHTML = titleText;
                }else {
                    var alert = document.createElement("div");
                    alert.setAttribute('id', 'alertText');
                    if (parseInt(currentVersion.replace(/\./g, '')) < parseInt(version.replace(/\./g, ''))) {
                        alert.innerHTML = 'New version available';
                        title.innerHTML = titleText+' (Update)';
                    }else if (parseInt(currentVersion.replace(/\./g, '')) > parseInt(version.replace(/\./g, ''))) {
                        alert.innerHTML = 'Older version available ???';
                        title.innerHTML = titleText+' (Downdate?)';
                    }
                    document.getElementById('alert').appendChild(alert);
                }
                
            }, function (xhr, status) {
                
                while (document.getElementById('alert').hasChildNodes()) {
                    document.getElementById('alert').removeChild(document.getElementById('alert').lastChild);
                }
                
                var alert = document.createElement("div");
                title.innerHTML = titleText+' (Error)';
                alert.setAttribute('id', 'alertText');
                switch(status) { 
                    case 404:
                        alert.innerHTML = 'Error reading local file (File Not Found)';
                        break;
                    case 500:
                        alert.innerHTML = 'Error reading local file (Server Error)';
                        break;
                    case 0:
                        alert.innerHTML = 'Error reading local file (Request Aborted)';
                        break;
                    default:
                        alert.innerHTML = 'Error reading local file (Unknown Error: '+status+')';
                }
                document.getElementById('alert').appendChild(alert);
            });
            
        }, function (xhr, status) {
            
            while (document.getElementById('alert').hasChildNodes()) {
                document.getElementById('alert').removeChild(document.getElementById('alert').lastChild);
            }
            
            var alert = document.createElement("div");
            title.innerHTML = titleText+' (Error)';
            alert.setAttribute('id', 'alertText');
            switch(status) { 
                case 404:
                    alert.innerHTML = 'Error reading local file (File Not Found)';
                    break;
                case 500:
                    alert.innerHTML = 'Error reading local file (Server Error)';
                    break;
                case 0:
                    alert.innerHTML = 'Error reading local file (Request Aborted)';
                    break;
                default:
                    alert.innerHTML = 'Error reading local file (Unknown Error: '+status+')';
            }
            document.getElementById('alert').appendChild(alert);
        }); 
        
        
        setTimeout(function() {checkVersion()}, 1000);
    }
    
    window.onbeforeunload = function() {
        while (document.getElementById('alert').hasChildNodes()) {
            document.getElementById('alert').removeChild(document.getElementById('alert').lastChild);
        };
    };
</script>

<?php
        
    };
?>