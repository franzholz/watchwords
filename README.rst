TYPO3 extension watchwords
==========================

What is does
------------

Use this extension to show daily bible verses.

Configuration
-------------

Use the constant editor. Or insert a plugin and use the flexform.

You can also show the watchwords on every page by a marker of your main
page template.

example:
~~~~~~~~

::

   lib.watchwordsLib = USER
   lib.watchwordsLib {
       userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run 
       pluginName = Watch
       extensionName = Watchwords
       controller = WatchwordsController
       action = index
       settings =< plugin.tx_watchwords.settings
       view =< plugin.tx_watchwords.view
   }

   page = PAGE
   ... 
   page.10 {

       marks.WATCHWORD < lib.watchwordsLib
   }
