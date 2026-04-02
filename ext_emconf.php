<?php

$EM_CONF['ns_event'] = [
    'title' => 'TYPO3 Event Manager',
    'description' => 'Create, manage, and display events on your TYPO3 website with ease. The extension offers features like customizable event details, ticket types, schedules, and more—all from a user-friendly backend.',
   
    'category' => 'plugin',
    'author' => 'Team T3Planet',
    'author_email' => 'info@t3planet.de',
    'author_company' => 'T3Planet',
    'state' => 'stable',
    'clearCacheOnLoad' => 0,
    'version' => '14.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.0.0-14.9.99',
            'php' => '7.4.0-8.5.0',
        ],
        'conflicts' => [],
        'suggests' => [],
    ]
];
