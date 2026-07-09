<?php
/**
 * Fix remaining issues in repos after BaseRepository refactor:
 *  - Duplicate $tableName lines
 *  - Leftover private $db / $prefix / $delta properties (with doc blocks)
 *  - Leftover constructors (standard and custom)
 */
$baseDir = __DIR__ . '/../src/FrontAccounting/Repository';
$problematic = [
    'AllocationRepository.php',
    'BankAccountsRepository.php',
    'DebtorTransactionDetailRepository.php',
    'DebtorTransactionRepository.php',
    'GrnItemsRepository.php',
    'PurchOrderDetailsRepository.php',
    'SalesOrderDetailsRepository.php',
    'SupplierInvoiceItemRepository.php',
    'SupplierTransactionRepository.php',
];

foreach ($problematic as $filename) {
    $path = "$baseDir/$filename";
    if (!file_exists($path)) { echo "  NOT FOUND: $filename\n"; continue; }

    $content = file_get_contents($path);
    $orig = $content;

    // 1. Remove duplicate $tableName lines (keep first)
    $content = preg_replace(
        '/^(\s+protected string \$tableName = \'[a-z_]+\';\s*\n)\s+protected string \$tableName = \'[a-z_]+\';\s*\n/m',
        '$1',
        $content
    );

    // 2. Remove private $db; with preceding doc comment block
    $content = preg_replace(
        '/\n\s{4}\/\*\*\s+\@var DbAdapterInterface\s+\*\/\s*\n\s{4}private\s+\$db\s*;/',
        '',
        $content
    );
    // Also handle inline case
    $content = preg_replace(
        '/\n\s{4}\/\*\*\s+\@var DbAdapterInterface \*\/\s*\n\s{4}private\s+\$db\s*;/',
        '',
        $content
    );

    // 3. Remove private $prefix; with preceding doc comment block
    $content = preg_replace(
        '/\n\s{4}\/\*\*\s+\@var string\s+\*\/\s*\n\s{4}private\s+\$prefix\s*;/',
        '',
        $content
    );
    $content = preg_replace(
        '/\n\s{4}\/\*\*\s+\@var string \*\/\s*\n\s{4}private\s+\$prefix\s*;/',
        '',
        $content
    );

    // 4. Remove private $delta; with preceding doc comment block
    $content = preg_replace(
        '/\n\s{4}\/\*\*\s+\@var float\s+\*\/\s*\n\s{4}private\s+\$delta\s*;/',
        '',
        $content
    );

    // 5. Remove constructor (standard or custom with $delta)
    $content = preg_replace(
        '/\n\s{4}public function __construct\(DbAdapterInterface \$db(?:, float \$delta = 0\.005)?\)\s*\n\s{4}\{\s*\n(?:\s{8}\$this->\w+\s*=\s*[^;]+\s*;\s*\n){2,3}\s{4}\}/',
        '',
        $content
    );

    // 6. Clean up blank lines
    $content = preg_replace("/\n{3,}/", "\n\n", $content);

    if ($content !== $orig) {
        file_put_contents($path, $content);
        echo "  FIXED: $filename\n";
    } else {
        echo "  NO CHANGE: $filename\n";
    }
}
