<?php

/**
 * Aligent Consulting
 * Copyright (c) Aligent Consulting (https://www.aligent.com.au)
 */

declare(strict_types=1);

namespace Aligent\AsyncEvents\Block\Adminhtml\Trace\Tab;

use Magento\Backend\Block\Template;
use Magento\Ui\Component\Layout\Tabs\TabInterface;

class View extends Template implements TabInterface
{

    public function getTabLabel()
    {
        return __('Overview');
    }

    public function getTabTitle()
    {
        return __('Overview');
    }

    public function getTabClass()
    {
        return '';
    }

    public function getTabUrl()
    {
        return '';
    }

    public function isAjaxLoaded()
    {
        return false;
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }
}
