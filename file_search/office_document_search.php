<?php

namespace File_Search;

use \Exception;
use \File_Search\File_SearchInterface;

class Office_Document_Search implements File_SearchInterface {

    private $_fileName = '';
    private $_stringFoundIn = array();

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
     * Sets a search string to look for within a document and performs the search
     * @param  string $string
     * @return bool if matched
     */
    public function searchFileForString($string) {
        $this->_searchFilesInside($string);
        if($this->getNumberOccurances() > 0)
            return TRUE;
        else
            return FALSE;
    }

    /**
     * Returns a list of files within the document that contain the string
     * @return array
     */
    public function getFilesContainingString() {
        return $this->_stringFoundIn;
    }

    /**
     * Get the number of occurances within the document of the string
     * @return int
     */
    public function getNumberOccurances() {
        return (int) count($this->_stringFoundIn);
    }

    /**
     * Performs the magic, actually does the search
     * @param  string $string The search string
     */
    private function _searchFilesInside($string) {
        $file = zip_open($this->_fileName);
        if(!is_int($file)) {
            while($file_read = zip_read($file)) {
                $file_contents = zip_entry_read($file_read, 8192);
                if(stristr($file_contents, $string)) {
                    $this->_stringFoundIn[] = zip_entry_name($file_read);
                }
            }
        }
        return TRUE;
    }

}