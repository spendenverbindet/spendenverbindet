<?php

namespace Backend\AdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BackendAdminBundle extends Bundle
{
    public function getParent()
    {
        return 'HtlSpendenportalBundle';
    }
}
