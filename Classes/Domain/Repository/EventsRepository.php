<?php

declare(strict_types=1);

namespace NITSAN\NsEvent\Domain\Repository;

use Doctrine\DBAL\Exception;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * This file is part of the "NsEvent" Extension for TYPO3 CMS.
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
     * @param $searchData
     * @return array
     * @throws InvalidQueryException|Exception
     */
    public function filterSearch(): array
    {
        $result = $this->createQuery()->execute()->toArray();
        return $result;
    }

}
