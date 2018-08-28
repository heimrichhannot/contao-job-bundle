<?php

$GLOBALS['TL_DCA']['tl_job'] = [
    'config'   => [
        'dataContainer'     => 'Table',
        'ptable'            => 'tl_job_archive',
        'enableVersioning'  => true,
        'onload_callback' => [
            ['huh.job.listener.job_callback', 'checkPermission'],
        ],
        'onsubmit_callback' => [
            ['huh.utils.dca', 'setDateAdded'],
        ],
        'oncopy_callback'   => [
            ['huh.utils.dca', 'setDateAddedOnCopy'],
        ],
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'pid,start,stop,published' => 'index'            ]
        ]
    ],
    'list'     => [
        'label' => [
            'fields' => ['id'],
            'format' => '%s'
        ],
        'sorting'           => [
            'mode'                  => 1,
            'fields'                => ['title'],
            'headerFields'          => ['title'],
            'panelLayout'           => 'filter;sort,search,limit',
            'child_record_callback' => ['huh.job.listener.job_callback', 'listChildren']
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
            'edit'   => [
                'label' => &$GLOBALS['TL_LANG']['tl_job']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif'
            ],
            'copy'   => [
                'label' => &$GLOBALS['TL_LANG']['tl_job']['copy'],
                'href'  => 'act=copy',
                'icon'  => 'copy.gif'
            ],
            'delete' => [
                'label'      => &$GLOBALS['TL_LANG']['tl_job']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ],
            'toggle' => [
                'label'           => &$GLOBALS['TL_LANG']['tl_job']['toggle'],
                'icon'            => 'visible.gif',
                'attributes'      => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback' => ['huh.job.listener.job_callback', 'toggleIcon']
            ],
            'show'   => [
                'label' => &$GLOBALS['TL_LANG']['tl_job']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif'
            ],
        ]
    ],
    'palettes' => [
        '__selector__' => ['published'],
        'default' => '{general_legend},title;{publish_legend},published;'
    ],
    'subpalettes' => [
        'published'    => 'start,stop'
    ],
    'fields'   => [
        'id' => [
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ],
        'pid' => [
            'foreignKey'              => 'tl_job_archive.title',
            'sql'                     => "int(10) unsigned NOT NULL default '0'",
            'relation'                => ['type'=>'belongsTo', 'load'=>'eager']
        ],
        'tstamp' => [
            'label'                   => &$GLOBALS['TL_LANG']['tl_job']['tstamp'],
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
            'label'                   => &$GLOBALS['TL_LANG']['tl_job']['title'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => true,
            'flag'                    => 1,
            'inputType'               => 'text',
            'eval'                    => ['mandatory' => true, 'tl_class'=>'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ],
        'published' => [
            'label'                   => &$GLOBALS['TL_LANG']['tl_job']['published'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => ['doNotCopy'=>true, 'submitOnChange' => true],
            'sql'                     => "char(1) NOT NULL default ''"
        ],
        'start' => [
            'label'                   => &$GLOBALS['TL_LANG']['tl_job']['start'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => ['rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'],
            'sql'                     => "varchar(10) NOT NULL default ''"
        ],
        'stop' => [
            'label'                   => &$GLOBALS['TL_LANG']['tl_job']['stop'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => ['rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'],
            'sql'                     => "varchar(10) NOT NULL default ''"
        ]
    ]
];