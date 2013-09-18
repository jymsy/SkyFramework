<?php
chdir(__DIR__);
if (!is_dir('log')) mkdir('log', 0777);
chmod('log', 0777);
if (!is_dir('config')) mkdir('config', 0777);
chmod('config', 0777);
echo 'Success create folder: config, log',PHP_EOL;