<?php
    
    if (!file_exists('backup/')) {
        mkdir('backup/');
    };

    if(isset($_GET['artist']) && isset($_GET['album']) && isset($_GET['song'])) {
        
        date_default_timezone_set("UTC");

        $file = 'info.xml';
    
        $xml = @simplexml_load_file($file);
        $backupXML = @simplexml_load_file($file);
    
        if (!$xml) {
            $xml = new SimpleXMLElement('<songs/>');
            $backupXML = new SimpleXMLElement('<songs/>');
        };
        
        $backupXML->addAttribute('id', 'added');
    
        $dom = dom_import_simplexml($xml);
        $domBackup = dom_import_simplexml($backupXML);
    
        $song = $dom->ownerDocument->createDocumentFragment();
        $songBackup = $domBackup->ownerDocument->createDocumentFragment();
        
        $artistinfo = htmlspecialchars($_GET['artist']);
        $albuminfo = htmlspecialchars($_GET['album']);
        $songinfo = htmlspecialchars($_GET['song']);
        
        $song->appendXML("\n    <song>\n        <artist>".$artistinfo."</artist>\n        <album>".$albuminfo."</album>\n        <songname>".$songinfo."</songname>\n        <info>\n            <timeAdded>\n                <dayOfWeek>".date("l")."</dayOfWeek>\n                    <date>\n                        <month>".date("F")."</month>\n                        <day>".date("j")."</day>\n                        <year>".date("Y")."</year>\n                </date>\n                <time>\n                    <hour>".date("h")."</hour>\n                    <min>".date("i")."</min>\n                    <sec>".date("s")."</sec>\n                    <period>".date("A")."</period>\n                </time>\n            </timeAdded>\n        </info>\n    </song>\n");
        $songBackup->appendXML("\n    <song>\n        <artist>".$artistinfo."</artist>\n        <album>".$albuminfo."</album>\n        <songname>".$songinfo."</songname>\n        <info>\n            <timeAdded>\n                <dayOfWeek>".date("l")."</dayOfWeek>\n                    <date>\n                        <month>".date("F")."</month>\n                        <day>".date("j")."</day>\n                        <year>".date("Y")."</year>\n                </date>\n                <time>\n                    <hour>".date("h")."</hour>\n                    <min>".date("i")."</min>\n                    <sec>".date("s")."</sec>\n                    <period>".date("A")."</period>\n                </time>\n            </timeAdded>\n        </info>\n    </song>\n");
        $dom->appendChild($song);
        $domBackup->appendChild($songBackup);
    
        $xml->asXML($file);
        $backup = fopen("backup/".date("l F j Y; h-i-s A").".xml", "w");
        fwrite($backup, $backupXML->asXML());
?>

<script>(function() {window.open('show', 'tab');window.close()})();</script>

<?php
        
    }else {
        
?>

<title>Error</title>
Redirecting to /show/.  If you are not redirected please click <a href="show">here</a>.
<script>
(function() {location.replace('show')})();
</script>

<?php
        
    };

?>