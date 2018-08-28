<?php

$GLOBALS['TL_DCA']['tl_job_archive'] = [
    'config' => [
        'dataContainer'     => 'Table',
        'switchToEdit'                => true,
        'enableVersioning'  => true,
        'onload_callback' => [
            ['huh.job.listener.job_archive_callback', 'checkPermission'],
        ],
        'onsubmit_callback' => [
            ['huh.utils.dca', 'setDateAdded'],
        ],
        'oncopy_callback'   => [
            ['huh.utils.dca', 'setDateAddedOnCopy'],
        ],
        'sql' => [
            'keys' => [
                'id' => 'primary'
            ]
        ]
    ],
    'list' => [
        'label' => [
            'fields' => ['title'],
            'format' => '%s'
        ],
        'sorting'           => [
            'mode'                  => 1,
            'fields'                => ['title'],
            'headerFields'          => ['title'],
            'panelLayout'           => 'filter;search,limit'
        ],
        'global_operations' => [
            'all'    => [
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();"'
            ],
        ],
        'operations' => [
            'edit' => [
                'label'               => &$GLOBALS['TL_LANG']['tl_job_archive']['edit'],
                'href'                => 'table=tl_job',
                'icon'                => 'edit.gif'
            ],
            'editheader' => [
                'label'               => &$GLOBALS['TL_LANG']['tl_job_archive']['editheader'],
                'href'                => 'act=edit',
                'icon'                => 'header.gif',
                'button_callback'     => ['huh.job.listener.job_archive_callback', 'editHeader']
            ],
            'copy' => [
                'label'               => &$GLOBALS['TL_LANG']['tl_job_archive']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif',
                'button_callback'     => ['huh.job.listener.job_archive_callback', 'copyArchive']
            ],
            'delete' => [
                'label'               => &$GLOBALS['TL_LANG']['tl_job_archive']['copy'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
                'button_callback'     => ['huh.job.listener.job_archive_callback', 'deleteArchive']
            ],
            'show' => [
                'label'               => &$GLOBALS['TL_LANG']['tl_job_archive']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            ],
            'toggle' => [
                'label'               => &$GLOBALS['TL_LANG']['tl_job_archive']['toggle'],
                'href'                => 'act=toggle',
                'icon'                => 'toggle.gif'
            ],
        ]
    ],
    'palettes' => [
        'default' => '{general_legend},title;'
    ],
    'fields'   => [
        'id' => [
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ],
        'tstamp' => [
            'label'                   => &$GLOBALS['TL_LANG']['tl_job_archive']['tstamp'],
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ],
        'dateAdded' => [
            'label'                   => &$GLOBALS['TL_LANG']['MSC']['dateAdded'],
            'sorting'                 => true,
            'flag'                    => 6,
            'eval'                    => ['rgxp'=>'datim', 'doNotCopy' => true],
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ],
        'title' => [
            'label'                   => &$GLOBALS['TL_LANG']['tl_job_archive']['title'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => true,
            'flag'                    => 1,
            'inputType'               => 'text',
            'eval'                    => ['mandatory' => true, 'tl_class'=>'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ],
        'start' => [
            'label'                   => &$GLOBALS['TL_LANG']['tl_job_archive']['start'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => ['rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'],
            'sql'                     => "varchar(10) NOT NULL default ''"
        ],
        'stop' => [
            'label'                   => &$GLOBALS['TL_LANG']['tl_job_archive']['stop'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => ['rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'],
            'sql'                     => "varchar(10) NOT NULL default ''"
        ]
    ]
];