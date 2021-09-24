<?php 
    require_once('startsession.php');
    $page_title = 'Questionnaire';
    require_once('header.php');

    require_once('appvars.php');
    require_once('connectvars.php');

    if(!isset($_SESSION['user_id'])){
        echo '<p class="login">Please <a href="login.php">login</a> to access this page';
        exit();
    }
    // Neu nguoi dung chua tra loi questionnaire thi chen vao
    require_once('navmenu.php');
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) 
            or die('Cannot connect to database MYSQL');

    $query = "SELECT * FROM mismatch_response WHERE user_id = '".$_SESSION['user_id']."'";
    $data = mysqli_query($dbc, $query);
    if(mysqli_num_rows($data) ==0 ){
        $query = "SELECT topic_id FROM mismatch_topic ORDER BY topic_id";
        $data = mysqli_query($dbc, $query);
        $topicIDs = array();
        while($row = mysqli_fetch_array($data)){
            array_push($topicIDs, $row['topic_id']);
        }
        foreach( $topicIDs as $topicID){
            $query = "INSERT INTO mismatch_response ( user_id, topic_id ) VALUES ('".$_SESSION['user_id']."','$topicID')";
            mysqli_query($dbc, $query);
        }
        echo "Insert Success";
    }
    if(isset($_POST['submit'])){
        foreach($_POST as $response_id => $response){
            $query = "UPDATE mismatch_response SET response = '$response' WHERE response_id = '$response_id'";
            mysqli_query($dbc, $query);
        }
        echo '<p>Your response have been saved. </p>';
    }

    // $query = "SELECT topic_id, response_id, response FROM mismatch_response ". 
    // "WHERE user_id = '".$_SESSION['user_id']."'";
    // $data = mysqli_query($dbc, $query);
    // $responses = array();
    // while($row = mysqli_fetch_array($data)){
    //     $query2 = "SELECT name, category FROM mismatch_topic WHERE topic_id = '".$row['topic_id']."'";
    //     $data2 = mysqli_query($dbc, $query2);
    //     if(mysqli_num_rows($data2) == 1){
    //         $row2 = mysqli_fetch_array($data2);
    //         $row['topic_name'] = $row2['name'];
    //         $row['category_name'] = $row2['category'];
    //         array_push($responses, $row);
    //     }
    // }
    $query = "SELECT mr.response_id, mr.response, mt.topic_id,
     mt.name AS topic_name, mc.name AS category_name ".
    "FROM mismatch_response AS mr
    INNER JOIN mismatch_topic AS mt USING (topic_id)
    INNER JOIN mismatch_category AS mc USING (category_id)
    WHERE mr.user_id='".$_SESSION['user_id']."'";
    $data = mysqli_query($dbc, $query)
    or die('Cannot query to the database');
    echo mysqli_error($dbc);
    $responses = array();
    while($row = mysqli_fetch_array($data)){
        array_push($responses, $row);
    }

    mysqli_close($dbc);
    echo '<form method= "post" action="'.$_SERVER['PHP_SELF'].'">';
    echo '<p>How do you feel about each topic</p>';
    $category = $responses[0]['category_name'];
    echo '<fieldset><legend>'.$responses[0]['category_name'].'</legend>';
    foreach($responses as $response){
        if($category != $response['category_name']){
            $category = $response['category_name'];
            echo '</fieldset><fieldset><legend>'.$response['category_name'].'</legend>';
        }
        echo '<label '.($response['response'] == NULL ? 'class="error"' : '').'for = "'.$response['response_id'].'">'.$response['topic_name'].':</label>';
        echo '<input type="radio" id="'.$response['response_id'].'" name="'.$response['response_id'].'"value="1"'.
        ($response['response'] == 1 ? 'checked = "checked"' : '').'/>Love ';
        echo '<input type="radio" id="'.$response['response_id'].'" name="'.$response['response_id'].'"value="2"'.
        ($response['response'] == 2 ? 'checked = "checked"' : '').'/>Hate<br/> ';
    }
        echo '</fieldset>';
        echo '<input type="submit" value="Save questionnaire" name="submit" />';
        echo '</form>';

        require_once('footer.php');

?>