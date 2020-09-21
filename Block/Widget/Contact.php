<?php
/**
 * Copyright © Juan Manuel Cinto All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace JMC\CustomContactPage\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class Contact extends Template implements BlockInterface
{

    protected $_template = "widget/contact.phtml";

}
