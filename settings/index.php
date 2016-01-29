<?php
    $file = 'settings.xml';
    
    $settings = @simplexml_load_file($file);
    
    if (!$settings) {
        echo 'You seem to not have the settings.xml file. If you have deleted it please return it to the propper location, if you have lost it or cannot find it please download it from my <a href="https://github.com/jaketr00/saveMusicInfo">GitHub page</a>';
    }else {
        
        function inputType($val, $name) {
            switch ($val['type']) {
                case 'string':
                    return '<input name="'.$name.'" type="text" value="'.$val.'" required>';
                    break;
                case 'boolean':
                    if ($val == 'true') {
                        return '<select name="'.$name.'" required><option value="1" selected>True</option><option value="false">False</option></select>';
                    }else {
                        return '<select name="'.$name.'" required><option value="2">True</option><option value="false" selected>False</option></select>';
                    }
                    break;
                case 'list':
                    echo '<select name="'.$name.'" id="'.$name.'" multiple required>';
                    foreach ($val->part as $part) {
                        echo '<option>';
                        echo $part;
                        echo '</option>';
                    }
                    echo '</select>';
                    echo '<span onclick="addIP(\''.$name.'\')">+</span>';
                    echo '<span onclick="removeIP(\''.$name.'\')">-</span>';
            }
        }
        
        if (isset($_GET['profile']) && !isset($_GET['edit'])) {
            
            if (isset($_GET['first'])) {
                $firstFilereset = new SimpleXMLElement('<settings/>');
                $firstFileresetDom = dom_import_simplexml($firstFilereset);
                $firstFileresetFrag = $firstFileresetDom->ownerDocument->createDocumentFragment();
                
                $firstFileresetFrag->appendXML("\n    ".'<profile number="1" active="1">'."\n        ".'<timeZone type="string">UTC</timeZone>'."\n        ".'<themeName type="string">default</themeName>'."\n    ".'</profile>'."\n");
                $firstFileresetDom->appendChild($firstFileresetFrag);
                $firstFilereset->asXML($file);
                echo '<h2>Thank you for downloading this web application. Please change the settings below if needed.</h2>';
            }
?>
<script>
    var allowLeave = false;
    
    
    function selectAllOptions() {
        var selObj = document.getElementById('addWhitelist');
        var a;
        for (a=0; a<selObj.options.length; a++) {
            selObj.options[a].selected = true;
        }
        var selObj = document.getElementById('addBlacklist');
        var b;
        for (b=0; b<selObj.options.length; b++) {
            selObj.options[b].selected = true;
        }
        var selObj = document.getElementById('editWhitelist');
        var c;
        for (c=0; c<selObj.options.length; c++) {
            selObj.options[c].selected = true;
        }
        var selObj = document.getElementById('editBlacklist');
        var d;
        for (d=0; d<selObj.options.length; d++) {
            selObj.options[d].selected = true;
        }
        
        allowLeave = true;
    }
    
    function addIP(name) {
        
        var ip = prompt('Please enter a new IP address');
        if (ip != null) {
            var newOption = document.createElement('option');
            newOption.innerHTML = ip;
            document.getElementById(name).appendChild(newOption);
        }
        
    };
    
    function removeIP(name) {
        if (document.getElementById(name).options[document.getElementById(name).selectedIndex] != undefined && document.getElementById(name).options[document.getElementById(name).selectedIndex] != null && document.getElementById(name).options[document.getElementById(name).selectedIndex] != false) {
            if (confirm('Are you sure?')) {
                var select = document.getElementById(name);
                
                var selected = select.options[select.selectedIndex].text;
                
                var i;
                for (i = 0; i < select.childElementCount; i++) {
                    if (select.childNodes[i].innerHTML == selected) {
                        select.removeChild(select.options[i]);
                    }
                };
            }
        }
    };
    
    /*window.onbeforeunload = function() {
        if (allowLeave) {
            return 'There may be unsaved changes';
        }
    }*/
    
</script>
<?php
            foreach($settings->profile as $profile) {
                if ($profile['number'] == $_GET['profile']) {
                    
                    echo '<link rel="stylesheet" href="../show/themes/'.$profile->themeName.'/settings.css">', "\n";
                    
                    echo '<form method="get" onsubmit="selectAllOptions()">',"\n";
                    
                    echo '<input name="profile" type="text" value="'.$_GET['profile'].'" style="display:none;">',"\n";
                    echo '<input name="edit" type="text" value="true" style="display:none;">',"\n";
                    
                    echo '<h1>Profile '.$_GET['profile'].'</h1>',"\n";
                    
                    echo '<table>',"\n";
                    
                    echo '<tr>',"\n";
                    
                    echo '<th>';
                    echo 'General';
                    echo '</th>', "\n";
                    
                    echo '</tr>',"\n";
                    echo '<tr>',"\n";
                    
                    echo '<td>';
                    echo 'Time Zone<br>view <a href="http://php.net/manual/en/timezones.php">this</a> for info.';
                    echo '</td>',"\n";
                    
                    echo '<td>';
                    echo inputType($profile->timeZone, 'timeZone');
                    echo '</td>',"\n";
                    
                    echo '</tr>',"\n";
                    echo '<tr>',"\n";
                    
                    echo '<td>';
                    echo 'Theme Name';
                    echo '</td>',"\n";
                    
                    echo '<td>';
                    echo inputType($profile->themeName, 'themeName');
                    echo '</td>',"\n";
                    
                    echo '</tr>',"\n";
                    
                    echo '</table>',"\n";
                    
                    echo '<input type="submit" value="Apply">';
                    
                    echo '</form>';
                    
                }
            };
        }elseif (isset($_GET['profile']) && isset($_GET['edit'])) {
            
            if (isset($_GET['timeZone']) && isset($_GET['themeName'])) {
                
                if (isset($_GET['confirm'])) {
                    
                    echo '<h1>loading...</h1>';
                    
                    $newSettingsFile = new SimpleXMLElement('<settings/>');
                    
                    foreach ($settings->profile as $profile) {
                        if ($profile['number'] == $_GET['profile']) {
                            
                            echo '<h1>found correct profile...</h1>';
                            
                            if ($profile['active'] == '1') {
                                $active = 1;
                            }else {
                                $active = 0;
                            }
                            $newSettingsDom = dom_import_simplexml($newSettingsFile);
                            $newSettings = $newSettingsDom->ownerDocument->createDocumentFragment();
                            
                            $newSettings->appendXML("\n    ".'<profile number="'.$_GET['profile'].'" active="'.$active.'">'."\n        ".'<timeZone type="string">'.$_GET['timeZone'].'</timeZone>'."\n        ".'<themeName type="string">'.$_GET['themeName'].'</themeName>'."\n    ".'</profile>'."\n");
                            $newSettingsDom->appendChild($newSettings);
                    
                            $newSettingsFile->asXML($file);
                            
?>

<script>

    (function() {
        window.location.replace(window.location.href.replace(/&edit=.*/, ''))
    })();

</script>

<?php
                            
                        }else {
                        
                            /*$newSettings->appendXML();
                            $newSettingsDom->appendChild($newSettings);*/
                            
                        }
                    }
                    
                    //echo '</h1>';
                
                }else {
                    
?>

<script>

    (function() {
        
        if (confirm('Are you sure?')) {
            window.location.replace(window.location.href+'&confirm');
        }else {
            window.location.replace(window.location.href.replace(/\&edit.*/, ''))
        }
        
    })();

</script>

<?php
                    
                }
                
            }else {
                echo '<h1>Not all required fields were filled in</h1>';
            }
            
        }else {
            echo '<ul>';
            foreach($settings->profile as $profile) { 
                echo '<li><a href="?profile='.urlencode($profile['number']).'">Profile '.$profile['number'];
                if ($profile['active'] == '1') {
                    echo ' (Active)';
                }
                echo '</a></li>';
            };
            echo '</ul>';
        };
    };
?>