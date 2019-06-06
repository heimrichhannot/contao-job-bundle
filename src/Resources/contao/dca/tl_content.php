<?php


// Dynamically add the permission check and parent table
if (Input::get('do') == 'job')
{
	$GLOBALS['TL_DCA']['tl_content']['config']['ptable'] = 'tl_job';
	$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = array('huh.job.data_container.content', 'checkPermission');
	$GLOBALS['TL_DCA']['tl_content']['list']['operations']['toggle']['button_callback'] = array('huh.job.data_container.content', 'toggleIcon');
}