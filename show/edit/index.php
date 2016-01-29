<?php

    $settings = @simplexml_load_file('../../settings/settings.xml');
    if ($settings['first'] == 'true') {
        
?>

<script>window.location.replace('../../settings?profile=1&first')</script>

<?php
        
    }
    /*foreach($settings->profile as $findProfile) {
        if ($findProfile['active'] == '1') {
            $profile = $findProfile;
        };
    };*/
    $profile = $settings->profile;
    
    if (isset($_GET['delete']) && !isset($_GET['confirm'])) {
        
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
        
            echo '<head><title>Remove '.urldecode($_GET['backup']).'</title><link rel="shortcut icon" type="image/jpeg" href="'.$resArr->results[0]->artworkUrl60.'"></head>';
         
        };
        
?>

<script>
    (function() {
        
        var conf1 = confirm('Are you sure?');
        if (conf1 == true) {
            var conf2 = confirm('This will delete the song from the visible list.');
            if (conf2 == true) {
                var current = window.location.href;
                alert('You can add the song again by going to /show/backup/.')
                location.replace(current+'&confirm');
            }else {
                location.replace('../edit');
            }
        }else {
            location.replace('../edit');
        };
        
    })();
</script>

<?php
        
    }elseif (isset($_GET['delete']) && isset($_GET['confirm'])) {
        
        date_default_timezone_set($profile->timeZone);
        
        $file = '../../info.xml';
        
        $xml = @simplexml_load_file($file);
        
        $found = false;
        
        foreach($xml->song as $song) {
            
            if($song->songname == urldecode($_GET['delete'])) {
                
                $removeSong=dom_import_simplexml($song);
                $removeSong->parentNode->removeChild($removeSong);
                
                
                $GLOBALS['found'] = true;
                
            }
            
        }
        
        if ($found) {
            
            $xml->asXML($file);
            
            $xml->addAttribute('id', 'removed');
            
            $backup = fopen("../../backup/".date("l F j Y; h-i-s A").".xml", "w");
            fwrite($backup, $xml->asXML());
            
            header('Location: ../edit/');
        }else {
?>
<title>Error</title>
<h1>Song not found</h1>
<?php
        }
        
    }else {
        
        $file = '../../info.xml';
        
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
                    echo '<head><title>Edit Songs</title><link rel="shortcut icon" type="image/jpeg" href="'.$resArr->results[0]->artworkUrl60.'"><link rel="stylesheet" href="../themes/'.$profile->themeName.'/main.css"><link rel="stylesheet" href="../themes/'.$profile->themeName.'/edit.css"></head>';
                };
                
                $oldvar = $GLOBALS['bodytext'];
                $currentvar = '<div id="song"><div id="img"><a name="'.urlencode($song->artist).'+-+'.urlencode($song->songname).'" href="#'.urlencode($song->artist).'+-+'.urlencode($song->songname).'"><img id="artwork" src="'.str_replace('100x100','1200x1200',$resArr->results[0]->artworkUrl100).'"></a></div><div id="allinfo"><div id="songname"><div id="songname"><a target="_blank" href="https://www.google.com/search?q='.urlencode($song->artist).'+-+'.urlencode($song->songname).'">'.$song->songname.'</a></div><div id="album"><a target="_blank" href="https://www.google.com/search?q='.urlencode($song->artist).'+-+'.urlencode($song->album).'">'.$song->album.'</a></div><div id="artist"><a target="_blank" href="https://www.google.com/search?q='.urlencode($song->artist).'">'.$song->artist.'</a></div></div><div id="info"><div id="timeAdded"><div id="dayOfWeek">'.$song->info->timeAdded->dayOfWeek.'</div><div id="date"><div id="month">'.$song->info->timeAdded->date->month.'</div><div id="day">'.$song->info->timeAdded->date->day.'</div><div id="year">'.$song->info->timeAdded->date->year.'</div></div><div id="time"><div id="hour">'.$song->info->timeAdded->time->hour.'</div><div id="min">'.$song->info->timeAdded->time->min.'</div><div id="sec">'.$song->info->timeAdded->time->sec.'</div><div id="period">'.$song->info->timeAdded->time->period.'</div></div></div><div id="controls"><div id="delete"><a href="?delete='.urlencode($song->songname).'">Delete Song</a></div></div></div></div></div>';
                
                $GLOBALS['bodytext'] = $oldvar.$currentvar;
                
            };
            
            echo '<script>(function() {var hash;hash = window.location.hash;console.log(hash);window.location.replace(\'#\');if (hash.length > 1) setTimeout(function() {window.location.replace(hash)}, 10);})();</script><body>'.$bodytext.'</body>';
            
        };
        
    };
?>