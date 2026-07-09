<?php
/**
 * Refactors all repositories to extend BaseRepository.
 * 
 * For each repo:
 *  - extends BaseRepository instead of standalone
 *  - Removes: private $db, private $prefix, constructor, getTableName(), use RepositoryTrait
 *  - Adds: protected string $tableName = '...'
 * 
 * Usage: php tools/refactor-base-repository.php
 */

$baseDir = __DIR__ . '/../src/FrontAccounting/Repository';

$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($baseDir));
$count = 0;
$errors = [];

foreach ($it as $file) {
    if ($file->getExtension() !== 'php') continue;
    $path = $file->getPathname();
    $filename = $file->getFilename();

    $skipFiles = ['BaseRepository.php', 'RepositoryTrait.php'];
    if (in_array($filename, $skipFiles)) continue;

    $content = file_get_contents($path);

    // Skip if already extends BaseRepository
    if (preg_match('/extends\s+BaseRepository/', $content)) {
        continue;
    }

    // Must have a repo class
    if (!preg_match('/\b(?:final\s+)?class\s+\w+Repository\b/', $content)) {
        $errors[] = "Not a repo: $filename";
        continue;
    }

    // Extract table name from getTableName method or from SQL
    $tableName = null;
    if (preg_match('/return\s+\'([a-z_]+)\'\s*;/', $content, $m)) {
        $tableName = $m[1];
    } elseif (preg_match('/\{\$this->prefix\}([a-z_]+)/', $content, $m)) {
        $tableName = $m[1];
    }
    if (!$tableName) {
        $errors[] = "No table name: $filename";
        continue;
    }

    // 1. Change class declaration: add extends BaseRepository, remove use RepositoryTrait
    $content = preg_replace(
        '/((?:final\s+)?class\s+\w+Repository)\s*\{/',
        "$1 extends \\FrontAccounting\\Repository\\BaseRepository\n{",
        $content,
        1
    );

    // 2. Remove use RepositoryTrait; line
    $content = preg_replace('/^\s*use\s+RepositoryTrait\s*;\s*$/m', '', $content);

    // 3. Remove private DbAdapterInterface $db; line
    $content = preg_replace('/^\s*private\s+DbAdapterInterface\s+\$db\s*;\s*$/m', '', $content);

    // 4. Remove private string $prefix; line
    $content = preg_replace('/^\s*private\s+string\s+\$prefix\s*;\s*$/m', '', $content);

    // 5. Remove the constructor method
    $content = preg_replace(
        '/\n\s{4}public function __construct\(DbAdapterInterface \$db\)\s*\n\s{4}\{\s*\n\s{8}\$this->db\s*=\s*\$db;\s*\n\s{8}\$this->prefix\s*=\s*\$db->getTablePrefix\(\);\s*\n\s{4}\}/',
        '',
        $content
    );

    // 6. Remove getTableName() method
    $content = preg_replace(
        '/\n\s{4}protected function getTableName\(\)\s*:\s*string\s*\n\s{4}\{\s*\n\s{8}return\s+\'[a-z_]+\'\s*;\s*\n\s{4}\}/',
        '',
        $content
    );

    // 7. Add tableName property after opening brace (before first method or property)
    $content = preg_replace(
        '/\{\s*\n/',
        "{\n    protected string \$tableName = '$tableName';\n",
        $content,
        1
    );

    // Clean up multiple consecutive blank lines
    $content = preg_replace("/\n{3,}/", "\n\n", $content);

    file_put_contents($path, $content);
    $count++;
    echo "  $filename -> $tableName\n";
}

echo "\nDone. Updated $count files.\n";
if ($errors) {
    echo "Errors:\n";
    foreach ($errors as $e) echo "  $e\n";
}
