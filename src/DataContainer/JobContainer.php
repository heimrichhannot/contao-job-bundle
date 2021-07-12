<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\JobBundle\DataContainer;

use Contao\BackendUser;
use Contao\Controller;
use Contao\CoreBundle\Exception\AccessDeniedException;
use Contao\Database;
use Contao\DataContainer;
use Contao\Input;
use Contao\System;
use Contao\Versions;
use HeimrichHannot\UtilsBundle\Model\ModelUtil;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class JobContainer
{
    protected $session;
    protected $modelUtil;

    public function __construct(SessionInterface $session, ModelUtil $modelUtil)
    {
        $this->session   = $session;
        $this->modelUtil = $modelUtil;
    }

    /**
     * Set the timestamp to 00:00:00
     *
     * @param integer $value
     *
     * @return integer
     */
    public function loadDate($value)
    {
        return strtotime(date('Y-m-d', $value ?: time()) . ' 00:00:00');
    }

    /**
     * Set the timestamp to 1970-01-01
     *
     * @param integer $value
     *
     * @return integer
     */
    public function loadTime($value)
    {
        return strtotime('1970-01-01 ' . date('H:i:s', $value ?: time()));
    }

    /**
     * Adjust start end end time of the item
     *
     * @param DataContainer $dc
     */
    public function adjustTime(DataContainer $dc)
    {
        // Return if there is no active record (override all)
        if (!$dc->activeRecord) {
            return;
        }

        if (null !== ($model = $this->modelUtil->findModelInstanceByPk('tl_job', $dc->id))) {
            $model->date = strtotime(date('Y-m-d', $dc->activeRecord->date) . ' ' . date('H:i:s', $dc->activeRecord->time));
            $model->time = $model->date;
            $model->save();
        }
    }

    public function getUploadPath($target, $file, DataContainer $dc)
    {
        return 'files/jobs';
    }

    public function listChildren($arrRow)
    {
        return '<div class="tl_content_left">' . ($arrRow['title'] ?: $arrRow['id']) . ' <span style="color:#b3b3b3; padding-left:3px">[' . \Date::parse(\Contao\Config::get('datimFormat'), trim($arrRow['date'])) . ']</span></div>';
    }

    public function checkPermission()
    {
        $user     = BackendUser::getInstance();
        $database = Database::getInstance();

        if ($user->isAdmin) {
            return;
        }

        // Set the root IDs
        if (!is_array($user->jobs) || empty($user->jobs)) {
            $root = [0];
        } else {
            $root = $user->jobs;
        }

        $id = strlen(Input::get('id')) ? Input::get('id') : CURRENT_ID;

        // Check current action
        switch (Input::get('act')) {
            case 'paste':
                // Allow
                break;

            case 'create':
                if (!strlen(Input::get('pid')) || !in_array(Input::get('pid'), $root)) {
                    throw new AccessDeniedException('Not enough permissions to create job items in job archive ID ' . Input::get('pid') . '.');
                }
                break;

            case 'cut':
            case 'copy':
            case 'edit':
            case 'show':
            case 'delete':
            case 'toggle':
            case 'feature':
                $item = $this->modelUtil->findModelInstanceByPk('tl_job', $id);

                if (null === $item) {
                    throw new AccessDeniedException('Invalid job item ID ' . $id . '.');
                }

                if (!in_array($item->pid, $root)) {
                    throw new AccessDeniedException('Not enough permissions to ' . Input::get('act') . ' job item ID ' . $id . ' of job archive ID ' . $job->pid . '.');
                }
                break;

            case 'select':
            case 'editAll':
            case 'deleteAll':
            case 'overrideAll':
            case 'cutAll':
            case 'copyAll':

                if (!\in_array($id, $root)) {
                    throw new AccessDeniedException('Not enough permissions to access tl_job archive ID ' . $id . '.');
                }

                $items = $this->modelUtil->findModelInstancesBy('tl_job', ['pid=?'], [$id]);


                if (null === $items) {
                    break;
                }

                $sessionData                   = $this->session->all();
                $sessionData['CURRENT']['IDS'] = array_intersect((array)$sessionData['CURRENT']['IDS'], $items->fetchEach('id'));
                $this->session->replace($sessionData);
                break;

            default:
                if (strlen(Input::get('act'))) {
                    throw new AccessDeniedException('Invalid command "' . Input::get('act') . '".');
                } elseif (!in_array($id, $root)) {
                    throw new AccessDeniedException('Not enough permissions to access job archive ID ' . $id . '.');
                }
                break;
        }
    }

    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        $user = \Contao\BackendUser::getInstance();

        if (strlen(\Contao\Input::get('tid'))) {
            $this->toggleVisibility(\Contao\Input::get('tid'), ('1' === \Contao\Input::get('state')), (@func_get_arg(12) ?: null));
            Controller::redirect(System::getReferer());
        }

        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$user->hasAccess('tl_job::published', 'alexf')) {
            return '';
        }

        $href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

        if (!$row['published']) {
            $icon = 'invisible.svg';
        }

        return '<a href="'.Controller::addToUrl($href).'" title="'.\StringUtil::specialchars($title).'"'.$attributes.'>'.\Image::getHtml($icon, $label).'</a> ';
    }

    public function toggleVisibility($intId, $blnVisible)
    {
        $objUser = \BackendUser::getInstance();
        $objDatabase = \Database::getInstance();

        // Check permissions to publish
        if (!$objUser->isAdmin && !$objUser->hasAccess('tl_job::published', 'alexf')) {
            Controller::log('Not enough permissions to publish/unpublish item ID "'.$intId.'"', 'tl_job toggleVisibility', TL_ERROR);
            Controller::redirect('contao/main.php?act=error');
        }

        $objVersions = new Versions('tl_job', $intId);
        $objVersions->initialize();

        // Trigger the save_callback
        if (is_array($GLOBALS['TL_DCA']['tl_job']['fields']['published']['save_callback'])) {
            foreach ($GLOBALS['TL_DCA']['tl_job']['fields']['published']['save_callback'] as $callback) {
                $blnVisible = System::importStatic($callback[0])->{$callback[1]}($blnVisible, $this);
            }
        }

        // Update the database
        $objDatabase->prepare('UPDATE tl_job SET tstamp='.time().", published='".($blnVisible ? 1 : '')."' WHERE id=?")->execute($intId);

        $objVersions->create();
        Controller::log('A new version of record "tl_job.id='.$intId.'" has been created',
            'tl_job toggleVisibility()', TL_GENERAL);
    }
}
