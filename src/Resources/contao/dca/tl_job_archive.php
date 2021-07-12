<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

$GLOBALS['TL_DCA']['tl_job_archive'] = [
    'config' => [
        'dataContainer' => 'Table',
        'switchToEdit' => true,
        'enableVersioning' => true,
        'onload_callback' => [
            [\HeimrichHannot\JobBundle\DataContainer\JobArchiveContainer::class, 'checkPermission'],
        ],
        'onsubmit_callback' => [
            ['huh.utils.dca', 'setDateAdded'],
        ],
        'oncopy_callback' => [
            ['huh.utils.dca', 'setDateAddedOnCopy'],
        ],
        'sql' => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
    ],
    'list' => [
        'label' => [
            'fields' => ['title'],
            'format' => '%s',
        ],
        'sorting' => [
            'mode' => 1,
            'fields' => ['title'],
            'headerFields' => ['title'],
            'panelLayout' => 'filter;search,limit',
        ],
        'global_operations' => [
            'all' => [
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
        ],
        'operations' => [
            'edit' => [
                'label' => &$GLOBALS['TL_LANG']['tl_job_archive']['edit'],
                'href' => 'table=tl_job',
                'icon' => 'edit.gif',
            ],
            'editheader' => [
                'label' => &$GLOBALS['TL_LANG']['tl_job_archive']['editheader'],
                'href' => 'act=edit',
                'icon' => 'header.gif',
                'button_callback' => [\HeimrichHannot\JobBundle\DataContainer\JobArchiveContainer::class, 'editHeader'],
            ],
            'copy' => [
                'label' => &$GLOBALS['TL_LANG']['tl_job_archive']['copy'],
                'href' => 'act=copy',
                'icon' => 'copy.gif',
                'button_callback' => [\HeimrichHannot\JobBundle\DataContainer\JobArchiveContainer::class, 'copyArchive'],
            ],
            'delete' => [
                'label' => &$GLOBALS['TL_LANG']['tl_job_archive']['copy'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\''.$GLOBALS['TL_LANG']['MSC']['deleteConfirm'].'\'))return false;Backend.getScrollOffset()"',
                'button_callback' => [\HeimrichHannot\JobBundle\DataContainer\JobArchiveContainer::class, 'deleteArchive'],
            ],
            'show' => [
                'label' => &$GLOBALS['TL_LANG']['tl_job_archive']['show'],
                'href' => 'act=show',
                'icon' => 'show.gif',
            ],
        ],
    ],
    'palettes' => [
        'default' => '{general_legend},title,memberContacts;',
    ],
    'fields' => [
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'tstamp' => [
            'label' => &$GLOBALS['TL_LANG']['tl_job_archive']['tstamp'],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'dateAdded' => [
            'label' => &$GLOBALS['TL_LANG']['MSC']['dateAdded'],
            'sorting' => true,
            'flag' => 6,
            'eval' => ['rgxp' => 'datim', 'doNotCopy' => true],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'title' => [
            'label' => &$GLOBALS['TL_LANG']['tl_job_archive']['title'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'memberContacts' => [
            'label' => &$GLOBALS['TL_LANG']['tl_job_archive']['memberContacts'],
            'inputType' => 'select',
            'options_callback' => function (\Contao\DataContainer $dc) {
                return System::getContainer()->get('huh.utils.choice.model_instance')->getCachedChoices([
                    'dataContainer' => 'tl_member',
                ]);
            },
            'eval' => ['multiple' => true, 'chosen' => true, 'tl_class' => 'w50'],
            'sql' => 'blob NULL',
        ],
    ],
];
