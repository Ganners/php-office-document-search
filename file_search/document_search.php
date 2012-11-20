<?php

namespace File_Search;

use \File_Search\Office_Document_Search;
use \File_Search\Text_File_Search;
use \Iterator\WaxRecursiveDirectoryIterator;
use \Exception;
use \RecursiveIteratorIterator;
use \RegexIterator;
use \RecursiveRegexIterator;

class Document_Search {

    private $_directoryList = array();
    private $_fileList = array();
    private $_filesContainingSearchTerm = array();

    /**
     * The file types that are deemed 'searchable' and are
     * supported by this module.
     * @var array
     */
    private $_searchableFileTypes = array(
        'doc', 'docx',
        'ppt', 'pptx',
        'xls', 'xlsx',
        'txt', 'md',
        'php', 'py',
        'sh',  'html',
        'js',  'css'
    );

    /**
     * Sets up and performs the search.
     * 
     * @param array $directory_list
     * @param string $search_term
     * @throws Exception If Search term is null
     */
    public function __construct(array $directory_list, $search_term) {
        if($search_term) {
            $this->_directoryList = $directory_list;
            $this->_createFileList();
            $this->_searchFilesFor($search_term);
        } else {
            throw new Exception("Search term is null, please make sure there is a value assigned.");
        }
    }

    /**
     * Returns a list of files which contain the search term
     * @return array
     */
    public function getContainingFiles() {
        return $this->_filesContainingSearchTerm;
    }

    /**
     * Creates a file list which is to be searched
     * @uses _searchableFileTypes array
     */
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
        return TRUE;
    }

    /**
     * Searches all of the file list for the search word
     * 
     * @param string $string
     * @throws Exception If a file format is not supported
     */
    private function _searchFilesFor($string) {
        foreach($this->_fileList as $filename) {

            $file_format = substr(strrchr($filename,'.'),1);

            switch($file_format) {
                /**
                 * The office formats which have to be unzipped
                 */
                case 'docx':
                case 'pptx':
                case 'xlsx':
                    $Office_Document_Search = new Office_Document_Search($filename);
                    if($Office_Document_Search->searchFileForString($string)) {
                        $this->_filesContainingSearchTerm[] = $filename;
                    }
                    break;

                case 'doc':
                case 'txt':
                    $Text_File_Search = new Text_File_Search($filename);
                    if($Text_File_Search->searchFileForString($string)) {
                        $this->_filesContainingSearchTerm[] = $filename;
                    }
                    break;
                /**
                 * If the file format is unrecognised or unsupported
                 */
                default:
                    //throw new Exception("The file format '{$file_format}' is not supported, how did you slip through?");
                    break;
            }

        }
        return TRUE;
    }

}