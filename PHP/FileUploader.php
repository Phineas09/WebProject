<?php

class FileUploader {


    static private $extensions = array('c' => 'C', 'cpp' => 'C++');
    static private $reverseExtensions = array('C' => 'c', 'C++' => 'cpp');

    static public function submitProblem($problemManager) {
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
            $newProblem->save();

            self::formatProblemFiles($newProblem);
            //Make directory for problem
            $newProblem->testCases = count($_FILES['testFiles']['tmp_name']);
            $newProblem->save();
 
            $problemPath = '\Misc\Problems\\';
            $problemPath = $problemPath . $newProblem->id . "\\";
            $problemManager->generateOutputFilesCpp($problemPath);

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

    static public function makeArhiveProblem($problemId) {
        $zipFile = new ZipArchive;
        $problemPath = './../Misc/Problems/' . $problemId;
        
        $problem = ORM::for_table("problems")->where(array("id" => $problemId))->find_one();

        if($problem) {
            if ($zipFile->open($problemPath . "/problemFiles.zip" , (ZipArchive::CREATE | ZipArchive::OVERWRITE)) === TRUE)
            {
                if ($handle = opendir($problemPath . "/Inputs"))
                {
                    while (false !== ($entry = readdir($handle)))
                    {
                        if ($entry != "." && $entry != ".." && !is_dir($problemPath . "/Inputs/" . $entry))
                        {
                            $zipFile->addFile($problemPath . "/Inputs/" . $entry,  "Inputs/" . $entry);
                        }
                    }
                    closedir($handle);
                }

                if ($handle = opendir($problemPath . "/Outputs"))
                {
                    while (false !== ($entry = readdir($handle)))
                    {
                        if ($entry != "." && $entry != ".." && !is_dir($problemPath . "/Outputs/" . $entry))
                        {
                            $zipFile->addFile($problemPath . "/Outputs/" . $entry,  "Outputs/" . $entry);
                        }
                    }
                    closedir($handle);
                }

                $zipFile->addFile($problemPath . "/source." . self::$reverseExtensions[$problem->language],  "source." . self::$reverseExtensions[$problem->language] );

                $zipFile->close();
            }
            return $problemPath . "/problemFiles.zip";
        }
        throw new Exception("Problem was not found!?");
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

    static public function submitSolution($problemManager) {

         
        if(isset($_FILES['sourceFile'])) {
            $user = new User();
            if($user->isGuest()) {
                throw new Exception("You cannot submit solutions as guest!");
            }
            $problem = ORM::for_table("problems")->where(array("id" => $_POST["problemId"]))->find_one();

            if(!$problem) 
                throw new Exception("Problem was not found!");

            $sourcePath = './../Misc/UsersSubmits/' . $user->getUserHash() . "/" . $problem->id . "/";

            if(!is_dir($sourcePath)) {
                mkdir($sourcePath, 0777, true);
            }

            $filesInFolder = new FilesystemIterator($sourcePath, FilesystemIterator::SKIP_DOTS);
            $numberOfSubmits = iterator_count($filesInFolder);

            //Move sourceFile, with extension and other stuff

            $file_ext = pathinfo($_FILES['sourceFile']['name'], PATHINFO_EXTENSION);
            $sourcePath = $sourcePath . 'source' . $numberOfSubmits . '.' . $file_ext;

            if(strcasecmp ($problem->language, self::$extensions[$file_ext]) != 0) 
                throw new Exception("Invalid language used!");

                $file_tmp = $_FILES['sourceFile']['tmp_name'];
            if(!move_uploaded_file($file_tmp, $sourcePath)) {
                throw new Exception("File move error!");
            }

            //We have moved the file, now test it

            if(!$problem->points) {
                throw new Exception("Problem is not corectly formatted!");
            }

            $problemFilesPath = '../Misc/Problems/' . $problem->id . '/';

            $output = $problemManager->verifySolution($sourcePath, $problemFilesPath . "Inputs", $problemFilesPath . "Outputs", $problem->points);

            $scriptResponse = json_decode(stripslashes($output), true);

            //print_r($scriptResponse);

            //Compare statusCodes and if 200 add score to problem and record the failed and passed tests

            if($scriptResponse["statusCode"] == 200) {
                $returnText = "";
                //Format array and send to user 
                for($i = 0; $i < $problem->testCases; $i++) {
                    $returnText = $returnText . "Test " . strval($i) . " => " . $scriptResponse[strval($i)] . "\n"; 
                }
                $returnText = $returnText . "\nPoints Gained : " . $scriptResponse["score"];
            }
            else {

                $recordSolve = ORM::for_table("problems_solved")->create();
                $recordSolve->user = $user->getId();
                $recordSolve->problem = $problem->id;
                $recordSolve->points = 0;
                $recordSolve->result = $output;
                $recordSolve->submitNumber = $numberOfSubmits;
                $recordSolve->save();

                throw new Exception($scriptResponse["message"]);
            }

            $recordSolve = ORM::for_table("problems_solved")->create();
            $recordSolve->user = $user->getId();
            $recordSolve->problem = $problem->id;
            $recordSolve->points = $scriptResponse["score"];
            $recordSolve->submitNumber = $numberOfSubmits;
            $recordSolve->result = $output;
            $recordSolve->save();

        }
        else
            throw new Exception("No file was sent!");
                 
        //Send problem results
        echo json_encode(
            array(
                'statusCode' => 200,
                'problemResults' => $returnText
            ));
    }

}

