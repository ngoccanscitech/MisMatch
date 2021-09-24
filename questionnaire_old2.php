<?php
require_once('startsession.php');
$page_title = "Questionnaire";
require_once('header.php');

require_once('appvars.php');
require_once('connectvars.php');
require_once('navmenu.php');

//Kiem tra nguoi dung da co response hay chua neu chua thi chen vao
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
 or 'Cannot connect to database mysql';
$query = "SELECT * FROM mismatch_response WHERE user_id='".$_SESSION['user_id']."'";
$data = mysqli_query($dbc, $query);
if(mysqli_num_rows($data) == 0){
    // lay topic_id tu topic table
    $query = "SELECT topic_id FROM mismatch_topic ORDER BY topic_id";
    $data = mysqli_query($dbc, $query);
    $topicIDs = array();
    while($row = mysqli_fetch_array($data)){
        array_push($topicIDs, $row['topic_id']); // do xay dung mang dong
    }
    //that ra cung chan can, minh dat truc tiep $row['topic_id'] vao query luon cung dc
    // chen empty response vao mismatch_response tuong ung voi moi topic
    foreach($topicIDs as $topicID){
        $query = "INSERT INTO mismatch_response (user_id, topic_id) VALUES ('".
        $_SESSION['user_id']."','$topicID')";
        mysqli_query($dbc, $query);
    }   
}
// neu form la submit, gui questionnaire den database
if(isset($_POST['submit'])){
    foreach($_POST as $response_id => $response){ 
        $query = "UPDATE mismatch_response SET response = '$response' WHERE response_id = '$response_id'";
        mysqli_query($dbc, $query);
    }
    echo "Your response have been save";
}
    $query = "SELECT response_id, topic_id, response FROM mismatch_response WHERE user_id ='".$_SESSION['user_id']."'";
    $data = mysqli_query($dbc, $query);
    $responses = array(); // phuc vu nhu bang ao tam thoi chua 5 truong
    while($row = mysqli_fetch_array($data)){
            //$query2 = "SELECT name, category FROM mismatch_topic WHERE topic_id = '".$row['topic_id']."'";
            $query2 = "SELECT name, category_id FROM mismatch_topic WHERE topic_id = '".$row['topic_id']."'";
            $data2 = mysqli_query($dbc, $query2);
            if(mysqli_num_rows($data2) == 1){ // dam bao thuc su la response data
                $row2 = mysqli_fetch_array($data2);
                $row['topic_name'] = $row2['name'];
                // $row['category_name'] = $row2['category'];
                //$row gio la gom response_id, topic_id, topic_name, mismatch_name
                // array_push($responses, $row);
                $query3 = "SELECT name FROM mismatch_category WHERE category_id = '".$row2['category_id']."'";
                $data3 = mysqli_query($dbc, $query3);
                if(mysqli_num_rows($data3) == 1){
                    $row3 = mysqli_fetch_array($data3);
                    $row['category_name'] = $row3['name'];
                    array_push($responses, $row);
                }
            }
    }
    echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'">';
    echo '<p>How do you feel about each topic? </p>';
    $category = $responses[0]['category_name'];
    echo '<fieldset><legend>'.$responses[0]['category_name'].'</legend>';
    foreach($responses as $response){
       
        if($category != $response['category_name']){
            $category = $response['category_name'];
            echo '</fieldset><fieldset><legend>'.$response['category_name'].'</legend>';
        }
        // hien thi cac topic cua category
        echo '<label '.($response['response'] == NULL ? 'class="error"' : '').' for="'.
            $response['response_id'].'">'.$response['topic_name'].':</label>';
        echo '<input type="radio" id="'.$response['response_id'].'" name="'.$response['response_id'].'" value="1"'.
        ($response['response'] == 1 ? 'checked = "checked"' : '').'/>Love';
        echo '<input type="radio" name="'.$response['response_id'].'" id="'.$response['response_id'].'" value="2"'.
        ($response['response'] == 2 ? 'checked = "checked"' : '').'/>Hate<br/>';
    }
    echo '</fieldset>';
    echo '<input type="submit" value="Save QUestionnaire" name="submit" >';
    echo '</form>';
    require_once('footer.php');
?>

