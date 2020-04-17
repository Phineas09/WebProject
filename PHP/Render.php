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

    static private function renderEditorAddCell() {

        $EditorAddCell = self::readElementToRender("EditorAddCell");
        return $EditorAddCell;
    }

    static public function renderElement(string $toRender) {

        if(strcmp ( $toRender, "textEditor") == 0) {
            return self::renderTextEditor();
        }
        if(strcmp ( $toRender, "textEditorAddCell") == 0) {
            return self::renderEditorAddCell();
        }
    }



}