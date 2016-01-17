<?php
    
    if (isset($_GET['backup'])) {
    
        $file = '../../backup/'.urldecode($_GET['backup']);
        
        $xml = @simplexml_load_file($file);
        
        if (!$xml) {
?>
<title>Error</title>
<h1>The File Doesn't Exist</h1>
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
                    echo '<head><title>Backup '.urldecode($_GET['backup']).'</title><link rel="shortcut icon" type="image/jpeg" href="'.$resArr->results[0]->artworkUrl60.'"><link rel="stylesheet" href="../main.css"></head>';
                };
                
                $oldvar = $GLOBALS['bodytext'];
                $currentvar = '<div id="song"><img src="'.str_replace('100x100','400x400',$resArr->results[0]->artworkUrl100).'"><div id="allinfo"><div id="songname"><a target="_blank" href="https://www.google.com/search?q='.urlencode($song->artist).'+-+'.urlencode($song->songname).'">'.$song->songname.'</a></div><div id="album"><a target="_blank" href="https://www.google.com/search?q='.urlencode($song->artist).'+-+'.urlencode($song->album).'">'.$song->album.'</a></div><div id="artist"><a target="_blank" href="https://www.google.com/search?q='.urlencode($song->artist).'">'.$song->artist.'</a></div><div id="info"><div id="timeAdded"><div id="dayOfWeek">'.$song->info->timeAdded->dayOfWeek.'</div><div id="date"><div id="month">'.$song->info->timeAdded->date->month.'</div><div id="day">'.$song->info->timeAdded->date->day.'</div><div id="year">'.$song->info->timeAdded->date->year.'</div></div><div id="time"><div id="hour">'.$song->info->timeAdded->time->hour.'</div><div id="min">'.$song->info->timeAdded->time->min.'</div><div id="sec">'.$song->info->timeAdded->time->sec.'</div><div id="period">'.$song->info->timeAdded->time->period.'</div></div></div></div></div></div>';
            
                $GLOBALS['bodytext'] = $oldvar.$currentvar;
            
            };
        
            echo '<body>';
            echo $bodytext;
            echo '<div id="backbutton" style="position:fixed;top:5px;right:5px;"><a href="../backup/">Back</a></div>';
            echo '<div id="applybutton" style="position:fixed;top:25px;right:5px;"><a href="?apply='.urldecode($_GET['backup']).'">Apply</a></div>';
            echo '</body>';
            
        };
    }elseif (isset($_GET['apply']) && !isset($_GET['confirm'])) {
        
        $xmlForIcon = @simplexml_load_file('../../info.xml');

        if($xmlForIcon) { 
            $numsong = count($xmlForIcon->song);
            $randnum = rand(1, $numsong);
            
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
            
            $response = get_web_page('http://itunes.apple.com/search?country=US&media=music&term='.urlencode($xmlForIcon->song[$randnum]->artist).'+'.urlencode($xmlForIcon->song[$randnum]->album));
            $resArr = array();
            $resArr = json_decode($response);
            if (!isset($resArr->results[0]->artworkUrl100) || empty($resArr->results[0]->artworkUrl60) || !$resArr->results[0]->artworkUrl60) {
                $urlartistreplace = urlencode(preg_replace("/(\s[a-zA-Z]+\.)?\s([a-zA-Z0-9_&#\-\\\\\/%\(\)]+)$/", '', preg_replace('/\+/', ' ', urlencode($xmlForIcon->song[$randnum]->artist))));
                $urlalbumreplace = urlencode(preg_replace("/(\s[a-zA-Z]+\.)?\s([a-zA-Z0-9_&#\-\\\\\/%\(\)]+)$/", '', preg_replace('/\+/', ' ', urlencode($xmlForIcon->song[$randnum]->album))));
                $response = get_web_page('http://itunes.apple.com/search?country=US&media=music&term='.$urlartistreplace.'+'.$urlalbumreplace);
                $resArr = array();
                $resArr = json_decode($response);
            };
        
            echo '<head><title>Backup '.urldecode($_GET['backup']).'</title><link rel="shortcut icon" type="image/jpeg" href="'.$resArr->results[0]->artworkUrl60.'"><link rel="stylesheet" href="../main.css"></head>';
         
        };
        
?>

<script>
    (function() {
        
        var conf1 = confirm('Are you sure?');
        if (conf1 == true) {
            var conf2 = confirm('This will replace the /show/ page.');
            if (conf2 == true) {
                var current = window.location.href;
                location.replace(current+'&confirm');
            }else {
                location.replace('../backup');
            }
        }else {
            location.replace('../backup');
        };
        
    })();
</script>

<?php
        
    }elseif (isset($_GET['apply']) && isset($_GET['confirm'])) {
        
        $filenew = '../../backup/'.urldecode($_GET['apply']);
        $fileold = '../../info.xml';
        
        $xmlnew = @simplexml_load_file($filenew);
        $xmlold = @simplexml_load_file($fileold);
        
        if (!$xmlnew || !$xmlold) {
?>
<title>Error</title>
<h1>Either the new or old file doesn't exist.</h1>
<?php
        }else {
            
            $xmlnew->asXML($fileold);
            
            header('Location: ../backup/');
            
        };
        
    }else {
        
        $xmlForIcon = @simplexml_load_file('../../info.xml');

        if($xmlForIcon) {   
            $numsong = count($xmlForIcon->song);
            $randnum = rand(1, $numsong);
            
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
            
            $response = get_web_page('http://itunes.apple.com/search?country=US&media=music&term='.urlencode($xmlForIcon->song[$randnum]->artist).'+'.urlencode($xmlForIcon->song[$randnum]->album));
            $resArr = array();
            $resArr = json_decode($response);
            if (!isset($resArr->results[0]->artworkUrl100) || empty($resArr->results[0]->artworkUrl60) || !$resArr->results[0]->artworkUrl60) {
                $urlartistreplace = urlencode(preg_replace("/(\s[a-zA-Z]+\.)?\s([a-zA-Z0-9_&#\-\\\\\/%\(\)]+)$/", '', preg_replace('/\+/', ' ', urlencode($xmlForIcon->song[$randnum]->artist))));
                $urlalbumreplace = urlencode(preg_replace("/(\s[a-zA-Z]+\.)?\s([a-zA-Z0-9_&#\-\\\\\/%\(\)]+)$/", '', preg_replace('/\+/', ' ', urlencode($xmlForIcon->song[$randnum]->album))));
                $response = get_web_page('http://itunes.apple.com/search?country=US&media=music&term='.$urlartistreplace.'+'.$urlalbumreplace);
                $resArr = array();
                $resArr = json_decode($response);
            };
        
            echo '<head><title>Backup '.urldecode($_GET['backup']).'</title><link rel="shortcut icon" type="image/jpeg" href="'.$resArr->results[0]->artworkUrl60.'"><link rel="stylesheet" href="../main.css"></head>';
         
        };
        
        echo '<link rel="stylesheet" href="list.css">';
        
        $dir = '../../backup/';
        $files = scandir($dir);
        
        $filenum = 0;
        echo '<ul id="ul">';
        foreach($files as $file) {
            if ($filenum === 0 || $filenum === 1) {}else {
                
                $xml = @simplexml_load_file('../../backup/'.$file);
                $songs = count($xml->song);
                
                if ($file !== '.DS_Store') {
                    echo '<li id="'.$songs.'" name="'.$songs.'"><span><a id="a" href="?backup='.urlencode($file).'">'.$file.' ('.$xml['id'].') ('.$songs.' songs)</a> <a id="apply" href="?apply='.urlencode($file).'">Apply</a></span></li>';
                };
                
            };
            
            ++$filenum;
        };
        echo '</ul>';
        
?>

<script>
    (function() {
        
        var numOfLi = document.getElementById('ul').childNodes.length;
        var numOfTimes = 0;
        var displayNum = 0;
        
        var newlist = document.createElement('ul');
        document.body.appendChild(newlist);
        
        while (numOfTimes < numOfLi) {
            
            numOfTimes++;
                
            displayNum++;
            
            if (document.getElementsByName(numOfTimes).length > 1) {
                
                console.log('true, '+numOfTimes+', '+displayNum+', '+document.getElementsByName(numOfTimes).length);
                
                var count = document.getElementsByName(numOfTimes).length;
                var times = 0;
                var numCurrently;
                numCurrently = numOfTimes;
                
                while (times < count) {
                
                console.log('innerwhile, '+times+', '+count);
                    times++;
                    numOfLi--;
                    
                    var curli = document.createElement('li');
                    curli.setAttribute('id', displayNum);
                    
                    var text = document.getElementsByName(numCurrently)[times-1].innerHTML;
                    
                    curli.innerHTML = text;
                    
                    newlist.appendChild(curli);
                }
                numOfLi++;
                
            }else {
                
                console.log('false, '+numOfTimes+', '+displayNum+', '+document.getElementsByName(numOfTimes).length);
                
                var curli = document.createElement('li');
                curli.setAttribute('id', displayNum);
                
                var text = document.getElementById(numOfTimes).innerHTML;
                
                curli.innerHTML = text;
                
                newlist.appendChild(curli);
                
            };
            
        };
        
        document.getElementById('ul').remove();
        
    })();
    
</script>

<?php
        
    };
?>