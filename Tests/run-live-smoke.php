<?php

declare(strict_types=1);

/**
 * Live smoke test for EXT:ns_event on a booted TYPO3 13/14 instance.
 *
 * Usage (inside the project, typically via `ddev exec`):
 *   php typo3conf/ext/ns_event/Tests/run-live-smoke.php setup
 *   php typo3conf/ext/ns_event/Tests/run-live-smoke.php check
 *   php typo3conf/ext/ns_event/Tests/run-live-smoke.php teardown
 */

use NITSAN\NsEvent\Backend\PremiumStrip;
use NITSAN\NsEvent\Domain\Model\Events;
use NITSAN\NsEvent\Domain\Repository\EventsRepository;
use NITSAN\NsEvent\Form\Container\PremiumStripFlexFormNoTabsContainer;
use NITSAN\NsEvent\Form\Resolver\PremiumStripFlexFormNoTabsContainerResolver;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileType;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$root = dirname(__DIR__, 4);
$classLoader = require $root . '/vendor/autoload.php';
$classLoader->addPsr4('NITSAN\\NsEvent\\', dirname(__DIR__) . '/Classes/');

SystemEnvironmentBuilder::run(4, SystemEnvironmentBuilder::REQUESTTYPE_CLI);
Bootstrap::init($classLoader, true);

$mode = $argv[1] ?? 'check';
$deprecations = [];
set_error_handler(static function (int $severity, string $message, string $file, int $line) use (&$deprecations): bool {
    if ($severity === E_DEPRECATED || $severity === E_USER_DEPRECATED) {
        $deprecations[] = sprintf('%s in %s:%d', $message, $file, $line);
        return true;
    }
    return false;
});

$failures = [];
$passes = 0;
$assert = static function (bool $ok, string $message) use (&$failures, &$passes): void {
    if ($ok) {
        $passes++;
        echo '  PASS  ' . $message . PHP_EOL;
        return;
    }
    $failures[] = $message;
    echo '  FAIL  ' . $message . PHP_EOL;
};

$marker = 'ns-event-phpcompat';
$now = time();
$version = GeneralUtility::makeInstance(Typo3Version::class);
$major = $version->getMajorVersion();
$baseUrl = rtrim((string)(getenv('DDEV_PRIMARY_URL') ?: ''), '/');

echo sprintf('TYPO3 %s | PHP %s | %s%s', $version->getVersion(), PHP_VERSION, $mode, PHP_EOL);

$packageManager = GeneralUtility::makeInstance(PackageManager::class);
$assert($packageManager->isPackageActive('ns_event'), 'ns_event is active');
$assert($packageManager->isPackageActive('ns_license'), 'ns_license is active');

$connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
$eventsConn = $connectionPool->getConnectionForTable('tx_nsevent_domain_model_events');
$schema = $eventsConn->createSchemaManager();
$assert($schema->tablesExist(['tx_nsevent_domain_model_events']), 'events table exists');

$tca = include dirname(__DIR__) . '/Configuration/TCA/tx_nsevent_domain_model_events.php';
$assert(is_array($tca) && isset($tca['columns']['poster_image']), 'events TCA file loads');
$posterColumn = $tca['columns']['poster_image'] ?? [];
$posterTypes = array_keys($posterColumn['overrideChildTca']['types'] ?? $posterColumn['config']['overrideChildTca']['types'] ?? []);
if ($major >= 14) {
    $assert(
        $posterTypes === [
            FileType::TEXT->value,
            FileType::IMAGE->value,
            FileType::AUDIO->value,
            FileType::VIDEO->value,
            FileType::APPLICATION->value,
        ],
        'v14 poster_image uses FileType enum values'
    );
} else {
    $assert(
        $posterTypes === [
            File::FILETYPE_TEXT,
            File::FILETYPE_IMAGE,
            File::FILETYPE_AUDIO,
            File::FILETYPE_VIDEO,
            File::FILETYPE_APPLICATION,
        ],
        'v13 poster_image uses File::FILETYPE_* constants'
    );
}

$assert(class_exists(Events::class), 'Events model class loads');
$assert(class_exists(EventsRepository::class), 'EventsRepository class loads');
$assert(class_exists(PremiumStrip::class), 'PremiumStrip class loads');
$assert(class_exists(PremiumStripFlexFormNoTabsContainer::class), 'FlexForm container class loads');
$assert(class_exists(PremiumStripFlexFormNoTabsContainerResolver::class), 'FlexForm resolver class loads');

