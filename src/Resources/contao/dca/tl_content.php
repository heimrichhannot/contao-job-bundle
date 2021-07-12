<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

if ('job' == Input::get('do')) {
    $GLOBALS['TL_DCA']['tl_content']['config']['ptable'] = 'tl_job';
    $GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = [\HeimrichHannot\JobBundle\DataContainer\ContentContainer::class, 'checkPermission'];
    $GLOBALS['TL_DCA']['tl_content']['list']['operations']['toggle']['button_callback'] = [\HeimrichHannot\JobBundle\DataContainer\ContentContainer::class, 'toggleIcon'];
}
