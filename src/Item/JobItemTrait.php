<?php

namespace HeimrichHannot\JobBundle\Item;

use Contao\ContentModel;
use Contao\Controller;
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

        if (null !== ($archive = System::getContainer()->get('huh.utils.model')->findModelInstanceByPk('tl_job_archive', $this->pid))) {
            $instances[] = $archive;
        }

        $instances[] = $this;

        $contacts = System::getContainer()->get('huh.utils.dca')->getOverridableProperty(
            'memberContacts', $instances
        );

        if (!$contacts) {
            return null;
        }

        $ids = StringUtil::deserialize($contacts, true);

        if (empty($ids)) {
            return null;
        }

        if (null === ($members = System::getContainer()->get('huh.utils.model')->findMultipleModelInstancesByIds('tl_member', $ids))) {
            return null;
        }

        return $members->fetchAll();
    }

    /**
     * Compile the job content (tl_content).
     *
     * @return string
     */
    public function getContent(): string
    {
        $strText = '';

        /**
         * @var ContentModel
         */
        $adapter = $this->getManager()->getFramework()->getAdapter(ContentModel::class);

        if (null !== ($elements = $adapter->findPublishedByPidAndTable($this->id, $this->getDataContainer()))) {
            foreach ($elements as $element) {
                try {
                    $strText .= Controller::getContentElement($element->id);
                } catch (\ErrorException $e) {
                }
            }
        }

        return $strText;
    }

    /**
     * Check if the job has content (tl_content).
     *
     * @return bool
     */
    public function hasContent(): bool
    {
        /** @var ContentModel $adapter */
        $adapter = $this->getManager()->getFramework()->getAdapter(ContentModel::class);

        return $adapter->countPublishedByPidAndTable($this->id, $this->getDataContainer());
    }

    /**
     * Check if the job has teaser text.
     *
     * @return bool
     */
    public function hasTeaser(): bool
    {
        return '' !== $this->teaser;
    }

    /**
     * Compile the job text.
     *
     * @return string
     */
    public function getTeaser(): string
    {
        return StringUtil::encodeEmail(StringUtil::toHtml5($this->teaser));
    }
}
