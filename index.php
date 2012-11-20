<?php

require_once('loader.php');
set_time_limit(60);

try {
    $docsearch = new File_Search\Document_Search(
        array(__DIR__ . '/test_files'), 
        isset($_GET['search']) ? $_GET['search'] : '' 
    );
} catch(Exception $e) {
    echo $e->getMessage();
}

?>

<h1>Search files for a word:</h1>
<form method="get">
    <input type="text" name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : '' ; ?>" />
    <input type="submit" value="submit" />
</form>

<?php 
    if(isset($docsearch) && $docsearch != NULL) { 
        var_dump($docsearch->getContainingFiles()); 
    } 
?>