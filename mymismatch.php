<?php
    require_once('startsession.php');
    $page_title = "My Mismatch";
    require_once('header.php');
    require_once('navmenu.php');
    require_once('connectvars.php');
    
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
    or die('Cannot connect to database Mysql');
    $query = "SELECT * FROM mismatch_response WHERE user_id = '".$_SESSION['user_id']."'";
    $data = mysqli_query($dbc, $query);
    // dam bao nguoi dung da chon thich hay khong thich trong bang form questionnaire roi
    // input vao la bang response va topic,output la user_responses co chua topic_id, topic_name, response_id, response_name
    if(mysqli_num_rows($data) != 0){
        $query = "SELECT mr.response_id, mr.response, mt.topic_id, mt.name AS topic_name ".
        "FROM mismatch_response AS mr ".
        "INNER JOIN mismatch_topic AS mt USING (topic_id) ".
        "WHERE user_id = '".$_SESSION['user_id']."'";
        $data = mysqli_query($dbc, $query);
        $user_responses = array();
        while($row = mysqli_fetch_array($data)){
            array_push($user_responses, $row);
        }

        // khoi tao bien de luu ideal mismatch user_id, diem, nhung topic doi nguoc
        $mismatch_score = 0;
        $mismatch_user_id = -1;
        $mismatch_topics = array();

        // so sanh responses nguoi dung hien tai voi tat ca nguoi dung con lai trong he thong
        //input vao la tat ca user_id, responses cua tung nguoi output la score, mismatch_topics 
        $query = "SELECT user_id FROM mismatch_user WHERE user_id != '".$_SESSION['user_id']."'";
        $data = mysqli_query($dbc, $query);
        while($row = mysqli_fetch_array($data)){
            $query2 = "SELECT mr.response_id, mr.response, mt.topic_id, mt.name AS topic_name ".
                    "FROM mismatch_response AS mr ".
                    "INNER JOIN mismatch_topic AS mt USING (topic_id) ".
                    "WHERE user_id = '".$row['user_id']."'";
            $data2 = mysqli_query($dbc, $query2);
            $mismatch_responses = array();
            while($row2 = mysqli_fetch_array($data2)){
                array_push($mismatch_responses, $row2);
            }
            
            // tien hanh so sanh
            $score = 0;
            $topics = array();
            for($i = 0; $i < count($user_responses); $i++){
                if((int)$user_responses[$i]['response'] + (int)$mismatch_responses[$i]['response'] == 3){
                    $score+=1;
                    array_push($topics, $user_responses[$i]['topic_name']);
                }
            }

            // so sanh diem hien tai score va diem lon nhat mismatch_score
            if($score > $mismatch_score){
                $mismatch_score = $score;
                $mismatch_user_id = $row['user_id'];
                $mismatch_topics = array_slice($topics, 0);
            }
        }

        // lay het thong tin nguoi dung ra va hien thi ra man hinh
        if($mismatch_user_id != -1){
            $query = "SELECT first_name, last_name FROM mismatch_user WHERE user_id = '$mismatch_user_id'";
            $data = mysqli_query($dbc, $query);
            while($row = mysqli_fetch_array($data)){
                echo 'Ban phu hop voi nguoi dung '.$mismatch_user_id.'<br/>';

                // hien thi mismatch_topics
                echo '<h4>Ban phu hop o '.count($mismatch_topics).' topic : </h4>';
                foreach($mismatch_topics as $topic){
                    echo $topic.'<br/>';
                }

                //hien thi toi link profile cua nguoi kia
                echo '<h4>View Profile <a href="viewprofile.php?user_id='.$mismatch_user_id.'">'.$row['first_name'].'</h4>';
            }
        }
    }
    require_once('footer.php');
?>