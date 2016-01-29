# Save Music Info

This is a PHP based website I made to easily save info from a song on internet radio.

## What is it for?

Have you ever been listening to internet radio and thought "I need so remember this song for later"?  This is that answer.

## Required programs

* Any program that can run PHP ([Wamp](http://www.wampserver.com/en/), [Mamp/Mamp Pro](https://www.mamp.info/en/),  [Lamp](https://en.wikipedia.org/wiki/LAMP_\(software_bundle\)#External_links),  [Xampp](https://www.apachefriends.org/index.html),  [Ampps](http://www.ampps.com/), etc.)

## Recommended programs

* [TamperMonkey](http://tampermonkey.net/) (Chrome/Safari/Opera/Dolphin)
* [GreaseMonkey](https://addons.mozilla.org/en-US/firefox/addon/greasemonkey/) (Firefox)

# How to use

### Required Steps

1. Download the compressed file from "Download Zip" above and extract the contents.
2. Go to your server's files (the server is the required program).
 * Usually under `C://(program name)/` in Windows or `/Applications/(program name).app/` in OSX.
 * In OSX you will have to CTRL+Click the program, then click "Show Package Contents".
3. Find either the folder named "htdocs" or "www".
4. In the folder you found, make a folder named whatever you want.
5. In the contents of the extracted file, you should see a few files and folders named "index.php", and "show".  If you do not see those, find a folder named "saveMusicInfo-master" and open that folder.
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
    var artistex = $('.trackData .artistSummary').text();
    var url = 'http://localhost/path/to/index.php';
    $('#buy_menu_dd ul').append('<li class="SVall" style="cursor:pointer"><a href="url+'?artist=' + escape(artistex) + '&album=' + escape(albumex) + '&song=' + escape(songex)+'" target="_blank">Save Info</a></li>');
});
```

#### What the above script does

Line by Line

1. When you click on the item with the classname of `buyButton` run the rest of the code.
 * ![Reference Image](https://raw.githubusercontent.com/jaketr00/saveMusicInfo/master/resources/buyButton.png)
2. Referencing an element that has a parent class name of "trackData" and a class name of "albumTitle". It gets the text from inside that element (This will be the album name).
3. Referencing an element that has a parent class name of "trackData" and a class name of "songTitle". It gets the text from inside that element (This will be the song name).
4. Referencing an element that has a parent class name of "trackData" and a class name of "artistSummary". It gets the text from inside that element (This will be the artist name).
 * ![Reference Image](https://raw.githubusercontent.com/jaketr00/saveMusicInfo/master/resources/song%20info.png)
5. Url of the `index.php` in reference to your `www` or `htdocs`, **YOU MUST CHANGE THIS**.
6. This will make a list item inside the drop down menu (which has a parent id of "buy_menu_dd") with text saying "Save Info".  This will also have a link to your local server to add this song which will open in a new tab.
 * ![Reference Image](https://raw.githubusercontent.com/jaketr00/saveMusicInfo/master/resources/button.png)
7. End the code ran from line 1.

# Other useful pages in this project

1. Show saved songs.
 * http://localhost/`path/to/project/`show/
2. Retrieve backups of saved songs.
 * http://localhost/`path/to/project/`show/backup/
2. Delete a song.
 * http://localhost/`path/to/project/`show/edit/

# Saving Syntax

Now that you have the website up and running, you may want to save the songs from not only itunes.  Here is how you will enter the url.

There are 3 php values, all required. (PHP values are made with `?` and `&`.  `url?value1=val&value2=val&value3=val`)

1. `artist`: artist name
1. `album`: album name
1. `song`: song name

**Example**

`http://localhost/path/to/index.php?artist=artist%20name&album=album%20name&song=song%20name`

* Change "`path/to/index.php`" to where your `index.php` file is from either the `htdocs` or `www` folder on your server.
* Do not forget to escape the url:

**PHP**
```
$str='artist name';
urlencode($str);
```

**Javascript**
```
str='artist name';
escape(str);
```

# Notes

### Other Projects used in this

* [Open Sans](https://www.google.com/fonts/specimen/Open+Sans) (Light, Regular, and SemiBold)
* iTunes Search (made possible by [iTunes](http://www.itunes.com/))

## To Do


* Make "/show/backups" and "/show/edit" able to be accessed with links throughout the site.
* Make global options:
  * ~~Make TimeZone editable without changing the Code.~~
  * Only able to add from local machine.
  * Whitelist machines allowed to add.
  * Blacklist machines allowed to add.
  * Only able to edit from local machine.
  * Whitelist machines allowed to edit.
  * Blacklist machines allowed to edit.
  * ~~Custom Theme.~~
  * Allow local options (using cookies).
  * Make default local options.
* Make local options (using cookies):
  * Night theme.
  * Custom CSS.
  * Exportable options.
  * Importable options.

## Done

[Changelog](https://github.com/jaketr00/saveMusicInfo/blob/master/changelog.MD)

* Version 1.2
  * Very basic settings at "/settings/"
    * Change time zone
    * Custom theme
  * Minor bug fixes/unimportant features
  * Made an indicator to let you know that the song you are importing is already saved or a very similar song is already saved (same song different album).

#### What I will not do

* Full mobile support
  * "/show/backups" and "/show/edit" pages will not have a mobile theme.
* Add a button to start a YouTube video for the song to easily listen
  * This is too much of a hastle as YouTube APIs need "points" to use, and everyone who downloads this site will need 100+ "points" to do this.

# License

[**The MIT License (MIT)**](https://github.com/jaketr00/saveMusicInfo/blob/visual/LICENSE.md)

Feel free to use and edit this page howerver you want, just give back credit.
