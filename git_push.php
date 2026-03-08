<?php
$dir = 'C:/xampp/htdocs/oc-shop';

function run(string $cmd): void {
    echo "> $cmd\n";
    exec($cmd . ' 2>&1', $out, $code);
    echo implode("\n", $out) . "\n";
    if ($code !== 0) {
        echo "[exit $code]\n";
    }
    echo "\n";
}

chdir($dir);

run('git config user.email "markprog@users.noreply.github.com"');
run('git config user.name "markprog"');
run('git checkout -b main');
run('git add .');
run('git status --short');
