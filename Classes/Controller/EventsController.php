<?php

declare(strict_types=1);

namespace NITSAN\NsEvent\Controller;

use Doctrine\DBAL\Exception;
use NITSAN\NsEvent\Domain\Repository\EventsRepository;
use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use Psr\Http\Message\ResponseInterface;
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
        EventsRepository $eventsRepository,
    ) {
        $this->eventsRepository = $eventsRepository;
    }

    /**
     * action list
     *
     * @return ResponseInterface
     */
    public function listAction(): ResponseInterface
    {
        $events[] = $this->eventsRepository->findAll()->toArray();
        $this->initializePagination($events);
        return $this->htmlResponse();
    }

    /**
     * @param array $events
     * @return void
     */
    public function initializePagination(array $events): void
    {
        $currentPage = 1;
        $response = $this->request->getQueryParams()['tx_nsevent_pi1'] ?? [];
        if(!empty($response['currentPage'])) {
            $currentPage = $response['currentPage'];
        }

        $arrayPaginator = new ArrayPaginator($events[0], (int)$currentPage, (int)$this->settings['recordsPerPage']);
        $pagination = new SimplePagination($arrayPaginator);
        $this->view->assign('pages', $pagination->getLastPageNumber());

        $this->view->assignMultiple(
            [
                'events' => $events[0],
                'paginator' => $arrayPaginator,
                'pagination' => $pagination,
                'paginationType' => 'paginator',
                'settings' => $this->settings,
            ]
        );
    }


    /**
     * action show
     *
     * @return ResponseInterface
     * @throws Exception
     */
    public function showAction(): ResponseInterface
    {
        $response = [];

        if(isset($this->request->getQueryParams()['tx_nsevent_pi1'])) {
            $response = $this->request->getQueryParams()['tx_nsevent_pi1'];
        } elseif(isset($this->request->getQueryParams()['tx_nsevent_pi2'])) {
            $response = $this->request->getQueryParams()['tx_nsevent_pi2'];
        }
        if(!empty($response)) {
            $getEvent = $this->eventsRepository->findByUid((int)$response['events']);

            $this->view->assignMultiple([
                'events' => $getEvent,
                // @extensionScannerIgnoreLine
                'currantPage' => $GLOBALS['TSFE']->id,
            ]);
        }
        return $this->htmlResponse();
    }
}
