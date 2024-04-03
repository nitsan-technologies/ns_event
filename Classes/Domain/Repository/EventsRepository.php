<?php

declare(strict_types=1);

namespace NITSAN\NsEvent\Domain\Repository;

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

    public function fetchData($offset = null, $per_page = null)
    {
        $query =  $query = $this->createQuery();
        if ($offset) {
            $query->setOffset($offset);
        }
        if ($per_page) {
            $query->setLimit($per_page);
        }
        return $query->execute();
    }
}
