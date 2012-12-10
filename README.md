PHP File Searcher
=================

Author: Mark Gannaway

Tested Version of PHP: php 5.3.8

This is a PHP based file searcher that solves the problem of being able to search microsoft office files such as docx, pptx, xlsx dynamically.

It's pretty quick, searching over 4GB worth of files took around 20 seconds in total. There's room for caching and optimization however this is the quickest and easiest solution for a smaller file base.

It allows putting in the directories which will be recursively iterated through.

To use you must require the loader (it does use the __autoload method, you may need to register a new method). Then instantiate File_Search\Document_Search.

Look at index.php for an example usage. To use this please create a directory in the same directory as the index file called test_files and put in some test searchable files.

Tags:
*Search docx files with php
*Search office files with php
*Search xls with php