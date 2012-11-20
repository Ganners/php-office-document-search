<?php

namespace File_Search;

use \Exception;
use \File_Search\File_SearchInterface;

class Text_File_Search implements File_SearchInterface {

    private $_fileName = '';
    private $_stringFound = FALSE;

    /**
     * The constructor for the office search
     * @param Filename - This takes a single filename of an office document format
     */
    public function __construct($filename) {
        if(file_exists($filename)) {
            $this->_fileName = $filename;
        } else {
            throw new Exception("File {$filename} could not be found. Please ensure the path is correct and this file exists.");
        }
    }

    /**
     * Sets a search string to look for within a file and performs the search
     * @param  string $string
     * @return bool if matched
     */
    public function searchFileForString($string) {
        $this->_searchString($string);
        return $this->_stringFound;
    }

    /**
     * Performs the magic, actually does the search
     * @param  string $string The search string
     */
    private function _searchString($string) {
        $file = fopen($this->_fileName, 'r');
        if(filesize($this->_fileName) > 0) {
            $file_contents = fread($file, filesize($this->_fileName));
            if(stristr($file_contents, $string)) {
                $this->_stringFound = TRUE;
            }
        }
        fclose($file);
        return TRUE;
    }

}