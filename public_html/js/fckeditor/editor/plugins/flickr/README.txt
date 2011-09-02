===============================================================================
Name: Flickr for FCKeditor
Version: 1.0
Date: 01/03/2009
Requirements: Webserver, PHP installation, FCKeditor
Author: Simon Jensen
Source: http://www.simon-jensen.net/flickr-plugin-til-fckeditor.html
Homepage: http://www.simon-jensen.net

Description
===============================================================================
Flickr for FCKeditor is an easy to setup plugin for FCKeditor.
When installed you«ll be able to brows your images from Flickr using the
FCKeditor. Selecting an image using this plugin will automatically insert the
image and link to it, into the FCKeditor.
Viewing-, inserting- and linking-sizes can be choosen separatly when browsing.

Install
===============================================================================
1. Extract flickr.zip and copy the "flickr"-folder into the editors plugin-
   directory.
    /[editor_root]/editor/plugins/

2. Edit the file "actions.php" in the "flickr"-folder, inserting your own API-
   key and username.
    /[editor_root]/editor/plugins/flickr/actions.php

3. Edit the editors config file to load the flickr plugin.
    /[editor_root]/fckconfig.js

    Add the following somewhere below the line with: FCKConfig.PluginsPath
    FCKConfig.Plugins.Add( 'flickr', 'en' );

    Add the Flickr-plugin to your ToolbarSets of choise:
    ['Flickr']

3.1. You might need to empty your browseres cache before beeing able to see the
     new Flickr icon.

4. Start using Flickr for FCKeditor by clicking the new Flickr icon.

Known Issues or Bugs
===============================================================================
Non that I know of.

History
===============================================================================
1.0, 2009/01/03 - Initial release.

Tools Used
===============================================================================
phpFlickr 2.3.0.1 - http://phpflickr.com/2008/12/18/phpflickr-2301/
jQuery 1.2.6 - http://jquery.com/
FCKeditor 2.6 Beta 1 - http://www.fckeditor.net/download

Licensing/Legal
===============================================================================
Feel free to use, modify and distribute this plugin all you want!