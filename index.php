<?php
require_once('loader.php');
$docsearch = new File_Search\Document_Search(array(__DIR__ . '/test_files'), isset($_GET['search']) ? $_GET['search'] : 'auto' );
var_dump($docsearch->getContainingFiles());