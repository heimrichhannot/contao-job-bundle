<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\JobBundle;

use HeimrichHannot\JobBundle\DependencyInjection\ContaoHeimrichHannotJobExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class HeimrichHannotContaoJobBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new ContaoHeimrichHannotJobExtension();
    }
}
