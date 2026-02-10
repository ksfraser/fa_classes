<?php

$autoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
    return;
}

$repoDir = dirname(__DIR__);
$reposDir = dirname(__DIR__, 2);

spl_autoload_register(static function (string $class) use ($repoDir, $reposDir): void {
    $prefixes = [
        'Ksfraser\\FA\\' => $repoDir . '/src/Ksfraser/FA/',
        'Ksfraser\\Validation\\' => $reposDir . '/validation/src/Ksfraser/Validation/',
        'Ksfraser\\ModulesDAO\\' => $reposDir . '/ksf_ModulesDAO/src/Ksfraser/ModulesDAO/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
            continue;
        }

        $relative = substr($class, strlen($prefix));
        $file = $baseDir . str_replace('\\', '/', $relative) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
        return;
    }
});
