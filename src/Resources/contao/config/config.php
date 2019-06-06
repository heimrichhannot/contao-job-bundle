<?php

/**
 * Backend modules
 */
$GLOBALS['BE_MOD']['content']['job'] = [
    'tables' => ['tl_job_archive', 'tl_job', 'tl_content'],
];

/**
 * Permissions
 */
$GLOBALS['TL_PERMISSIONS'][] = 'jobs';
$GLOBALS['TL_PERMISSIONS'][] = 'jobp';

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_job']         = 'HeimrichHannot\JobBundle\Model\JobModel';
$GLOBALS['TL_MODELS']['tl_job_archive'] = 'HeimrichHannot\JobBundle\Model\JobArchiveModel';