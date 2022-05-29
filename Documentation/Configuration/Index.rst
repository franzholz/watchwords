.. include:: ../Includes.txt

.. _configuration:

=============
Configuration
=============

You can configure each plugin individually in the TYPO3 backend. 
see :ref:`for-editors`
Alternatively and additionally you can use a setup which is valid globally.
The plugin configuration has precedence.

Priorities
----------

Several properties of this extension can be defined in different ways. For such properties, the following priorities in getting the respective values are applied:

Bible version
~~~~~~~~~~~~~

#. Bible version defined in the Plugin
#. Value of the TypoScript property :typoscript:`plugin.tx_watchwords.settings.bibleVersion`  
#. Default bible version (English: New International Version)



.. _tsBibleVersion:

Bible version `bibleVersion`
----------------------------

.. confval:: bibleVersion

    :type: integer
   :Default: 31 - “English: New International Version”
   :Path: plugin.tx_watchwords.settings
   :Scope: Plugin, TypoScript Setup

.. t3-field-list-table::
   :header-rows: 1

   -  :Code:         Code
      :Language:     Language
      :Bible:        Bible Title

   -  :Code:        1
      :Language:    Albanian
      :Bible:       Albanian Bible

   -  :Code:       21
      :Language:   Bulgarian
      :Bible:      Bulgarian Bible

   -  :Code:       80
      :Language:   Chinese (Simplified)
      :Bible:      Chinese Union Version

   -  :Code:       22
      :Language:   Chinese (Traditional)
      :Bible:      Chinese Union Version

   -  :Code:       11
      :Language:   Danish
      :Bible:      Dette er Biblen på dansk

   -  :Code:       8
      :Language:   English
      :Bible:      American Standard Version

   -  :Code:       16
      :Language:   English
      :Bible:      Darby Translation

   -  :Code:       47
      :Language:   English
      :Bible:      English Standard Version

   -  :Code:       9
      :Language:   English
      :Bible:      King James Version

   -  :Code:       31
      :Language:   English
      :Bible:      New International Version

   -  :Code:       15
      :Language:   English
      :Bible:      Young's Literal Translation

   -  :Code:       2
      :Language:   French
      :Bible:      Louis Segond

   -  :Code:       10
      :Language:   German
      :Bible:      Luther Bibel 1545

   -  :Code:       69
      :Language:   Greek
      :Bible:      1550 Stephanus New Testament

   -  :Code:       68
      :Language:   Greek
      :Bible:      1881 Westcott-Hort New Testament

   -  :Code:       70
      :Language:   Greek
      :Bible:      1894 Scrivener New Testament

   -  :Code:       23
      :Language:   Haïtian Creole
      :Bible:      Haïtian Creole Version

   -  :Code:       81
      :Language:   Hebrew
      :Bible:      The Westminster Leningrad Codex

   -  :Code:       17
      :Language:   Hungarian
      :Bible:      Hungarian Kairoli

   -  :Code:       18
      :Language:   Icelandic
      :Bible:      Icelandic Bible

   -  :Code:       3
      :Language:   Italian
      :Bible:      Conferenza Episcopale Italiana

   -  :Code:       20
      :Language:   Korean
      :Bible:      Korean Bible

   -  :Code:       4
      :Language:   Latin
      :Bible:      Biblia Sacra Vulgata

   -  :Code:       24
      :Language:   Maori
      :Bible:      Maori Bible

   -  :Code:       5
      :Language:   Norwegian
      :Bible:      Det Norsk Bibelselskap 1930

   -  :Code:       12
      :Language:   Polish
      :Bible:      Biblia Tysiaclecia

   -  :Code:       25
      :Language:   Portugese
      :Bible:      João Ferreira de Almeida Atualizada

   -  :Code:       14
      :Language:   Romanian
      :Bible:      Romanian

   -  :Code:       13
      :Language:   Russian
      :Bible:      Russian Synodal Version

   -  :Code:       42
      :Language:   Spanish
      :Bible:      Nueva Versión Internacional

   -  :Code:       6
      :Language:   Spanish
      :Bible:      Reina-Valera Antigua

   -  :Code:       7
      :Language:   Swedish
      :Bible:      Svenska 1917

   -  :Code:       27
      :Language:   Ukrainian
      :Bible:      Ukrainian Bible

   -  :Code:       19
      :Language:   Vietnamese
      :Bible:      1934 Vietnamese Bible


   The code numbers for the bible versions are available in the file :file:`Resources/Private/Language/locallang.xlf` under the entries "pi1_flexform.bibleVersion" . 


.. _tsTimeOffset:

Time Offset `timeOffset`
------------------------

.. confval:: timeOffset

   :type: integer
   :Default: 0
   :Path: plugin.tx_watchwords.settings
   :Scope: Plugin, TypoScript Setup

   The timeOffset is not used. It is intended to delay a daily watchword update by this time span.


.. _tsTestFile:

Bible version `testFile`
----------------------------

.. confval:: testFile

   :type: string
   :Default: 0
   :Path: plugin.tx_watchwords.settings
   :Scope: Plugin, TypoScript Setup

   If the test file is set, then its content will be shown instead of a daily watchword. Use a news reader and save its content into a test file. Example: :file:`/Resources/Public/Examples/Verse_Feb_06_2022.xml` .

   
.. _tsStandard:

Standard error text `standard`
------------------------------

.. confval:: standard

   :type: stdWrap
   :Default: 
        standard = TEXT
        standard.value = Sorry, no data could be fetched!
   :Path: plugin.tx_watchwords.settings
   :Scope: Plugin, TypoScript Setup

   Text that will be displayed, if no watchword could be fetched with the current configuration.


.. _configuration-every-page:

How to show a watchword on every page:
--------------------------------------
You can show the watchwords on every page by a marker inserted into your main
page template.

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


Extbase/Fluid
-------------

Use the common Extbase/Fluid setup if you want to modify the template files.

See :ref:`t3extbasebook:configuration`.

