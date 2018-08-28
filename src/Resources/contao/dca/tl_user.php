<?php

$dca = &$GLOBALS['TL_DCA']['tl_user'];

/**
 * Palettes
 */
$dca['palettes']['extend'] = str_replace('fop;', 'fop;{job_legend},jobs,jobp;', $dca['palettes']['extend']);
$dca['palettes']['custom'] = str_replace('fop;', 'fop;{job_legend},jobs,jobp;', $dca['palettes']['custom']);

/**
 * Fields
 */
$dca['fields']['jobs'] = [
    'label'      => &$GLOBALS['TL_LANG']['tl_user']['jobs'],
    'exclude'    => true,
    'inputType'  => 'checkbox',
    'foreignKey' => 'tl_job_archive.title',
    'eval'       => ['multiple' => true],
    'sql'        => "blob NULL"
];

$dca['fields']['jobp'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_user']['jobp'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'options'   => ['create', 'delete'],
    'reference' => &$GLOBALS['TL_LANG']['MSC'],
    'eval'      => ['multiple' => true],
    'sql'       => "blob NULL"
];
