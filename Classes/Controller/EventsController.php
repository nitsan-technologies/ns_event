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
        $itemsPerPage = (int)($this->settings['recordsPerPage'] ?? 10);
        if ($itemsPerPage < 1) {
            $itemsPerPage = 10;
        }
        $response = $this->request->getQueryParams()['tx_nsevent_pi1'] ?? [];
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
            return $this->htmlResponse();
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
        $queryParams = $this->request->getQueryParams();
        $sources = [
            $this->request->getArguments(),
            is_array($queryParams['tx_nsevent_pi2'] ?? null) ? $queryParams['tx_nsevent_pi2'] : [],
            is_array($queryParams['tx_nsevent_pi1'] ?? null) ? $queryParams['tx_nsevent_pi1'] : [],
        ];
        $eventUid = 0;
        foreach ($sources as $source) {
            foreach (['events', 'event'] as $argumentName) {
                if (!isset($source[$argumentName]) || $source[$argumentName] === '') {
                    continue;
                }
                $candidate = $source[$argumentName];
                if (is_object($candidate) && method_exists($candidate, 'getUid')) {
                    $candidate = (int)$candidate->getUid();
                } elseif (is_array($candidate) && isset($candidate['uid'])) {
                    $candidate = (int)$candidate['uid'];
                } else {
                    $candidate = (int)$candidate;
                }
                if ($candidate > 0) {
                    $eventUid = $candidate;
                    break 2;
                }
            }
        }
        if ($eventUid > 0) {
            $getEvent = $this->eventsRepository->findByUid($eventUid);

            $this->view->assignMultiple([
            'events' => $getEvent,
            'currantPage' => $this->request->getAttribute('frontend.page.information')->getId(),
        ]);
        }
            return $this->htmlResponse();
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
