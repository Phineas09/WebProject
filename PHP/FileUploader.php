<?php

class FileUploader {


    static private $extensions = array('c' => 'C', 'cpp' => 'C++');
    static private $reverseExtensions = array('C' => 'c', 'C++' => 'cpp');

    static public function submitProblem() {
        try {
            $user = new User();
            $newProblem = ORM::for_table('problems')->create();
            $newProblem->author = $user->getId();
            $newProblem->name = $_POST["title"];

            //Get extension of sourceFile
            //We are sure that tere is only one source file
            $file_ext = pathinfo($_FILES['sourceFile']['name'], PATHINFO_EXTENSION);
            if(!array_key_exists($file_ext, self::$extensions)) 
                throw new Exception("Invalid file format");

            $newProblem->language = self::$extensions[$file_ext];
            $newProblem->testCases = 0;

            self::formatProblemFiles($newProblem);
            //Make directory for problem
            $newProblem->testCases = count($_FILES['testFiles']['tmp_name']);
            $newProblem->save();
 
            echo json_encode(
                array(
                    'statusCode' => 200
                ));
        }
        catch (Exception $e) {
            echo json_encode(
                array(
                    'statusCode' => 420
                ));
        }
        exit;
    }

    static public function makeArhiveFile($problemId) {

        $zipFile = new ZipArchive;
        $problemPath = './../Misc/Problems/' . $problemId;
        
        if ($zipFile->open($problemPath . "/inputFiles.zip" , (ZipArchive::CREATE | ZipArchive::OVERWRITE)) === TRUE)
        {
            if ($handle = opendir($problemPath . "/Inputs"))
            {
                while (false !== ($entry = readdir($handle)))
                {
                    if ($entry != "." && $entry != ".." && !is_dir($problemPath . "/Inputs/" . $entry))
                    {
                        $zipFile->addFile($problemPath . "/Inputs/" . $entry, $entry);
                    }
                }
                closedir($handle);
            }
            $zipFile->close();
        }
        return $problemPath . "/inputFiles.zip";
    }

    static public function appendFilesToProblem() {
        try {
            $problem = ORM::for_table("problems")->where(array("id" => $_POST["problemId"]))->find_one();

            if($problem) {
                if(isset($_POST["overwrite"])) {
                    self::formatProblemFiles($problem, true);
                }
                else {
                    self::formatProblemFiles($problem);
                }
            }
            else throw new Exception("Problem was not found!");
            
            $problem->name = $_POST["title"];
            $problem->save();
        }
        catch (Exception $e) {
            echo json_encode(
                array(
                    'statusCode' => 420
                ));
        }
    }

    static private function formatProblemFiles($newProblem, $overWrite = false) {

        $problemPath = './../Misc/Problems/' . $newProblem->id;

        if(!is_dir($problemPath. '/Inputs' )) {
            mkdir($problemPath . '/Inputs', 0777, true);
        }
        else {
            if($overWrite) {
                // Delete already existing input files
                array_map('unlink', glob($problemPath . "/Inputs/*.*"));
            }
        }

        if(!is_dir($problemPath. '/Outputs' ))    
            mkdir($problemPath . '/Outputs', 0777, true);

        $testCases = count($_FILES['testFiles']['tmp_name']);

        for($i = 0; $i < $testCases; $i++) {
            if(!$overWrite) {
                $testFileNumber = $problemPath . '/Inputs/in' . ($i + $newProblem->testCases) . '.txt';
            }
            else {
                $testFileNumber = $problemPath . '/Inputs/in' . $i . '.txt';
            }
            $file_tmp = $_FILES['testFiles']['tmp_name'][$i];
            $file_type = $_FILES['testFiles']['type'][$i];
            //$file_size = $_FILES['testFiles']['size'][$i];
            if(!move_uploaded_file($file_tmp, $testFileNumber)) {
                throw new Exception("File move error!");
            }
        }
        if($overWrite) {
            $newProblem->testCases = $testCases;
        }
        else {
            $newProblem->testCases = $newProblem->testCases +  $testCases;
        }

        //Move source file

        if(isset($_FILES['sourceFile'])) {
            $file_ext = pathinfo($_FILES['sourceFile']['name'], PATHINFO_EXTENSION);
            $testFileNumber = $problemPath . '/source.' . $file_ext;
            $file_tmp = $_FILES['sourceFile']['tmp_name'];
            $file_type = $_FILES['sourceFile']['type'];
            if(!move_uploaded_file($file_tmp, $testFileNumber)) {
                throw new Exception("File move error!");
            }
        }

        //Make file for problemData 
        $problemData = fopen($problemPath . '/problemData', "w");
        if(!$problemData) 
            throw new Exception("File move error!");

        fwrite($problemData, $_POST["problemData"]);
        fclose($problemData);

    }

    static public function appendSourceFileToProblem() {
        if(isset($_FILES['sourceFile'])) {

            $newProblem = ORM::for_table("problems")->where(array("id" => $_POST["problemId"]))->find_one();
            if($newProblem) {
                $problemPath = './../Misc/Problems/' . $newProblem->id;
                $file_ext = pathinfo($_FILES['sourceFile']['name'], PATHINFO_EXTENSION);
                $testFileNumber = $problemPath . '/source.' . $file_ext;
                $file_tmp = $_FILES['sourceFile']['tmp_name'];
                $file_type = $_FILES['sourceFile']['type'];
                if(!move_uploaded_file($file_tmp, $testFileNumber)) {
                    throw new Exception("File move error!");
                }
            }
        }
    }

    static public function updateProblemFormData() {


        $newProblem = ORM::for_table("problems")->where(array("id" => $_POST["problemId"]))->find_one();
        $newProblem->name = $_POST["title"];
        $newProblem->save();
        $problemPath = './../Misc/Problems/' . $newProblem->id;
        $problemData = fopen($problemPath . '/problemData', "w");
        if(!$problemData) 
            throw new Exception("File move error!");

        fwrite($problemData, $_POST["problemData"]);
        fclose($problemData);
    }

}

