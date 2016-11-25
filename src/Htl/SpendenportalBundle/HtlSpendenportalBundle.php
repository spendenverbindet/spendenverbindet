<?php

namespace Htl\SpendenportalBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class HtlSpendenportalBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
