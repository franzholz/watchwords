TYPO3 extension watchwords
==========================

What is does
------------

Use this extension to show daily bible verses in multiple languages.

Configuration
-------------

Insert "Display daily Christian Watchwords (watchwords_watch)" in the Template module setup under "Include static (from extensions)".

  * Use the constant editor or the TypoScript setup.
  * Insert an extension plugin and use its flexform.

You can show the watchwords on every page by a marker inserted into your main
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
