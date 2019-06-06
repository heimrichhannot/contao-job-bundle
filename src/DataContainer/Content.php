<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\JobBundle\DataContainer;

use Contao\Backend;
use Contao\BackendUser;
use Contao\CoreBundle\Exception\AccessDeniedException;
use Contao\Input;
use DataContainer;
use Image;
use StringUtil;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use System;
use Versions;

class Content extends Backend
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var BackendUser
     */
    protected $user;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->user      = BackendUser::getInstance();
        parent::__construct();
    }


    /**
     * Check permissions to edit table tl_content
     */
    public function checkPermission()
    {
        if ($this->user->isAdmin) {
            return;
        }

        // Set the root IDs
        if (empty($this->user->jobs) || !\is_array($this->user->jobs)) {
            $root = [0];
        } else {
            $root = $this->user->jobs;
        }

        // Check the current action
        switch (Input::get('act')) {
            case '': // empty
            case 'paste':
            case 'create':
            case 'select':
                // Check access to the job item
                $this->checkAccessToElement(CURRENT_ID, $root, true);
                break;

            case 'editAll':
            case 'deleteAll':
            case 'overrideAll':
            case 'cutAll':
            case 'copyAll':
                // Check access to the parent element if a content element is moved
                if (Input::get('act') == 'cutAll' || Input::get('act') == 'copyAll') {
                    $this->checkAccessToElement(Input::get('pid'), $root, (Input::get('mode') == 2));
                }

                $objCes = $this->Database->prepare("SELECT id FROM tl_content WHERE ptable='tl_job' AND pid=?")
                    ->execute(CURRENT_ID);

                /** @var SessionInterface $objSession */
                $objSession = $this->container->get('session');

                $sessionData                   = $objSession->all();
                $sessionData['CURRENT']['IDS'] = array_intersect((array)$sessionData['CURRENT']['IDS'], $objCes->fetchEach('id'));
                $objSession->replace($sessionData);
                break;

            case 'cut':
            case 'copy':
                // Check access to the parent element if a content element is moved
                $this->checkAccessToElement(Input::get('pid'), $root, (Input::get('mode') == 2));
            // NO BREAK STATEMENT HERE

            default:
                // Check access to the content element
                $this->checkAccessToElement(Input::get('id'), $root);
                break;
        }
    }

    /**
     * Check access to a particular content element
     *
     * @param integer $id
     * @param array $root
     * @param boolean $blnIsPid
     *
     * @throws AccessDeniedException
     */
    protected function checkAccessToElement($id, $root, $blnIsPid = false)
    {
        if ($blnIsPid) {
            $objArchive = $this->Database->prepare("SELECT a.id, j.id AS jid FROM tl_job j, tl_job_archive a WHERE j.id=? AND j.pid=a.id")
                ->limit(1)
                ->execute($id);
        } else {
            $objArchive = $this->Database->prepare("SELECT a.id, j.id AS jid FROM tl_content c, tl_job j, tl_job_archive a WHERE c.id=? AND c.pid=j.id AND j.pid=a.id")
                ->limit(1)
                ->execute($id);
        }

        // Invalid ID
        if ($objArchive->numRows < 1) {
            throw new AccessDeniedException('Invalid job content element ID ' . $id . '.');
        }

        // The job archive is not mounted
        if (!\in_array($objArchive->id, $root)) {
            throw new AccessDeniedException('Not enough permissions to modify job ID ' . $objArchive->jid . ' in job archive ID ' . $objArchive->id . '.');
        }
    }

    /**
     * Return the "toggle visibility" button
     *
     * @param array $row
     * @param string $href
     * @param string $label
     * @param string $title
     * @param string $icon
     * @param string $attributes
     *
     * @return string
     */
    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        if (\strlen(Input::get('tid'))) {
            $this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
            $this->redirect($this->getReferer());
        }

        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$this->user->hasAccess('tl_content::invisible', 'alexf')) {
            return '';
        }

        $href .= '&amp;id=' . Input::get('id') . '&amp;tid=' . $row['id'] . '&amp;state=' . $row['invisible'];

        if ($row['invisible']) {
            $icon = 'invisible.svg';
        }

        return '<a href="' . $this->addToUrl($href) . '" title="' . StringUtil::specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label, 'data-state="' . ($row['invisible'] ? 0 : 1) . '"') . '</a> ';
    }

    /**
     * Toggle the visibility of an element
     *
     * @param integer $intId
     * @param boolean $blnVisible
     * @param DataContainer $dc
     */
    public function toggleVisibility($intId, $blnVisible, DataContainer $dc = null)
    {
        // Set the ID and action
        Input::setGet('id', $intId);
        Input::setGet('act', 'toggle');

        if ($dc) {
            $dc->id = $intId; // see #8043
        }

        // Trigger the onload_callback
        if (\is_array($GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'])) {
            foreach ($GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'] as $callback) {
                if (\is_array($callback)) {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($dc);
                } elseif (\is_callable($callback)) {
                    $callback($dc);
                }
            }
        }

        // Check the field access
        if (!$this->user->hasAccess('tl_content::invisible', 'alexf')) {
            throw new AccessDeniedException('Not enough permissions to publish/unpublish content element ID ' . $intId . '.');
        }

        // Set the current record
        if ($dc) {
            $objRow = $this->Database->prepare("SELECT * FROM tl_content WHERE id=?")
                ->limit(1)
                ->execute($intId);

            if ($objRow->numRows) {
                $dc->activeRecord = $objRow;
            }
        }

        $objVersions = new Versions('tl_content', $intId);
        $objVersions->initialize();

        // Reverse the logic (elements have invisible=1)
        $blnVisible = !$blnVisible;

        // Trigger the save_callback
        if (\is_array($GLOBALS['TL_DCA']['tl_content']['fields']['invisible']['save_callback'])) {
            foreach ($GLOBALS['TL_DCA']['tl_content']['fields']['invisible']['save_callback'] as $callback) {
                if (\is_array($callback)) {
                    $this->import($callback[0]);
                    $blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, $dc);
                } elseif (\is_callable($callback)) {
                    $blnVisible = $callback($blnVisible, $dc);
                }
            }
        }

        $time = time();

        // Update the database
        $this->Database->prepare("UPDATE tl_content SET tstamp=$time, invisible='" . ($blnVisible ? '1' : '') . "' WHERE id=?")
            ->execute($intId);

        if ($dc) {
            $dc->activeRecord->tstamp    = $time;
            $dc->activeRecord->invisible = ($blnVisible ? '1' : '');
        }

        // Trigger the onsubmit_callback
        if (\is_array($GLOBALS['TL_DCA']['tl_content']['config']['onsubmit_callback'])) {
            foreach ($GLOBALS['TL_DCA']['tl_content']['config']['onsubmit_callback'] as $callback) {
                if (\is_array($callback)) {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($dc);
                } elseif (\is_callable($callback)) {
                    $callback($dc);
                }
            }
        }

        $objVersions->create();
    }
}
