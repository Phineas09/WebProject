<?php

class Render {

    static private function getiFrameNumber() {
        if (isset($_SESSION['iFrame'])) {
            $_SESSION['iFrame'] = intval($_SESSION['iFrame']) + 1;
            return $_SESSION['iFrame'];
        }
        else {
            $_SESSION['iFrame'] = 0;
            return 0;
        }
    }

    static private function readElementToRender($element) {

        $filename = "../Elements/" . $element . ".html";
        $pageContents = file_get_contents($filename);
        if(!file_exists($filename))
            throw new Exception("Given element was not found!");

        return $pageContents;
    }

    static private function renderTextEditor() {
        $textEditor = str_replace("richTextFieldID", "richTextField" . strval(self::getiFrameNumber()), self::readElementToRender("TextEditor"));
        return $textEditor;
    }

    static private function renderDrawEditor() {
        $textEditor = str_replace("drawCanvasID", "drawCanvas" . strval(self::getiFrameNumber()), self::readElementToRender("DrawEditor"));
        return $textEditor;
    }

    static private function renderEditorAddCell() {

        $EditorAddCell = self::readElementToRender("EditorAddCell");
        return $EditorAddCell;
    }

    static public function renderElement(string $toRender) {

        if(strcasecmp  ( $toRender, "textEditor") == 0) {
            return self::renderTextEditor();
        }
        if(strcasecmp  ( $toRender, "textEditorAddCell") == 0) {
            return self::renderEditorAddCell();
        }
        if(strcasecmp  ( $toRender, "drawEditor") == 0) {
            return self::renderDrawEditor();
        }
    }

    static private function renderProfilePage($user) {

        $profilePage = self::readElementToRender("ProfilePage");
        //Format profile Page 
        $profilePage = str_replace("ReplaceTitle", $user->getTitle(),$profilePage);
        $profilePage = str_replace("ReplaceUsername", $user->getUsername(),$profilePage);
        $profilePage = str_replace("ReplaceEmailAddress", $user->getEmailAddress(),$profilePage);
        $profilePage = str_replace("ReplaceProfileImage", $user->getProfilePicture(),$profilePage);
        $profilePage = str_replace("ReplaceFirstName", $user->getFirstName(),$profilePage);
        $profilePage = str_replace("ReplaceLastName", $user->getLastName(),$profilePage);
        $profilePage = str_replace("ReplaceAddress", $user->getAddress(),$profilePage);
        $profilePage = str_replace("ReplacePhone", $user->getPhone(),$profilePage);
        $profilePage = str_replace("ReplaceBirth", $user->getBirthDate(),$profilePage);
        $profilePage = str_replace("ReplacePoints", $user->getUserPoints(),$profilePage);
        $profilePage = str_replace("ReplaceSolved", $user->getNumberOfProblemsSolved(),$profilePage);
        $profilePage = str_replace("ReplacePosted", $user->getNumberOfPublishedProblems(),$profilePage);

        return $profilePage;
    }


    static public function renderPageContents($user, $pageName) {

        if(strcasecmp  ( $pageName, "profilepage") == 0) {
            return self::renderProfilePage($user);
        }


    }



}