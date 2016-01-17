# Save Music Info

This is a PHP based website I made to easily save info from a song.

## Required programs

* Any program that can run PHP ([Wamp](http://www.wampserver.com/en/), [Mamp/Mamp Pro](https://www.mamp.info/en/),  [Lamp](https://en.wikipedia.org/wiki/LAMP_\(software_bundle\)#External_links),  [Xampp](https://www.apachefriends.org/index.html),  [Ampps](http://www.ampps.com/), etc.)

## Recommended programs

* [TamperMonkey](http://tampermonkey.net/) (Chrome/Safari/Opera/Dolphin)
* [GreaseMonkey](https://addons.mozilla.org/en-US/firefox/addon/greasemonkey/) (Firefox)

## What is it for?

Have you ever been listening to internet radio and thought "I need so remember this song for later"?  This is that answer.

# How to use

### Required Steps

1. Download the compressed file from [here](localhost) and extract the contents.
2. Go to your server's files (the server is the required program).
 ** Usually under `C://(program name)/` in Windows or `/Applications/(program name).app/` in OSX.
 *** In OSX you will have to CTRL+Click the program, then click "Show Package Contents".
3. Find either the folder named "htdocs" or "www".
4. In the folder you found, make a folder named whatever you want.
5. In the contents of the extracted file, you should see a few files and folders named "index.php", "backup", and "show".  If you do not see those, find a folder named "saveMusicInfo" and open that folder.
6. Drag the contents from the extracted file into the folder you created.
7. Start up the server.

### If you downloaded the web browser extension

1. Go to the website you wish to add this to (you can add this to as many websites as you want).
 * I will use Pandora for this example.  If you are using Chrome or Firefox, and know basic HTML/JavaScript, this part is customizable, but I will give steps on how to do Pandora only.
2. With TamperMonkey, click the tampermonkey logo at the top right of your browser, and click "New Script".  With GreaseMonkey, click the arrow next to the GreaseMonkey logo and click "New Script".


 TamperMonkey:
  * Safari will not show the logo at first, CTRL+Click to the right of the URL bar, then click "Customize Toolbar...".  Now Drag and drop the TamperMonkey to the right or left of the Toolbar.
  * Change the text after "@name" to something, like "Save Music Button".
  * Change the text after "@description" to something also, like "This button will save the music info.".
  * Change the text after "@namespace" and "@author" to your name or alias.
  * Replace "@match" with "@include".
  * If the url next to "@include" has a url like "http://www.pandora.com/station/play/...", remove the "/station/play/...".
  
  
 GreaseMonkey:
  * Name the script something, like "Save Music Button".
  * Namespace is your name/alias.
  * Make the Description something also, like "This button will save the music info.".
  * If the url below "includes" has a url like "http://www.pandora.com/station/play/...", remove the "/station/play/...".
  * Click OK
3. Now add this code into the script:

```
$('.buyButton').click(function() {
    var albumex = $('.trackData .albumTitle').text();
    var songex = $('.trackData .songTitle').text();
    var artistex = $('.trackData a.artistSummary').text();
    $('#buy_menu_dd ul').append('<li class="SVall" style="cursor:pointer"><a>Save Info</a></li>');
    $('.SVall').click(function() {
        var url = 'http://localhost/path/to/index.php';
        window.open(url+'?artist=' + escape(artistex) + '&album=' + escape(albumex) + '&song=' + escape(songex), '_blank', 'width=100, height=100');
    });
});
```

#### What the above script does

Line by Line

1. When you click on the item with the classname of `buyButton` run the rest of the code.

# License

[**The MIT License (MIT)**](https://github.com/jaketr00/saveMusicInfo/blob/visual/LICENSE.md)

Feel free to use and edit this page howerver you want, just give back credit.
