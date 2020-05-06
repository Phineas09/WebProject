<?php


class ProblemsManager
{




    private function _queryFor(string $table, array $conditions) {
        $records = ORM::for_table($table)->where($conditions)->find_many();
        if (empty($records)) 
            return NULL;
        return $records;
    }

    private function _queryForOrderedAscPattern(string $pattern,string $table, array $conditions, string $orderBy) {
        $records = ORM::for_table($table)->order_by_asc($orderBy)->where($conditions)
        ->where_like('name', '%' . $pattern .'%')->find_many();
        if (empty($records)) 
            return NULL;
        return $records;
    }

    private function _queryForOrderedDescPattern(string $pattern, string $table, array $conditions, string $orderBy) {
        $records = ORM::for_table($table)->where_like('name', '%' . $pattern .'%')
            ->order_by_desc($orderBy)->where($conditions)->find_many();
        if (empty($records)) 
            return NULL;
        return $records;
    }

    private function _queryForOrderedAsc(string $table, array $conditions, string $orderBy) {
        $records = ORM::for_table($table)->order_by_asc($orderBy)->where($conditions)->find_many();
        if (empty($records)) 
            return NULL;
        return $records;
    }

    private function _queryForOrderedDesc(string $table, array $conditions, string $orderBy) {
        $records = ORM::for_table($table)->order_by_desc($orderBy)->where($conditions)->find_many();
        if (empty($records)) 
            return NULL;
        return $records;
    }

    private function _getProjectElement($problem, $hidden = "project-element-hidden") {

        //Get the user's name 
        $user = ORM::for_table("users")->where( array('id' => $problem->author))->find_one();
        //Make sure there is one
        if($user == false)
            $user = ORM::for_table("users")->find_one(1);
        //Count how many times was this problem solved
        $solvers = ORM::for_table("problems_solved")->where( array('problem' => $problem->id))->count();
        //Div element generalized
        
        $contents = 
            '<div tabindex="-1" class="project-element ' . $hidden . '">
                <a class="project-hidden">' . $problem->id . '</a>
                <div id="projectName" class="project-brief ' . $problem->difficulty . '">
                    <p>' . $problem->name . ' </p>
                </div>
                <p id="projectAuthor" class="project-author">' . $user->name . '</p>
                <div class="project-view-div">
                    <button onclick="viewProblem(this);" class="project-view-button">View Project</button>
                </div>
            </div>
            <div class="projectDetails">
                <div class="projectDetailsExtras">
                    <ul>
                        <li class="detailsTopLeft">Language: ' . $problem->language . '</li>
                        <li class="detailsTopRight">Solvers: ' . $solvers . '</li>
                        <li class="detailsDownLeft">Points: ' . $problem->points . '</li>
                        <li class="detailsDownRight">Date: ' . explode(' ', $problem->date)[0] . '</li>						
                        </ul>
                    </div>
                    <div class="projectDetailsBrief">
                        <p>' . $problem->presentation . '</p>
                        </div>
                    </div>';

        return $contents;
    }

    //If none are found returns NULL
    public function getProblemsBrief() { 
        return $this->_queryFor("problems", array('approved' => 1));
    }

    public function getProblemsPendingApproval() {
        $records = ORM::for_table("problems")->where(array('approved' => 0))
                        ->order_by_asc("date")
                        ->find_many();
        if (empty($records)) 
            return NULL;
        return $records;
    }

    public function getProblemsContentsPendingApproval() {

        $problems = $this->getProblemsPendingApproval();

        if($problems == NULL)
            throw new Exception("No Problems Avalable");
        
        $response = "";

        foreach($problems as $problem) {
            $response = $response . $this->_getProjectElement($problem);
        }

        return $response;
    }

    public function getProblemData($problemId) {

        $filename = "../Misc/Problems/" . $problemId . "/problemData";
        $problemData = file_get_contents($filename);
        if(!file_exists($filename))
            throw new Exception("Given problem does not have body!");
        return $problemData;
    }