try {
    $strip = GeneralUtility::makeInstance(PremiumStrip::class)->render();
    $assert(is_string($strip), 'PremiumStrip::render() returns a string');
} catch (\ArgumentCountError|\TypeError $e) {
    $assert(
        str_contains($e->getMessage(), 'UriBuilder'),
        'PremiumStrip is loadable; UriBuilder needs the backend container (CLI-only)'
    );
}

$repo = GeneralUtility::makeInstance(EventsRepository::class);
$assert($repo instanceof EventsRepository, 'EventsRepository is instantiable');

if ($mode === 'setup' || $mode === 'check') {
    $pagesConn = $connectionPool->getConnectionForTable('pages');
    $existing = $pagesConn->fetchOne(
        'SELECT uid FROM pages WHERE title = :title AND deleted = 0',
        ['title' => $marker . '-root']
    );
    if ($mode === 'setup' && !$existing) {
        $rootPid = 0;
        $pagesConn->insert('pages', [
            'pid' => $rootPid,
            'title' => $marker . '-root',
            'slug' => '/',
            'doktype' => 1,
            'is_siteroot' => 1,
            'hidden' => 0,
            'deleted' => 0,
            'sys_language_uid' => 0,
            'tstamp' => $now,
            'crdate' => $now,
            'sorting' => 256,
        ]);
        $siteRootUid = (int)$pagesConn->lastInsertId();

        $pagesConn->insert('pages', [
            'pid' => $siteRootUid,
            'title' => $marker . '-storage',
            'slug' => '/storage',
            'doktype' => 254,
            'hidden' => 0,
            'deleted' => 0,
            'sys_language_uid' => 0,
            'tstamp' => $now,
            'crdate' => $now,
            'sorting' => 256,
        ]);
        $storageUid = (int)$pagesConn->lastInsertId();

        $pagesConn->insert('pages', [
            'pid' => $siteRootUid,
            'title' => $marker . '-detail',
            'slug' => '/detail',
            'doktype' => 1,
            'hidden' => 0,
            'deleted' => 0,
            'sys_language_uid' => 0,
            'tstamp' => $now,
            'crdate' => $now,
            'sorting' => 512,
        ]);
        $detailUid = (int)$pagesConn->lastInsertId();

        $pagesConn->insert('pages', [
            'pid' => $siteRootUid,
            'title' => $marker . '-list',
            'slug' => '/list',
            'doktype' => 1,
            'hidden' => 0,
            'deleted' => 0,
            'sys_language_uid' => 0,
            'tstamp' => $now,
            'crdate' => $now,
            'sorting' => 768,
        ]);
        $listUid = (int)$pagesConn->lastInsertId();

        $flex = '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>'
            . '<T3FlexForms><data><sheet index="listConfig"><language index="lDEF">'
            . '<field index="settings.recordsPerPage"><value index="vDEF">2</value></field>'
            . '<field index="settings.detailPageId"><value index="vDEF">' . $detailUid . '</value></field>'
            . '</language></sheet></data></T3FlexForms>';

        $contentConn = $connectionPool->getConnectionForTable('tt_content');
        $contentConn->insert('tt_content', [
            'pid' => $listUid,
            'CType' => 'nsevent_pi1',
            'header' => $marker . '-list-plugin',
            'pages' => (string)$storageUid,
            'pi_flexform' => $flex,
            'colPos' => 0,
            'hidden' => 0,
            'deleted' => 0,
            'sys_language_uid' => 0,
            'tstamp' => $now,
            'crdate' => $now,
            'sorting' => 256,
        ]);
        $contentConn->insert('tt_content', [
            'pid' => $detailUid,
            'CType' => 'nsevent_pi2',
            'header' => $marker . '-detail-plugin',
            'colPos' => 0,
            'hidden' => 0,
            'deleted' => 0,
            'sys_language_uid' => 0,
            'tstamp' => $now,
            'crdate' => $now,
            'sorting' => 256,
        ]);

        $ts = "page = PAGE\n"
            . "page.10 < styles.content.get\n"
            . "plugin.tx_nsevent_pi1.view.templateRootPaths.0 = EXT:ns_event/Resources/Private/Templates/\n"
            . "plugin.tx_nsevent_pi1.view.partialRootPaths.0 = EXT:ns_event/Resources/Private/Partials/\n"
            . "plugin.tx_nsevent_pi1.view.layoutRootPaths.0 = EXT:ns_event/Resources/Private/Layouts/\n"
            . "plugin.tx_nsevent_pi2.view.templateRootPaths.0 = EXT:ns_event/Resources/Private/Templates/\n"
            . "plugin.tx_nsevent_pi2.view.partialRootPaths.0 = EXT:ns_event/Resources/Private/Partials/\n"
            . "plugin.tx_nsevent_pi2.view.layoutRootPaths.0 = EXT:ns_event/Resources/Private/Layouts/\n";
        $templateConn = $connectionPool->getConnectionForTable('sys_template');
        $templateConn->insert('sys_template', [
            'pid' => $siteRootUid,
            'title' => $marker . '-ts',
            'root' => 1,
            'clear' => 3,
            'include_static_file' => 'EXT:fluid_styled_content/Configuration/TypoScript/,EXT:fluid_styled_content/Configuration/TypoScript/Styling/',
            'config' => $ts,
            'hidden' => 0,
            'deleted' => 0,
            'tstamp' => $now,
            'crdate' => $now,
        ]);

        for ($i = 1; $i <= 5; $i++) {
            $eventsConn->insert('tx_nsevent_domain_model_events', [
                'pid' => $storageUid,
                'title' => $marker . '-event-' . $i,
                'teaser' => 'Teaser ' . $i,
                'description' => '<p>Description ' . $i . '</p>',
                'address' => 'Test venue ' . $i,
                'start_date' => $now + ($i * 86400),
                'end_date' => $now + ($i * 86400) + 3600,
                'hidden' => 0,
                'deleted' => 0,
                'sys_language_uid' => 0,
                'tstamp' => $now,
                'crdate' => $now,
            ]);
        }

        $siteDir = $root . '/typo3conf/sites/' . $marker;
        if (!is_dir($siteDir)) {
            mkdir($siteDir, 0775, true);
        }
        $siteBase = $baseUrl !== '' ? $baseUrl . '/' . $marker : 'https://localhost/' . $marker;
        file_put_contents($siteDir . '/config.yaml', <<<YAML
base: '{$siteBase}'
dependencies: []
languages:
  -
    title: English
    enabled: true
    languageId: 0
    base: /
    locale: en_US.UTF-8
    iso-639-1: en
    navigationTitle: English
    flag: us
rootPageId: {$siteRootUid}
websiteTitle: 'ns_event PHP compatibility'
YAML
        );
        echo "  SETUP siteRoot={$siteRootUid} list={$listUid} detail={$detailUid} storage={$storageUid}\n";
    }
}

