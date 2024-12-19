<?php

/**
 * Copyright © Magendoo Interactive SRL All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE, 
    'Magendoo_OrderStatusVisualizer', 
    __DIR__
);
