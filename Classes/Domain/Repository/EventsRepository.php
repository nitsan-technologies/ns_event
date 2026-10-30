<?php

declare(strict_types=1);

namespace NITSAN\NsEvent\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * This file is part of the "Event" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2021
 */

/**
 * The repository for Events
 */
class EventsRepository extends Repository
{
    /**
     * @param int|null $offset
     * @param int|null $per_page
     * @param array $settings
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|array
     */
    public function fetchData($offset = null, $per_page = null, array $settings = [])
    {
        $query = $this->createQuery();
        $this->applyStoragePageSettings($query, $settings);
        $query->matching(
            $query->logicalOr(
                $query->equals('endDate', 0),
                $query->greaterThanOrEqual('endDate', time())
            )
        );
        if ($offset) {
            $query->setOffset($offset);
        }
        if ($per_page) {
            $query->setLimit($per_page);
        }
        return $query->execute();
    }

    /**
     * Load a frontend-visible event by uid (respects hidden/starttime/endtime).
     */
    public function findPublishedByUid(int $uid)
    {
        if ($uid < 1) {
            return null;
        }
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->matching($query->equals('uid', $uid));
        return $query->execute()->getFirst();
    }

    /**
     * @param QueryInterface $query
     * @param array $settings
     */
    protected function applyStoragePageSettings(QueryInterface $query, array $settings): void
    {
        $storagePages = GeneralUtility::intExplode(',', (string)($settings['pages'] ?? ''), true);
        if ($storagePages !== []) {
            $query->getQuerySettings()->setStoragePageIds($storagePages);
        }
    }
}
