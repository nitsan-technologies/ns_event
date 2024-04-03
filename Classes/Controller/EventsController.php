<?php

declare(strict_types=1);

namespace NITSAN\NsEvent\Controller;

use Doctrine\DBAL\Exception;
use NITSAN\NsEvent\Domain\Repository\EventsRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * This file is part of the "NsEvent" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2021
 */

/**
 * EventsController
 */
class EventsController extends ActionController
{
    /**
     * @var EventsRepository
     */
    protected EventsRepository $eventsRepository;

    public function __construct(
        EventsRepository $eventsRepository
    ) {
        $this->eventsRepository = $eventsRepository;
    }

    /**
     * action list
     *
     */
    public function listAction()
    {

        $currentPage = 1;
        $itemsPerPage = (int)$this->settings['recordsPerPage'];
        if ($this->getCurrentVersion() <= 10) {
            $response = GeneralUtility::_GET('tx_nsevent_pi1');
        } else {
            $response = $this->request->getQueryParams()['tx_nsevent_pi1'] ?? [];
        }
        if(!empty($response['currentPage'])) {
            $currentPage = $response['currentPage'];
        }
        $offset = ($currentPage-1) * $itemsPerPage;
        $allEvents = $this->eventsRepository->fetchData(); 
        $events = $this->eventsRepository->fetchData($offset, $itemsPerPage);
        $countEvents = count($allEvents);
        $totalPage = ceil($countEvents / $itemsPerPage);
        if ($currentPage > 1) {
            $previous = $currentPage - 1;
        }
        if ($currentPage < $totalPage) {
            $next = $currentPage + 1;
        }
        $pages = [];
        for ($x = 1; $x<= $totalPage; $x++) {
            $pages[] = $x;
        }
        $i = 0;
        $modifiedObjects = [];
        $indexMin = $currentPage - 5;  // -1  to show pages
        $indexMax = $currentPage + 3; // -1  to show pages
        foreach ($pages as $object) {
            if ($i >= $indexMin && $i <= $indexMax) {
                $modifiedObjects[] = $object;
            }
            $i++;
        }
        $previous = isset($previous) ? $previous : '';
        $next = isset($next) ? $next : '';

        $this->view->assignMultiple(
            [
                'events' => $events,
                'settings' => $this->settings,
                'totalpage' => $totalPage,
                'previous' => $previous,
                'next' => $next,
                'pages' => $modifiedObjects,
                'currentPage' => $currentPage,
            ]
        );
        
        if ($this->getCurrentVersion() >= 11) {
            return $this->htmlResponse();
        }
    }

    /**
     * action show
     *
     * @throws Exception
     */
    public function showAction()
    {
        $response = [];

        if ($this->getCurrentVersion() <= 10) {
            if(!empty(GeneralUtility::_GET('tx_nsevent_pi1'))) {
                $response = GeneralUtility::_GET('tx_nsevent_pi1');
            } elseif(!empty(GeneralUtility::_GET('tx_nsevent_pi2'))) {
                $response = GeneralUtility::_GET('tx_nsevent_pi2');
            }
        } else {
            if(isset($this->request->getQueryParams()['tx_nsevent_pi1'])) {
                $response = $this->request->getQueryParams()['tx_nsevent_pi1'];
            } elseif(isset($this->request->getQueryParams()['tx_nsevent_pi2'])) {
                $response = $this->request->getQueryParams()['tx_nsevent_pi2'];
            }
        }

        if(!empty($response)) {
            $getEvent = $this->eventsRepository->findByUid((int)$response['events']);

            $this->view->assignMultiple([
                'events' => $getEvent,
                // @extensionScannerIgnoreLine
                'currantPage' => $GLOBALS['TSFE']->id,
            ]);
        }
        if ($this->getCurrentVersion() >= 11) {
            return $this->htmlResponse();
        }
    }

    /**
     * @return int
     */
    private function getCurrentVersion(): int
    {
        $typo3VersionArray = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionStringToArray(
            \TYPO3\CMS\Core\Utility\VersionNumberUtility::getCurrentTypo3Version()
        );
        
        return $typo3VersionArray['version_main'];
    }
}
