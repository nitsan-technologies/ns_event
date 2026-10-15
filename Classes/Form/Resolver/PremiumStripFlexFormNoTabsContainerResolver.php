<?php

declare(strict_types=1);

namespace NITSAN\NsEvent\Form\Resolver;

use NITSAN\NsEvent\Form\Container\PremiumStripFlexFormNoTabsContainer;
use TYPO3\CMS\Backend\Form\NodeResolverInterface;

/**
 * Uses PremiumStripFlexFormNoTabsContainer for the plugin options (pi_flexform) of "NS Event: List View" elements.
 */
final class PremiumStripFlexFormNoTabsContainerResolver implements NodeResolverInterface
{
    private const PLUGIN_SIGNATURE = 'nsevent_pi1';

    private array $data = [];

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function resolve(): ?string
    {
        if (($this->data['tableName'] ?? '') !== 'tt_content'
            || ($this->data['fieldName'] ?? '') !== 'pi_flexform'
            || !empty($this->data['isInlineChild'])
        ) {
            return null;
        }

        return (string)($this->data['recordTypeValue'] ?? '') === self::PLUGIN_SIGNATURE
            ? PremiumStripFlexFormNoTabsContainer::class
            : null;
    }
}
