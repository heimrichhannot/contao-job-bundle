<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

$GLOBALS['TL_DCA']['tl_job'] = [
    'config' => [
        'dataContainer' => 'Table',
        'ptable' => 'tl_job_archive',
        'ctable' => ['tl_content'],
        'switchToEdit' => true,
        'enableVersioning' => true,
        'onload_callback' => [
            [\HeimrichHannot\JobBundle\DataContainer\JobContainer::class, 'checkPermission'],
            ['huh.utils.dca', 'generateSitemap'],
        ],
        'onsubmit_callback' => [
            [\HeimrichHannot\JobBundle\DataContainer\JobContainer::class, 'adjustTime'],
            ['huh.utils.dca', 'setDateAdded'],
        ],
        'oncopy_callback' => [
            ['huh.utils.dca', 'setDateAddedOnCopy'],
        ],
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'pid,start,stop,published' => 'index',
            ],
        ],
    ],
    'list' => [
        'label' => [
            'fields' => ['title'],
            'format' => '%s',
        ],
        'sorting' => [
            'mode' => 4,
            'fields' => ['date DESC'],
            'headerFields' => ['title'],
            'panelLayout' => 'filter;sort,search,limit',
            'child_record_callback' => [\HeimrichHannot\JobBundle\DataContainer\JobContainer::class, 'listChildren'],
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
                'label' => &$GLOBALS['TL_LANG']['tl_job']['edit'],
                'href' => 'table=tl_content',
                'icon' => 'edit.svg',
            ],
            'editheader' => [
                'label' => &$GLOBALS['TL_LANG']['tl_job']['editMeta'],
                'href' => 'act=edit',
                'icon' => 'header.svg',
            ],
            'copy' => [
                'label' => &$GLOBALS['TL_LANG']['tl_job']['copy'],
                'href' => 'act=copy',
                'icon' => 'copy.gif',
            ],
            'delete' => [
                'label' => &$GLOBALS['TL_LANG']['tl_job']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? 'confirm delete') . '\'))return false;Backend.getScrollOffset()"',
            ],
            'toggle' => [
                'label' => &$GLOBALS['TL_LANG']['tl_job']['toggle'],
                'icon' => 'visible.gif',
                'attributes' => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback' => [\HeimrichHannot\JobBundle\DataContainer\JobContainer::class, 'toggleIcon'],
            ],
            'show' => [
                'label' => &$GLOBALS['TL_LANG']['tl_job']['show'],
                'href' => 'act=show',
                'icon' => 'show.gif',
            ],
        ],
    ],
    'palettes' => [
        '__selector__' => ['addImage', 'published'],
        'default' => '
            {title_legend},title;
            {date_legend},date,time;
            {teaser_legend},subheadline,teaser;
            {detail_legend},description,location,region,addImage,workingTime,levelsOfEducation,targets,yearsOfProfessionalExperience;
            {enclosure_legend},files;
            {employer_legend},employer,overrideMemberContacts;{publish_legend},published;',
    ],
    'subpalettes' => [
        'addImage' => 'singleSRC',
        'published' => 'start,stop',
    ],
    'fields' => [
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'pid' => [
            'foreignKey' => 'tl_job_archive.title',
            'sql' => "int(10) unsigned NOT NULL default '0'",
            'relation' => ['type' => 'belongsTo', 'load' => 'eager'],
        ],
        'tstamp' => [
            'label' => &$GLOBALS['TL_LANG']['tl_job']['tstamp'],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'dateAdded' => [
            'label' => &$GLOBALS['TL_LANG']['MSC']['dateAdded'],
            'sorting' => true,
            'flag' => 6,
            'eval' => ['rgxp' => 'datim', 'doNotCopy' => true],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        // job
        'title' => [
            'label' => &$GLOBALS['TL_LANG']['tl_job']['title'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'date' => [
            'label' => &$GLOBALS['TL_LANG']['tl_job']['date'],
            'default' => time(),
            'exclude' => true,
            'filter' => true,
            'sorting' => true,
            'flag' => 8,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'date', 'mandatory' => true, 'doNotCopy' => true, 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'load_callback' => [
                [\HeimrichHannot\JobBundle\DataContainer\JobContainer::class, 'loadDate'],
            ],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'time' => [
            'label' => &$GLOBALS['TL_LANG']['tl_job']['time'],
            'default' => time(),
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'time', 'mandatory' => true, 'doNotCopy' => true, 'tl_class' => 'w50'],
            'load_callback' => [
                [\HeimrichHannot\JobBundle\DataContainer\JobContainer::class, 'loadTime'],
            ],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'subheadline' => [
            'label' => &$GLOBALS['TL_LANG']['tl_job']['subheadline'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => ['maxlength' => 255, 'tl_class' => 'long'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'teaser' => [
            'label' => &$GLOBALS['TL_LANG']['tl_job']['teaser'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'textarea',
            'eval' => ['rte' => 'tinyMCE', 'tl_class' => 'clr'],
            'sql' => 'text NULL',
        ],
        'description' => [
            'label' => &$GLOBALS['TL_LANG']['tl_job']['description'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'textarea',
            'eval' => ['tl_class' => 'long clr', 'rte' => 'tinyMCE'],
            'sql' => 'text NULL',
        ],
        'location' => [
            'label' => &$GLOBALS['TL_LANG']['tl_job']['location'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => ['maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'region' => [
            'label' => &$GLOBALS['TL_LANG']['tl_job']['region'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => ['maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'addImage' => [
            'label' => &$GLOBALS['TL_LANG']['tl_job']['addImage'],
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w50', 'submitOnChange' => true],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'singleSRC' => [
            'label' => &$GLOBALS['TL_LANG']['tl_job']['singleSRC'],
            'exclude' => true,
            'inputType' => 'fileTree',
            'eval' => ['fieldType' => 'radio', 'filesOnly' => true, 'extensions' => Config::get('validImageTypes'), 'mandatory' => true],
            'sql' => 'binary(16) NULL',
        ],
        'files' => [
            'label' => &$GLOBALS['TL_LANG']['tl_job']['files'],
            'exclude' => true,
            'inputType' => 'fileTree',
            'eval' => [
                'tl_class' => 'clr',
                'extensions' => 'pdf,doc,docx,odt,xls,xlsx,ppt,pptx,png,jpg,jpeg,gif',
                'filesOnly' => true,
                'maxFiles' => 10,
                'fieldType' => 'radio',
                'multiple' => true,
            ],
            'uploadPathCallback' => [[\HeimrichHannot\JobBundle\DataContainer\JobContainer::class, 'getUploadPath']],
            'sql' => 'blob NULL',
        ],
        'workingTime' => [
            'label' => &$GLOBALS['TL_LANG']['tl_job']['workingTime'],
            'exclude' => true,
            'filter' => true,
            'inputType' => 'select',
            'options' => \HeimrichHannot\JobBundle\Model\JobModel::WORKING_TIMES,
            'reference' => &$GLOBALS['TL_LANG']['tl_job']['reference'],
            'eval' => ['tl_class' => 'w50', 'includeBlankOption' => true, 'multiple' => true, 'chosen' => true],
            'sql' => 'blob NULL',
        ],
        'levelsOfEducation' => [
            'label' => &$GLOBALS['TL_LANG']['tl_job']['levelsOfEducation'],
            'exclude' => true,
            'filter' => true,
            'inputType' => 'select',
            'options' => \HeimrichHannot\JobBundle\Model\JobModel::LEVELS_OF_EDUCATION,
            'reference' => &$GLOBALS['TL_LANG']['tl_job']['reference'],
            'eval' => ['tl_class' => 'w50', 'includeBlankOption' => true, 'multiple' => true, 'chosen' => true],
            'sql' => 'blob NULL',
        ],
        'targets' => [
            'label' => &$GLOBALS['TL_LANG']['tl_job']['targets'],
            'exclude' => true,
            'filter' => true,
            'inputType' => 'select',
            'options' => \HeimrichHannot\JobBundle\Model\JobModel::TARGETS,
            'reference' => &$GLOBALS['TL_LANG']['tl_job']['reference'],
            'eval' => ['tl_class' => 'w50', 'includeBlankOption' => true, 'multiple' => true, 'chosen' => true],
            'sql' => 'blob NULL',
        ],
        'yearsOfProfessionalExperience' => [
            'label' => &$GLOBALS['TL_LANG']['tl_job']['yearsOfProfessionalExperience'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'digit', 'maxlength' => 10, 'tl_class' => 'w50'],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        // employer
        'employer' => [
            'label' => &$GLOBALS['TL_LANG']['tl_job']['employer'],
            'exclude' => true,
            'filter' => true,
            'inputType' => 'select',
            'options_callback' => function (\Contao\DataContainer $dc) {
                return System::getContainer()->get('huh.utils.choice.model_instance')->getCachedChoices(
                    [
                        'dataContainer' => 'tl_company',
                        'labelPattern' => '%title% (ID: %id%)',
                    ]
                );
            },
            'eval' => ['tl_class' => 'w50', 'includeBlankOption' => true, 'submitOnChange' => true, 'chosen' => true],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        // published
        'published' => [
            'label' => &$GLOBALS['TL_LANG']['tl_job']['published'],
            'exclude' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'eval' => ['doNotCopy' => true, 'submitOnChange' => true],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'start' => [
            'label' => &$GLOBALS['TL_LANG']['tl_job']['start'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql' => "varchar(10) NOT NULL default ''",
        ],
        'stop' => [
            'label' => &$GLOBALS['TL_LANG']['tl_job']['stop'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql' => "varchar(10) NOT NULL default ''",
        ],
    ],
];

$dca = &$GLOBALS['TL_DCA']['tl_job'];

System::getContainer()->get('huh.utils.dca')->addAliasToDca(
    'tl_job',
    function ($value, \Contao\DataContainer $dc) {
        return System::getContainer()->get('huh.utils.dca')->generateAlias($value, $dc->id, 'tl_job', $dc->activeRecord->title);
    },
    'title'
);

if (System::getContainer()->get('huh.utils.container')->isFrontend()
    && class_exists(
        '\HeimrichHannot\MultiFileUploadBundle\HeimrichHannotContaoMultiFileUploadBundle'
    )) {
    $dca['fields']['files']['inputType'] = 'multifileupload';
}

System::getContainer()->get('huh.utils.dca')->addOverridableFields(
    ['memberContacts'],
    'tl_job_archive',
    'tl_job',
    ['checkboxDcaEvalOverride' => ['tl_class' => 'clr']]
);
