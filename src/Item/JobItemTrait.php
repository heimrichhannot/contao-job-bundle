<?php

namespace HeimrichHannot\JobBundle\Item;

use Contao\StringUtil;
use Contao\System;

trait JobItemTrait
{
    /**
     * Retrieve the member contacts respecting archive inheritance.
     *
     * @return array|null
     */
    public function getMemberContacts()
    {
        $instances = [];

        if (null !== ($archive  = System::getContainer()->get('huh.utils.model')->findModelInstanceByPk('tl_job_archive', $this->pid)))
        {
            $instances[] = $archive;
        }

        $instances[] = $this;

        $contacts = System::getContainer()->get('huh.utils.dca')->getOverridableProperty(
            'memberContacts', $instances
        );

        if (!$contacts)
        {
            return null;
        }

        $ids = StringUtil::deserialize($contacts, true);

        if (empty($ids))
        {
            return null;
        }

        if (null === ($members = System::getContainer()->get('huh.utils.model')->findMultipleModelInstancesByIds('tl_member', $ids)))
        {
            return null;
        }

        return $members->fetchAll();
    }
}