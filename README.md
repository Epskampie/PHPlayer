PHPlayer
========

PHPlayer allows you to easily upload your music to your server, and then play it through any modern webbrowser.
A personal grooveshark, if you will.

![Uploading and playback on desktop Works on mobile too!](https://raw.github.com/Epskampie/PHPlayer/master/docs/img/screengrabs.png)

Features
--------

* Easy drag-and-drop uploading of new albums.
* Playback through htm5 audio element, working in any modern browser.
* Support for mp3 and OGG audio files.
* No database required.
* Simple installation. Just copy files and make 3 folder writable.

Requirements
------------

* A server with a webserver capable of running php files.
* A very basic understanding of how to install a webapp on your server.

Installation
------------

* Download the latest distribution, and unpack the files under the webroot of your webserver.
* [Linux only]: Make the following folders in the phplayer directory world readable/writable:
** phplayer/app/cache
** phplayer/app/logs
** phplayer/web/uploads
* Browse to http://YOUR-HOST-NAME/music
* Upload some albums.
* Enjoy your music online!