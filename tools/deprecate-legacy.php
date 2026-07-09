<?php
/**
 * Adds @deprecated annotations to legacy files based on their role.
 */

$baseDir = __DIR__ . '/../legacy';

$schemaFiles = [
    'class.fa_attachments.php',         // broken schema (copied from crm_persons)
    'class.fa_bank_account.php',
    'class.fa_bank_accounts.php',
    'class.fa_bank_trans.php',
    'class.fa_currencies.php',
    'class.fa_cust_branch.php',
    'class.fa_debtor_trans.php',
    'class.fa_debtor_trans_details.php',
    'class.fa_dimension.php',
    'class.fa_dimensions.php',
    'class.fa_document_type.php',       // broken schema (fields are for dimensions)
    'class.fa_gl.php',
    'class.fa_item_codes.php',
    'class.fa_loc_stock.php',
    'class.fa_locations.php',
    'class.fa_payment_terms.php',
    'class.fa_prices.php',
    'class.fa_purch_data.php',
    'class.fa_purch_order_details.php',
    'class.fa_purch_orders.php',
    'class.fa_references.php',
    'class.fa_sales_order_details.php',
    'class.fa_sales_orders.php',
    'class.fa_sales_types.php',
    'class.fa_salesman.php',
    'class.fa_shippers.php',
    'class.fa_stock_category.php',
    'class.fa_stock_master.php',
    'class.fa_stock_moves.php',
    'class.fa_suppliers.php',
    'class.fa_tax_groups.php',
    'class.fa_tax_types.php',
    'class.fa_users.php',
    'class.fa_workcenter.php',
];
$schemaMsg = "Replaced by DTO + Repository pattern in src/FrontAccounting/{DTO,Repository}/";

$infraFiles = [
    'class.fa_db.php' => "Replaced by Ksfraser\\ModulesDAO\\Db\\DbAdapterInterface",
    'class.fa_MODEL.php' => "Replaced by FrontAccounting\\Repository\\BaseRepository",
    'class.fa_table_wrapper.php' => "Replaced by RepositoryTrait + QueryBuilder (read-only queries)",
];
$infraMsg = "Replaced by new Repository/DAO layer";

$modelFiles = [
    'class.fa_crm_persons_model.php',
    'class.fa_crm_contacts_model.php',
];
$modelMsg = $schemaMsg;

// Files with schema + business logic (partial coverage)
$mixedFiles = [
    'class.fa_debtors_master_model.php' => "Schema replaced by DebtorMasterDTO + DebtorMasterRepository; business methods (find_customer_by_email, searchCustomersByName) not yet ported to repository",
    'class.fa_crm_persons.php' => "Schema replaced by CrmPersonDTO + CrmPersonRepository; add_crm_person() business logic not yet ported",
    'class.fa_crm_contacts.php' => "Schema replaced by CrmContactDTO + CrmContactRepository; add_crm_contact() business logic not yet ported",
    'class.fa_customer.php' => "Customer CRUD via FA core functions (add_customer, add_branch, add_crm_person) not yet ported to Service layer",
    'class.fa_cust.php' => "Customer wrapper + CRM cross-reference logic not yet ported",
    'class.fa_customer_payment.php' => "Payment/allocation transaction logic not yet ported — references external 'origin' class",
    'class.fa_bank_transfer.php' => "Bank transfer transaction logic not yet ported — references external 'fa_origin' class",
    'class.fa_order_to_delivery.php' => "Order-to-delivery analysis queries not yet ported; references external 'table_interface' class",
];

$infraOnlyFiles = [
    'class.fa_origin.php' => "Transaction base class (trans_no/ref/comments). Not replaced — future Service layer needed. Extends external 'origin'.",
    'class.fa_image.php' => "Image/file utility. Not DB/schema related. Extends external 'ksf_file_upload'.",
];

foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($baseDir)) as $file) {
    if ($file->getExtension() !== 'php') continue;
    $path = $file->getPathname();
    $name = $file->getFilename();

    $content = file_get_contents($path);

    // Skip if already has @deprecated
    if (preg_match('/@deprecated/', $content)) {
        echo "  SKIP (already deprecated): $name\n";
        continue;
    }

    $depMsg = null;

    if (in_array($name, $schemaFiles)) {
        $depMsg = $schemaMsg;
    } elseif (isset($infraFiles[$name])) {
        $depMsg = $infraFiles[$name];
    } elseif (in_array($name, $modelFiles)) {
        $depMsg = $modelMsg;
    } elseif (isset($mixedFiles[$name])) {
        $depMsg = $mixedFiles[$name];
    } elseif (isset($infraOnlyFiles[$name])) {
        $depMsg = $infraOnlyFiles[$name];
    } else {
        // Generic fallback for any unmatched files (archive/ etc.)
        $depMsg = "Legacy file — functionality covered by new DTO + Repository pattern";
    }

    // Find the opening <?php tag and add docblock after it
    $pattern = '/^<\?php\s*\n(declare\(strict_types=1\);\s*\n)?/';
    $replacement = "<?php\n\n/**\n * @deprecated $depMsg\n */\n";

    $newContent = preg_replace($pattern, $replacement, $content, 1, $count);
    if ($count === 1 && $newContent !== $content) {
        file_put_contents($path, $newContent);
        echo "  DEPRECATED: $name\n";
    } else {
        echo "  FAILED: $name\n";
    }
}
