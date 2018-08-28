<?php

/**
* Backend modules
*/
$GLOBALS['BE_MOD']['content']['job'] = [
    'tables' => ['tl_job_archive', 'tl_job'],
];
$GLOBALS['BE_MOD']['content']['job_archive'] = [
    'tables' => ['tl_job_archive'],
];

/**
* Permissions
*/
$GLOBALS['TL_PERMISSIONS'][] = 'jobs';
$GLOBALS['TL_PERMISSIONS'][] = 'jobp';
