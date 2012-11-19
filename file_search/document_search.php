<?php

namespace File_Search;

use \File_Search\Office_Document_Search;
use \Iterator\WaxRecursiveDirectoryIterator;
use \Exception;
use \RecursiveIteratorIterator;
use \RegexIterator;
use \RecursiveRegexIterator;

class Document_Search {

    private $_directoryList = array();
    private $_fileList = array();
    private $_filesContainingSearchTerm = array();
    private $_searchableFileTypes = array(
        'doc', 'docx',
        'ppt', 'pptx',
        'xls', 'xlsx',
        'txt', 'md',
        'php', 'py',
        'sh',  'html',
        'js',  'css'
    );

    public function __construct(array $directory_list, $search_term) {
        if($search_term) {
            $this->_directoryList = $directory_list;
            $this->_createFileList();
            $this->_searchFilesFor($search_term);
        } else {
            throw new Exception("Search term is null, please make sure there is a value assigned.");
        }
    }

    public function getContainingFiles() {
        return $this->_filesContainingSearchTerm;
    }

    private function _createFileList() {
        foreach($this->_directoryList as $directory_name) {
            if(is_dir($directory_name)) {
                $directory = new WaxRecursiveDirectoryIterator($directory_name);
                $iterator = new RecursiveIteratorIterator($directory);

                $searchable_files = new RegexIterator(
                    $iterator, '/^.+\.('. implode("|", $this->_searchableFileTypes) .')$/i', 
                    RecursiveRegexIterator::GET_MATCH
                );

                foreach($searchable_files as $file) {

                    $this->_fileList[] = str_replace("\\", "/", reset($file));

                }

            } else {
                throw new Exception("Folder ({$directory_name}) does not exist");
            }
        }
    }

    private function _searchFilesFor($string) {
        foreach($this->_fileList as $filename) {

            switch(substr(strrchr($filename,'.'),1)) {

                //All of the office x extensions
                case 'docx':
                case 'pptx':
                case 'xlsx':
                    $Office_Document_Search = new Office_Document_Search($filename);
                    if($Office_Document_Search->searchDocumentForString($string)) {
                        $this->_filesContainingSearchTerm[] = $filename;
                    }
                    break;

            }

        }
    }

}