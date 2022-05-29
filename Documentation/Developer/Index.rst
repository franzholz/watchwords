.. include:: ../Includes.txt

.. _developer:

================
Developer Corner
================


The current watchword is read in from a news feed by an internal API.


.. _developer-api:

API
===

How to use the API...

.. code-block:: php

   use JambageCom\Watchwords\Api\BibleWebApi;

   $watchword = [];
   $bibleApi = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
      BibleWebApi::class
   );
   $bibleApi->getWatchwordsFromBiblegateway($watchword, $this->settings);
   $this->view->assign('watchword', $watchword);


