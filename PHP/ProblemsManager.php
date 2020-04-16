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
                    <button class="project-view-button">View Project</button>
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
        return $this->_queryFor("problems", array('approved' => 0));
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

}