<?php

namespace Ksfraser\FA\Schema;

/**
 * Single-source-of-truth schema+UI descriptor for the `sales_types` entity.
 *
 * This is intentionally:
 * - static + memoized (no per-instance construction overhead)
 * - environment-agnostic (does not require TB_PREF to exist)
 *
 * Consumers (DAO/UI) can apply an FA prefix at runtime.
 */
class SalesTypesSchema
{
    /** @var array|null */
    private static $descriptor;

    /**
     * Return a memoized descriptor array.
     *
     * Shape (stable contract):
     * - entity: string
     * - table: string (unprefixed)
     * - primaryKey: string
     * - fields: array<string,array>
     * - ui: array (optional)
     * - relationships: array (optional)
     *
     * @return array
     */
    public static function descriptor()
    {
        if (self::$descriptor !== null) {
            return self::$descriptor;
        }

        self::$descriptor = array(
            'entity' => 'sales_types',
            'table' => 'sales_types',
            'primaryKey' => 'id',

            // Field contract is intentionally close to legacy fields_array.
            // Keep names stable so we can map to CRUD and to table creation.
            'fields' => array(
                'id' => array(
                    'label' => 'Sales Type',
                    'type' => 'int(11)',
                    'null' => 'NOT NULL',
                    'readwrite' => 'read',
                    'auto_increment' => true,
                ),
                'sales_type' => array(
                    'label' => 'Sales Type',
                    'type' => 'char(50)',
                    'null' => 'NOT NULL',
                    'readwrite' => 'readwrite',
                    'default' => '0',
                ),
                'tax_included' => array(
                    'label' => 'Tax Included',
                    'type' => 'int(1)',
                    'null' => 'NOT NULL',
                    'readwrite' => 'readwrite',
                    'default' => '0',
                ),
                'factor' => array(
                    'label' => 'Factor',
                    'type' => 'double',
                    'null' => 'NOT NULL',
                    'readwrite' => 'readwrite',
                    'default' => '1',
                ),
                'inactive' => array(
                    'label' => 'Inactive',
                    'type' => 'bool',
                    'null' => 'NOT NULL',
                    'readwrite' => 'readwrite',
                    'default' => '0',
                ),
            ),

            // Optional UI hints. Smart UI can run without these, but they reduce manual tweaks.
            'ui' => array(
                'title' => 'Sales Types',
                'pageSize' => 10,
                'listColumns' => array('id', 'sales_type', 'tax_included', 'factor', 'inactive'),
                'formFields' => array('sales_type', 'tax_included', 'factor', 'inactive'),
                // Tabs here are screen-level and map 1:1 with the generic_fa_interface tabs shape.
                // If you want *zero* UI duplication, you can omit this and let the screen use defaults.
                'tabs' => array(
                    array('title' => 'List', 'action' => 'list', 'form' => 'list_form', 'hidden' => false),
                    array('title' => 'Add', 'action' => 'add', 'form' => 'add_form', 'hidden' => false),
                ),
            ),

            'relationships' => array(
                // Example shape for future FK dropdowns:
                // 'some_fk_field' => array('type' => 'fk', 'target' => 'suppliers', 'valueColumn' => 'supplier_id', 'labelColumn' => 'supp_name')
            ),
        );

        return self::$descriptor;
    }
}