    public function getSolvedProblems() {

        if (isset($_COOKIE['LoggedUser'])) {
            
            $user = ORM::for_table('users')->where(array('hash' => $_COOKIE['LoggedUser']))->find_one();

            if($user == false)
                return NULL;

            $problemsSolved = ORM::for_table('problems')
                ->inner_join(
                    'problems_solved',
                    array( 'problems_solved.problem', '=', 'problems.id'))
                ->where(array('problems_solved.user' => $user->id))    
                ->find_many();

            if (empty($problemsSolved)) 
                return NULL;
            return $problemsSolved;

        }
        return NULL;
    }

    public function getProblemName($problemId) {

        $result = ORM::for_table("problems")->where(array("id" => $problemId))->find_one();
        if($result) {
            return $result->name;
        }
        return "Undefined";
    }

    public function getProblemsPageContentsSearchBy(string $pattern, string $sortBy = "name", string $order = "asc") {
        //For each problem, return div elements properly formated
        // /_queryForOrderedAsc(string $table, array $conditions, string $orderBy)

        if($pattern === '')
            throw new Exception("No Problems Avalable");

        if($order === "asc") {
            $problems = $this->_queryForOrderedAscPattern($pattern ,"problems", array('approved' => 1), $sortBy);
        }
        else 
            $problems = $this->_queryForOrderedDescPattern($pattern, "problems", array('approved' => 1), $sortBy);

            
        if($problems == NULL)
            throw new Exception("No Problems Avalable");
        
        $response = "";

        foreach($problems as $problem) {
            // ! if you wanna change display type
            //$response = $response . $this->_getProjectElement($problem, "");
            $response = $response . $this->_getProjectElement($problem);
        }

        return $response;
    }

    public function approveProblem($user, $_MyPost) {

        $problemId = $_MyPost->problemId;

        $problem = ORM::for_table("problems")->where(array("id" => $problemId))->find_one();

        if($problem) {

            try {
                ORM::get_db()->beginTransaction();
                $problem->approved = $user->getId();
                $approvedBy = ORM::for_table("problems_approvedby")->create();
                $approvedBy->user = $user->getId();
                $approvedBy->problem = $problemId;
                $problem->points = $_MyPost->problemPoints;
                $problem->presentation = $_MyPost->problemBrief;
                $problem->difficulty = $_MyPost->problemDiff;
                $approvedBy->save();
                $problem->save();
                ORM::get_db()->commit();
                return;
            }
            catch (Exception $e) {
                ORM::get_db()->rollBack();
                throw new Exception("Internal Error");
            }
            return;
        }
        throw new Exception("Problem was not found!");

    }


    /**
     * Returns returns all validated problems properly formated 
     * @return String
     * @throws Exception if operation fail
    **/
    public function getProblemsPageContents(string $sortBy = "name", string $order = "asc") {
        //For each problem, return div elements properly formated
        // /_queryForOrderedAsc(string $table, array $conditions, string $orderBy)

        if($order === "asc") {
            $problems = $this->_queryForOrderedAsc("problems", array('approved' => 1), $sortBy);
        }
        else 
            $problems = $this->_queryForOrderedDesc("problems", array('approved' => 1), $sortBy);


        if($problems == NULL)
            throw new Exception("No Problems Avalable");
        
        $response = "";

        foreach($problems as $problem) {
            $response = $response . $this->_getProjectElement($problem);
        }

        return $response;
    }


    public function generateOutputFilesCpp(string $folderPath) {

        $folderPath = ".." . $folderPath;
        $sourcePath = $folderPath . "source.cpp";
        $command = escapeshellcmd('py ..\PythonCompiler\problemCompiler.py ' . $sourcePath);
        $output = exec($command);
        $scriptResponse = json_decode(stripslashes($output), true);
        if($scriptResponse["statusCode"] == 200) {
            return 1;
        }
        throw new Exception ("Problem at generation output files!");
    }

    public function verifySolution(string $sourcePath, string $inputPath, string $outputPath, $problemPoints) {

        $command = escapeshellcmd('py ..\PythonCompiler\problemChecker.py ' . $sourcePath . ' ' . $inputPath . ' ' . $outputPath . ' ' . $problemPoints);
        $output = exec($command);
        return $output;
    }

}