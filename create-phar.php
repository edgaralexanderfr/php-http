<?php

/**
 * ```sh
 * # Usage:
 * php --define phar.readonly=0 create-phar.php
 * ```
 *
 * https://blog.programster.org/creating-phar-files
 */

try {
    $pharFile = 'lib/php-http.phar';

    // clean up
    if (file_exists($pharFile)) {
        unlink($pharFile);
    }

    if (file_exists($pharFile . '.gz')) {
        unlink($pharFile . '.gz');
    }

    // create phar
    $phar = new Phar($pharFile);

    // start buffering. Mandatory to modify stub to add shebang
    $phar->startBuffering();

    // Create the default stub from main.php entrypoint
    // $defaultStub = $phar->createDefaultStub('bin/php-http.php');

    // Copy files into build folder
    // This is not cross-platform... but it works...
    `cp -r src build/src`;
    `cp autoload.php build/autoload.php`;

    // Add the rest of the apps files
    $phar->buildFromDirectory(__DIR__ . '/build');

    // Customize the stub to add the shebang
    // $stub = "#!/usr/bin/env php \n" . $defaultStub;

    // Add the stub
    // $phar->setStub($stub);
    $phar->setStub(file_get_contents('bin/php-http.php'));

    $phar->stopBuffering();

    // plus - compressing it into gzip  
    $phar->compressFiles(Phar::GZ);

    # Make the file executable
    chmod(__DIR__ . "/{$pharFile}", 0770);

    // Delete files from build directory
    `rm build/autoload.php`;
    `rm -rf build/src`;

    echo "$pharFile successfully created" . PHP_EOL;
} catch (Exception $e) {
    echo $e->getMessage();
}
