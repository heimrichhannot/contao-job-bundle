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
use Contao\Input;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class JobArchiveContainer
{
    protected $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function checkPermission()
    {
        $user = BackendUser::getInstance();
        $database = Database::getInstance();

        if ($user->isAdmin) {
            return;
        }

        // Set root IDs
        if (!is_array($user->jobs) || empty($user->jobs)) {
            $root = [0];
        } else {
            $root = $user->jobs;
        }

        $GLOBALS['TL_DCA']['tl_job_archive']['list']['sorting']['root'] = $root;

        // Check permissions to add archives
        if (!$user->hasAccess('create', 'jobp')) {
            $GLOBALS['TL_DCA']['tl_job_archive']['config']['closed'] = true;
        }

        /** @var \Symfony\Component\HttpFoundation\Session\SessionInterface $objSession */
        $objSession = $this->session->get('session');

        // Check current action
        switch (Input::get('act')) {
            case 'create':
            case 'select':
                // Allow
                break;

            case 'edit':
                // Dynamically add the record to the user profile
                if (!in_array(Input::get('id'), $root)) {
                    /** @var \Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface $sessionBag */
                    $sessionBag = $objSession->getBag('contao_backend');

                    $arrNew = $sessionBag->get('new_records');

                    if (is_array($arrNew['tl_job_archive']) && in_array(Input::get('id'), $arrNew['tl_job_archive'])) {
                        // Add the permissions on group level
                        if ('custom' != $user->inherit) {
                            $objGroup = $database->execute('SELECT id, jobs, jobp FROM tl_user_group WHERE id IN('.implode(',', array_map('intval', $user->groups)).')');

                            while ($objGroup->next()) {
                                $arrModulep = \StringUtil::deserialize($objGroup->jobp);

                                if (is_array($arrModulep) && in_array('create', $arrModulep)) {
                                    $arrModules = \StringUtil::deserialize($objGroup->jobs, true);
                                    $arrModules[] = Input::get('id');

                                    $database->prepare('UPDATE tl_user_group SET jobs=? WHERE id=?')->execute(serialize($arrModules), $objGroup->id);
                                }
                            }
                        }

                        // Add the permissions on user level
                        if ('group' != $user->inherit) {
                            $user = $database->prepare('SELECT jobs, jobp FROM tl_user WHERE id=?')
                                ->limit(1)
                                ->execute($user->id);

                            $arrModulep = \StringUtil::deserialize($user->jobp);

                            if (is_array($arrModulep) && in_array('create', $arrModulep)) {
                                $arrModules = \StringUtil::deserialize($user->jobs, true);
                                $arrModules[] = Input::get('id');

                                $database->prepare('UPDATE tl_user SET jobs=? WHERE id=?')
                                    ->execute(serialize($arrModules), $user->id);
                            }
                        }

                        // Add the new element to the user object
                        $root[] = Input::get('id');
                        $user->jobs = $root;
                    }
                }
            // no break;

            case 'copy':
            case 'delete':
            case 'show':
                if (!in_array(Input::get('id'), $root) || ('delete' == Input::get('act') && !$user->hasAccess('delete', 'jobp'))) {
                    throw new AccessDeniedException('Not enough permissions to '.Input::get('act').' job_archive ID '.Input::get('id').'.');
                }
                break;

            case 'editAll':
            case 'deleteAll':
            case 'overrideAll':
                $session = $objSession->all();
                if ('deleteAll' == Input::get('act') && !$user->hasAccess('delete', 'jobp')) {
                    $session['CURRENT']['IDS'] = [];
                } else {
                    $session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $root);
                }
                $objSession->replace($session);
                break;

            default:
                if (strlen(Input::get('act'))) {
                    throw new AccessDeniedException('Not enough permissions to '.Input::get('act').' job_archives.');
                }
                break;
        }
    }

    public function editHeader($row, $href, $label, $title, $icon, $attributes)
    {
        return BackendUser::getInstance()->canEditFieldsOf('tl_job_archive') ? '<a href="'.Controller::addToUrl($href.'&amp;id='.$row['id']).'&rt='.\RequestToken::get().'" title="'.\StringUtil::specialchars($title).'"'.$attributes.'>'.\Image::getHtml($icon, $label).'</a> ' : \Image::getHtml(preg_replace('/\.svg$/i', '_.svg', $icon)).' ';
    }

    public function copyArchive($row, $href, $label, $title, $icon, $attributes)
    {
        return BackendUser::getInstance()->hasAccess('create', 'jobp') ? '<a href="'.Controller::addToUrl($href.'&amp;id='.$row['id']).'&rt='.\RequestToken::get().'" title="'.\StringUtil::specialchars($title).'"'.$attributes.'>'.\Image::getHtml($icon, $label).'</a> ' : \Image::getHtml(preg_replace('/\.svg$/i', '_.svg', $icon)).' ';
    }

    public function deleteArchive($row, $href, $label, $title, $icon, $attributes)
    {
        return BackendUser::getInstance()->hasAccess('delete', 'jobp') ? '<a href="'.Controller::addToUrl($href.'&amp;id='.$row['id']).'&rt='.\RequestToken::get().'" title="'.\StringUtil::specialchars($title).'"'.$attributes.'>'.\Image::getHtml($icon, $label).'</a> ' : \Image::getHtml(preg_replace('/\.svg$/i', '_.svg', $icon)).' ';
    }
}
