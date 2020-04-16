<?php


class PageChanger
{

    /**
     * Returns page contents for change
     * @return String 
     * @throws Exception if the given page is not found
     */
    static function getPageContents(string $pageName) {

        if (empty($pageName)) {
            throw new Exception("Given pagename was empty!");
        }

        $filename = "../Pages/" . $pageName . ".html";
        $pageContents = file_get_contents($filename);
     
        if(!file_exists($filename))
            throw new Exception("Given page was not found!");

        return $pageContents;
    }



}