if ($mode === 'teardown') {
    $pagesConn = $connectionPool->getConnectionForTable('pages');
    $contentConn = $connectionPool->getConnectionForTable('tt_content');
    $templateConn = $connectionPool->getConnectionForTable('sys_template');
    $eventsConn->executeStatement('DELETE FROM tx_nsevent_domain_model_events WHERE title LIKE ?', [$marker . '%']);
    $contentConn->executeStatement('DELETE FROM tt_content WHERE header LIKE ?', [$marker . '%']);
    $templateConn->executeStatement('DELETE FROM sys_template WHERE title LIKE ?', [$marker . '%']);
    $pagesConn->executeStatement('DELETE FROM pages WHERE title LIKE ?', [$marker . '%']);
    $siteDir = $root . '/typo3conf/sites/' . $marker;
    if (is_dir($siteDir)) {
        @unlink($siteDir . '/config.yaml');
        @rmdir($siteDir);
    }
    echo "  TEARDOWN done\n";
}

$pagesConn = $connectionPool->getConnectionForTable('pages');
$listUid = (int)$pagesConn->fetchOne('SELECT uid FROM pages WHERE title = ? AND deleted = 0', [$marker . '-list']);
$detailUid = (int)$pagesConn->fetchOne('SELECT uid FROM pages WHERE title = ? AND deleted = 0', [$marker . '-detail']);
$storageUid = (int)$pagesConn->fetchOne('SELECT uid FROM pages WHERE title = ? AND deleted = 0', [$marker . '-storage']);
$eventUid = (int)$eventsConn->fetchOne(
    'SELECT uid FROM tx_nsevent_domain_model_events WHERE title = ? AND deleted = 0',
    [$marker . '-event-1']
);

