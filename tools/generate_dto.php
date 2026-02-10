<?php

/**
 * DTO generator (minimal) from a schema descriptor.
 *
 * Usage:
 *   php tools/generate_dto.php Ksfraser\\FA\\Schema\\SalesTypesSchema src/Ksfraser/FA/DTO/Generated/SalesType.php 7.3
 *
 * Notes:
 * - For PHP 7.3: generates untyped protected properties with docblocks.
 * - For PHP 7.4+: can be extended to emit typed properties.
 */

if ($argc < 4) {
    fwrite(STDERR, "Usage: php tools/generate_dto.php <SchemaClass> <OutputFile> <PhpVersion>\n");
    exit(2);
}

$schemaClass = $argv[1];
$outputFile = $argv[2];
$phpVersion = $argv[3];

$autoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
}

if (!class_exists($schemaClass)) {
    fwrite(STDERR, "Schema class not found: {$schemaClass}\n");
    exit(2);
}

if (!method_exists($schemaClass, 'descriptor')) {
    fwrite(STDERR, "Schema class must have static descriptor(): {$schemaClass}\n");
    exit(2);
}

$descriptor = $schemaClass::descriptor();

if (!is_array($descriptor) || !isset($descriptor['fields']) || !is_array($descriptor['fields'])) {
    fwrite(STDERR, "Invalid descriptor shape from {$schemaClass}\n");
    exit(2);
}

$entity = isset($descriptor['entity']) ? (string) $descriptor['entity'] : 'Entity';

// Very small name helper: sales_types -> SalesTypes
$dtoBaseName = str_replace(' ', '', ucwords(str_replace(array('-', '_'), ' ', $entity)));
$dtoClassName = $dtoBaseName;

$namespace = 'Ksfraser\\FA\\DTO\\Generated';

$lines = array();
$lines[] = '<?php';
$lines[] = '';
$lines[] = 'namespace ' . $namespace . ';';
$lines[] = '';
$lines[] = '/**';
$lines[] = ' * GENERATED FILE - do not edit by hand.';
$lines[] = ' * Source schema: ' . $schemaClass;
$lines[] = ' */';
$lines[] = 'class ' . $dtoClassName;
$lines[] = '{';

foreach ($descriptor['fields'] as $fieldName => $field) {
    if (!is_array($field)) {
        $field = array();
    }

    $sqlType = isset($field['type']) ? (string) $field['type'] : 'mixed';
    $label = isset($field['label']) ? (string) $field['label'] : '';

    $lines[] = '    /**';
    if ($label !== '') {
        $lines[] = '     * ' . $label;
        $lines[] = '     *';
    }
    $lines[] = '     * @var ' . self_guessDocType($sqlType);
    $lines[] = '     */';

    // PHP 7.3: no typed properties. Keep protected for now.
    $lines[] = '    protected $' . $fieldName . ';';
    $lines[] = '';
}

$lines[] = '}';
$lines[] = '';

// Ensure output directory exists.
$outDir = dirname($outputFile);
if (!is_dir($outDir)) {
    if (!mkdir($outDir, 0777, true) && !is_dir($outDir)) {
        fwrite(STDERR, "Failed to create output dir: {$outDir}\n");
        exit(2);
    }
}

file_put_contents($outputFile, implode("\n", $lines));

fwrite(STDOUT, "Wrote DTO: {$outputFile}\n");

function self_guessDocType($sqlType)
{
    $t = strtolower($sqlType);

    if (strpos($t, 'bool') !== false || strpos($t, 'tinyint(1)') !== false) {
        return 'bool';
    }
    if (preg_match('/\b(int|smallint|bigint)\b/', $t)) {
        return 'int';
    }
    if (strpos($t, 'double') !== false || strpos($t, 'decimal') !== false || strpos($t, 'float') !== false) {
        return 'float';
    }
    if (strpos($t, 'date') !== false || strpos($t, 'time') !== false) {
        return 'string';
    }

    return 'string';
}
