<?php

declare(strict_types=1);

namespace NITSAN\NsEvent\Controller;

use Doctrine\DBAL\Exception;
use NITSAN\NsEvent\Domain\Repository\EventsRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * This file is part of the "Event" Extension for TYPO3 CMS.
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

    /**
     * @var ContentObjectRenderer
     */
    protected ContentObjectRenderer $currentContentObject;

    public function __construct(
        EventsRepository $eventsRepository,
        ContentObjectRenderer $currentContentObject
    ) {
        $this->eventsRepository = $eventsRepository;
        $this->currentContentObject = $currentContentObject;
    }

    /**
     * action list
     *
     */
    public function listAction()
    {
        // Access data directly from currentContentObject
        $currentPage = 1;
        $itemsPerPage = (int) $this->settings['recordsPerPage'] ?? 10;
        if ($this->getCurrentVersion() <= 10) {
            $response = GeneralUtility::_GET('tx_nsevent_pi1');
        } else {
            $response = $this->request->getQueryParams()['tx_nsevent_pi1'] ?? [];
        }
        if (!empty($response['currentPage'])) {
            $currentPage = (int) $response['currentPage'];
        }
        $response['pluginUid'] = $response['pluginUid'] ?? '';

        if (isset($data['uid']) && $response['pluginUid'] != $data['uid']) {
            $currentPage = 1;
        }

        $offset = ($currentPage - 1) * $itemsPerPage;
        $allEvents = $this->eventsRepository->fetchData();
        $events = $this->eventsRepository->fetchData($offset, $itemsPerPage);
        $countEvents = count($allEvents);

        $this->initiatePagination($countEvents, $itemsPerPage, $currentPage);

        $this->view->assignMultiple(
            [
                'events' => $events,
                'settings' => $this->settings
            ]

        );
        if ($this->getCurrentVersion() >= 11) {
            return $this->htmlResponse();
        }
    }



    /**
     * initiatePagination
     *
     * @param integer $countEvents
     * @param integer $itemsPerPage
     * @param integer $currentPage
     * @return void
     */
    private function initiatePagination(int $countEvents, int $itemsPerPage, int $currentPage): void
    {
        $totalPage = ceil($countEvents / $itemsPerPage);
        $previous = ($currentPage > 1) ? $currentPage - 1 : '';
        $next = ($currentPage < $totalPage) ? $currentPage + 1 : '';

        $indexMin = max(1, $currentPage - 5);
        $indexMax = min($totalPage, $currentPage + 3);

        $pages = range($indexMin, $indexMax);

        $this->view->assignMultiple([
            'totalpage' => $totalPage,
            'previous' => $previous,
            'next' => $next,
            'pages' => $pages,
            'currentPage' => $currentPage,
        ]);
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
            if (isset($this->request->getQueryParams()['tx_nsevent_pi1'])) {
                $response = $this->request->getQueryParams()['tx_nsevent_pi1'];
            } elseif (isset($this->request->getQueryParams()['tx_nsevent_pi2'])) {
                $response = $this->request->getQueryParams()['tx_nsevent_pi2'];
            }
        }
        if (!empty($response['events'])) {
            $getEvent = $this->eventsRepository->findByUid((int) $response['events']);

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