if ($mode !== 'teardown') {
    $assert($listUid > 0, 'list page exists');
    $assert($detailUid > 0, 'detail page exists');
    $assert($storageUid > 0, 'storage folder exists');
    $count = (int)$eventsConn->fetchOne(
        'SELECT COUNT(*) FROM tx_nsevent_domain_model_events WHERE title LIKE ? AND deleted = 0',
        [$marker . '%']
    );
    $assert($count === 5, 'five test events exist');
}

if ($mode === 'check' && $baseUrl !== '' && $listUid > 0) {
    $listUrl = $baseUrl . '/' . $marker . '/list';

    $fetch = static function (string $url): array {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_HTTPHEADER => ['Accept: text/html'],
        ]);
        $body = (string)curl_exec($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        return [$status, $body, $err];
    };

    $absolute = static function (string $href, string $origin): string {
        $href = html_entity_decode($href, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        if (str_starts_with($href, 'http://') || str_starts_with($href, 'https://')) {
            return $href;
        }
        return $origin . $href;
    };

    [$status, $body, $err] = $fetch($listUrl);
    $assert($err === '', 'list request has no curl error' . ($err !== '' ? ": $err" : ''));
    $assert($status === 200, "list page HTTP 200 (got {$status})");
    $assert(!str_contains($body, 'Oops, an error occurred') && !str_contains($body, 'TYPO3 Exception'), 'list page is not a TYPO3 exception');
    $assert(!str_contains($body, 'Deprecated:'), 'list page has no PHP Deprecated output');
    $assert(str_contains($body, $marker . '-event-1'), 'list page shows first event');
    $assert(str_contains($body, $marker . '-event-2'), 'list page shows second event (recordsPerPage=2)');
    $assert(!str_contains($body, $marker . '-event-3'), 'list page 1 does not show the third event');
    $assert(str_contains($body, 'Event List'), 'list page shows Event List headline');
    $assert(str_contains($body, 'Next') || str_contains($body, 'ns-pagination'), 'list page shows pagination');

    preg_match('#^https?://[^/]+#', $baseUrl, $originMatch);
    $origin = $originMatch[0] ?? $baseUrl;
    preg_match('#href="([^"]*currentPage%5D=2[^"]*)"#', $body, $page2Match)
        || preg_match('#href="([^"]*currentPage=2[^"]*)"#', $body, $page2Match);
    $assert(isset($page2Match[1]), 'list page exposes a page-2 pagination link');
    if (isset($page2Match[1])) {
        [$status2, $body2] = $fetch($absolute($page2Match[1], $origin));
        $assert($status2 === 200, "list page 2 HTTP 200 (got {$status2})");
        $assert(str_contains($body2, $marker . '-event-3'), 'list page 2 shows the third event');
        $assert(!str_contains($body2, 'TYPO3 Exception'), 'list page 2 is not a TYPO3 exception');
    }

    preg_match('#href="([^"]*events%5D=' . preg_quote((string)$eventUid, '#') . '[^"]*)"#', $body, $detailMatch)
        || preg_match('#href="([^"]*/detail[^"]*)"#', $body, $detailMatch);
    $assert(isset($detailMatch[1]), 'list page exposes a detail link for the first event');
    if (isset($detailMatch[1])) {
        [$status3, $body3] = $fetch($absolute($detailMatch[1], $origin));
        $assert($status3 === 200, "detail page HTTP 200 (got {$status3})");
        $assert(!str_contains($body3, 'TYPO3 Exception'), 'detail page is not a TYPO3 exception');
        $assert(str_contains($body3, $marker . '-event-1'), 'detail page shows the requested event');
        $assert(str_contains($body3, 'Teaser 1') || str_contains($body3, 'Test venue 1'), 'detail page shows event fields');
    }
}

$nsEventDeprecations = array_filter(
    $deprecations,
    static fn(string $d): bool => str_contains($d, '/ns_event/Classes/') || str_contains($d, '/ns_event/Configuration/')
);
$assert($nsEventDeprecations === [], 'no PHP deprecations from ns_event (' . count($nsEventDeprecations) . ')');
if ($nsEventDeprecations) {
    foreach ($nsEventDeprecations as $d) {
        echo '    DEP  ' . $d . PHP_EOL;
    }
}

echo sprintf('Result: %d passed, %d failed%s', $passes, count($failures), PHP_EOL);
if ($failures) {
    foreach ($failures as $f) {
        echo '  - ' . $f . PHP_EOL;
    }
    exit(1);
}
exit(0);
