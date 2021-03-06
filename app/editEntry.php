<?php
    session_start();
    include('connectToServer.php');

    $thesis_id = $_POST['thesis_id'];
    $title = trim(str_replace('\'', '\\\'', $_POST['title']));
    $abstract = trim(str_replace('\'', '\\\'', $_POST['abstract']));
    $researchers = $_POST['researcher'];
    $pub_date = $_POST['pub_date'];
    $category = $_POST['category'];
    $acc_date = $_POST['acc_date'];
    $faculty_id = explode('_', $_SESSION['user_id'])[1];
    $dept_id = mysql_query("SELECT department_id FROM faculty_profiles WHERE faculty_id = '$faculty_id'");
    $dept_id = mysql_fetch_assoc($dept_id)['department_id'];
    $tags = explode(',', $_POST['tags']);

    $checkDuplicate = mysql_query("SELECT * FROM thesis WHERE title LIKE '%$title%' OR abstract LIKE '%$abstract%'");
    $checkDuplicate = mysql_fetch_assoc($checkDuplicate);

    if ($title && $abstract && $researchers && $pubdate && $category && $acc_date && $tags) {
        if ($checkDuplicate['thesis_id'] == $thesis_id) {
            mysql_query("UPDATE thesis SET title = '$title', abstract = '$abstract', date_published = '$pub_date', date_accessioned = '$acc_date' WHERE thesis_id = '$thesis_id'");
            
            $tag_array = array();
            foreach ($tags as $tag) {
                $tag = trim(str_replace('\'', '\\\'', $tag));
                $tag_search = mysql_query("SELECT tag_id FROM tags WHERE tag_name = '$tag'");
                if (mysql_num_rows($tag_search) == 1) {
                    $tag_id = mysql_fetch_array($tag_search);
                    array_push($tag_array, $tag_id[0]);
                } else {
                    mysql_query("INSERT INTO tags (tag_name) VALUES ('$tag')");
                    array_push($tag_array, mysql_insert_id());
                }
            }

            $res_array = array();
            foreach ($researchers as $researcher) {
                $researcher_fn = $researcher['fn'];
                $researcher_mn = $researcher['mn'];
                $researcher_ln = $researcher['ln'];
                $res = mysql_query("SELECT researcher_id FROM researcher WHERE res_fname = '$researcher_fn' AND res_lname = '$researcher_ln' AND res_mname = '$researcher_mn'");
                if (mysql_num_rows($res) == 1) {
                    $researcher_id = mysql_fetch_array($res);  
                    array_push($res_array, $researcher_id[0]);
                } else {
                    mysql_query("INSERT INTO researcher (res_fname, res_lname, res_mname) VALUES('$researcher_fn', '$researcher_ln', '$researcher_mn')");
                    array_push($res_array, mysql_insert_id());
                }
            }

            $checkToRemove = mysql_query("SELECT tag_id FROM thesis_tags WHERE thesis_id = '$thesis_id'");
            while ($toRemove = mysql_fetch_assoc($checkToRemove)) {
                mysql_query("DELETE FROM thesis_tags WHERE thesis_id = '$thesis_id' AND tag_id = " . $toRemove['tag_id']);
            }

            $checkToRemove = mysql_query("SELECT researcher_id FROM thesis_researchers WHERE thesis_id = '$thesis_id'");
            while ($toRemove = mysql_fetch_assoc($checkToRemove)) {
                mysql_query("DELETE FROM thesis_researchers WHERE thesis_id = '$thesis_id' AND researcher_id = " . $toRemove['researcher_id']);
            }

            $checkToRemove = mysql_query("SELECT category_id FROM thesis_categories WHERE thesis_id = '$thesis_id'");
            while ($toRemove = mysql_fetch_assoc($checkToRemove)) {
                mysql_query("DELETE FROM thesis_categories WHERE thesis_id = '$thesis_id' AND category_id = " . $toRemove['category_id']);
            }

            foreach ($tag_array as $tag_id) {
                mysql_query("INSERT INTO thesis_tags VALUES ('$thesis_id', '$tag_id')");
            }

            foreach ($res_array as $res_id) {
                mysql_query("INSERT INTO thesis_researchers VALUES ('$thesis_id', '$res_id')");
            }

            foreach ($category as $cat) {
                mysql_query("INSERT INTO thesis_categories VALUES ('$thesis_id', '$cat')");
            }

            mysql_query("UPDATE thesis_department SET department_id = '$dept_id' WHERE thesis_id = '$thesis_id'");
            mysql_query("UPDATE thesis_faculty SET faculty_id = '$faculty_id' WHERE thesis_id = '$thesis_id'");

            $_SESSION['alert'] = '<em>' . $title . '</em> has been updated successfully.';
            header('Location: ../thesisInfo.php?id=' . $thesis_id);
        } else {
            $_SESSION['alert'] = 'It seems that there is a duplicate in title/abstract.';
            header('location: ../editEntry.php?id=' . $thesis_id);
        }
    } else {
        $_SESSION['alert'] = 'There are fields that are not provided.';
        header('location: ../addEntry.php');
    }

?>