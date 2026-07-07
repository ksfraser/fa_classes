<?php

namespace Ksfraser\FA\Schema;

/**
 * @deprecated since 0.2.0, use \FrontAccounting\Schema\SalesTypesSchema instead.
 * This stub will be removed in a future version.
 */
class SalesTypesSchema extends \FrontAccounting\Schema\SalesTypesSchema
{
    public static function descriptor()
    {
        trigger_error(
            __CLASS__ . ' is deprecated, use FrontAccounting\Schema\SalesTypesSchema instead.',
            E_USER_DEPRECATED
        );
        return parent::descriptor();
    }
}
