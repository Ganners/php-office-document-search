<?php

namespace File_Search;

use \Exception;

class Office_Document_Search {

    private $_fileName = '';
    private $_stringFoundIn = array();

    public function __construct($filename) {
        if(file_exists($filename)) {
            $this->_fileName = $filename;
        } else {
            throw new Exception("File {$filename} could not be found. Please ensure the path is correct and this file exists.");
        }
    }

    public function searchDocumentForString($string) {
        $this->_searchFilesInside($string);
        if($this->getNumberOccurances() > 0)
            return TRUE;
        else
            return FALSE;
    }

    public function getFilesContainingString() {
        return $this->_stringFoundIn;
    }

    public function getNumberOccurances() {
        return (int) count($this->_stringFoundIn);
    }

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
    }

}