zotero-bib-maker
================

BibTex Maker for Zotero Collection

This PHP script is a tool to pull down a collection from zotero as a bibtex file to add to my php latex make file.  It will pull down a specific collection and create the bib file as the first step before building the latex document.   

Before running the script add your API key from https://www.zotero.org/settings/keys to the script, take a note of your userid on this page where it says "Your userID for use in API calls is ..."

Then grab your collection ID from the folder you want to use. For example I use a folder called Collection.  Using the web viewer click the folder and the url will look like - https://www.zotero.org/frogosteve/items/collectionKey/XXXXXXXX where X is the collection id.  Fill in the blanks at the top of the script.
 
To run the script add your own API key and add it then call: 
  * php getzoterobib.php
  
