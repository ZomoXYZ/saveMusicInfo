<?php
    
    if (!file_exists('backup/')) {
        mkdir('backup/');
    };
    
    $xml = @simplexml_load_file($file);

    $settings = @simplexml_load_file('settings/settings.xml');
        
    /*foreach($settings->profile as $findProfile) {
        if ($findProfile['active'] == '1') {
            $profile = $findProfile;
        };
    };*/
    $profile = $settings->profile;
    date_default_timezone_set($profile->timeZone);
    
    function addSong($file, $xml, $backupXML) {
        $backupXML->addAttribute('id', 'added');
        
        $dom = dom_import_simplexml($xml);
        $domBackup = dom_import_simplexml($backupXML);
        
        $song = $dom->ownerDocument->createDocumentFragment();
        $songBackup = $domBackup->ownerDocument->createDocumentFragment();
        
        $artistinfo = htmlspecialchars($_GET['artist']);
        $albuminfo = htmlspecialchars($_GET['album']);
        $songinfo = htmlspecialchars($_GET['song']);
        
        $song->appendXML("\n    <song>\n        <artist>".$artistinfo."</artist>\n        <album>".$albuminfo."</album>\n        <songname>".$songinfo."</songname>\n        <info>\n            <timeAdded>\n                <dayOfWeek>".date("l")."</dayOfWeek>\n                <date>\n                    <month>".date("F")."</month>\n                    <day>".date("j")."</day>\n                    <year>".date("Y")."</year>\n                </date>\n                <time>\n                    <hour>".date("h")."</hour>\n                    <min>".date("i")."</min>\n                    <sec>".date("s")."</sec>\n                    <period>".date("A")."</period>\n                </time>\n            </timeAdded>\n        </info>\n    </song>\n");
        $songBackup->appendXML("\n    <song>\n        <artist>".$artistinfo."</artist>\n        <album>".$albuminfo."</album>\n        <songname>".$songinfo."</songname>\n        <info>\n            <timeAdded>\n                <dayOfWeek>".date("l")."</dayOfWeek>\n                <date>\n                    <month>".date("F")."</month>\n                    <day>".date("j")."</day>\n                    <year>".date("Y")."</year>\n                </date>\n                <time>\n                    <hour>".date("h")."</hour>\n                    <min>".date("i")."</min>\n                    <sec>".date("s")."</sec>\n                    <period>".date("A")."</period>\n                </time>\n            </timeAdded>\n        </info>\n    </song>\n");
        $dom->appendChild($song);
        $domBackup->appendChild($songBackup);
    
        $xml->asXML($file);
        $backup = fopen("backup/".date("l F j Y; h-i-s A").".xml", "w");
        fwrite($backup, $backupXML->asXML());
        
        $hashLocation = urlencode($_GET['artist']).'+-+'.urlencode($_GET['song']);
        if (isset($_GET['confirm'])) {
        //#<?php echo urlencode($_GET['artist']).'+-+'.urlencode($_GET['song']); ?>       
?>

<script>(function() {window.location.replace('show#<?=$hashLocation ?>')})();</script>
<h1>Redirecting to /show/.  If you are not redirected please click <a href="show">here</a></h1>

<?php
            
        }else {
?>

<script>(function() {window.open('show#<?=$hashLocation ?>', 'tab');window.close()})();</script>

<?php
        }
        
    }
    
    function confirmAddSong($num) {
        
        switch($num) {
            case 1:
                $script = 'if(confirm(\'A song with the same name has already been added. Are you sure you want to add this?\')) {window.location.replace(window.location.href+\'&confirm\')}else {window.location.replace(\'show#'.urlencode($_GET['artist']).'+-+'.urlencode($_GET['song']).'\')}';
                break;
            case 2:
                $script = 'if(confirm(\'A very similar song has already been added. Are you sure you want to add this?\')) {window.location.replace(window.location.href+\'&confirm\')}else {window.location.replace(\'show#'.urlencode($_GET['artist']).'+-+'.urlencode($_GET['song']).'\')}';
                break;
            case 3:
                $script = 'alert(\'This exact song has already been added.\');window.location.replace(\'show#'.urlencode($_GET['artist']).'+-+'.urlencode($_GET['song']).'\')';
                break;
        }
        echo '<script>';
        echo '(function() {'."\n";
        echo $script;
        echo "\n".'})();';
        echo '</script>';
        
    }
    
    if(isset($_GET['artist']) && isset($_GET['album']) && isset($_GET['song']) && !isset($_GET['confirm'])) {

        $file = 'info.xml';
    
        $xml = @simplexml_load_file($file);
        $backupXML = @simplexml_load_file($file);
    
        if (!$xml) {
            $xml = new SimpleXMLElement('<songs/>');
            $backupXML = new SimpleXMLElement('<songs/>');
        }
        
        $noMatches = true;
        $confirmAddSong = 0;
        foreach($xml->song as $checkSong) {
            if ($checkSong->songname == $_GET['song']) {
                $noMatches = false;
                if ($checkSong->artist == $_GET['artist']) {
                    if ($checkSong->album == $_GET['album']) {
                        //echo '<script>alert(\'This song has already been added.\')</script>';
                        if ($confirmAddSong < 3) {
                            $confirmAddSong = 3;
                        }
                    }else {
                        //echo '<script>alert(\'A very similar song has already been added.\')</script>';
                        if ($confirmAddSong < 2) {
                            $confirmAddSong = 2;
                        }
                    }
                }else {
                    //echo '<script>alert(\'A song with the same name has already been added.\')</script>';
                    if ($confirmAddSong < 1) {
                        $confirmAddSong = 1;
                    }
                }
            }
        }
        
        if ($confirmAddSong > 0) {
            confirmAddSong($confirmAddSong);
        }
        
        if ($noMatches) {
            addSong($file, $xml, $backupXML);
        }
        
    }elseif (isset($_GET['artist']) && isset($_GET['album']) && isset($_GET['song']) && isset($_GET['confirm'])) {

        $file = 'info.xml';
    
        $xml = @simplexml_load_file($file);
        $backupXML = @simplexml_load_file($file);
    
        if (!$xml) {
            $xml = new SimpleXMLElement('<songs/>');
            $backupXML = new SimpleXMLElement('<songs/>');
        }
        
        addSong($file, $xml, $backupXML);
        
    }else {
?>

<title>Error</title>
<h1>Redirecting to /show/.  If you are not redirected please click <a href="show">here</a></h1>
<script>
(function() {location.replace('show')})();
</script>

<?php
        
    };

?>