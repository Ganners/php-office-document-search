<?php
require_once('loader.php');
set_time_limit(60);
$docsearch = new File_Search\Document_Search(array(__DIR__ . '/test_files'), isset($_GET['search']) ? $_GET['search'] : 'auto' );
var_dump($docsearch->getContainingFiles());