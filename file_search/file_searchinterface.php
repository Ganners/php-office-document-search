<?php

namespace File_Search;

interface File_SearchInterface {

    /**
     * The constructor for the office search
     * @param Filename - This takes a single filename of an office document format
     */
    public function __construct($filename);

    /**
     * Sets a search string to look for within a file and performs the search
     * @param  string $string
     * @return bool if matched
     */
    public function searchFileForString($string);

}