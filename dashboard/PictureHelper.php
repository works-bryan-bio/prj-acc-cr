<?php
require_once("include/checklogin.php");
require_once("include/session.php");
require_once("include/db_connect.php");

class pictureHelper {

    function pictureHelper() {
        global $session;
        /* Make sure administrator is accessing page */
        if (!$session->isAdmin() && !$session->isMaster()) {
            header("Location: index.php");
            return;
        }
        /* Admin submitted update user level form */
        if (isset($_POST['add_picture'])) {
            $this->addPicture();
            exit;
        }
        else if( isset($_GET['del_pic']) ) {
            if($_GET['del_pic'] == 1) {
                $this->deletePic();
                exit;
            }
        }

        /* Should not get here, redirect to home page */ else {
            header("Location: Pictures.php");
        }
    }

    function addPicture() {
        global $session, $database, $form;

        $data = $_POST;
        $file = $_FILES;

        if (isset($data["submit_file"]) || isset($data['add_picture'])) {

            header('Content-Type: text/plain; charset=utf-8');

            if(isset($file['fileToUpload'])){

                $errors      = array();
                $file_name = $data['lead_id'] . "_" . $file['fileToUpload']['name'];
                $file_size = $file['fileToUpload']['size'];
                $file_tmp  = $file['fileToUpload']['tmp_name'];
                $file_type = $file['fileToUpload']['type'];
                $file_err  = $file['fileToUpload']['error'];
                $lead_id   = $data['lead_id'];

                if(isset($file_err) && $file_err != 0) {
                    $errors[] = 'Error uploading file..';
                }

                if(empty($errors)==true) {
                    move_uploaded_file($file_tmp,"files/lead_img/".$data['folder']."/".strtolower($file_name));
                    //After upload save file to database
                    global $session, $database, $form;          
                    $q = "INSERT INTO lead_pictures (
                                            lead_id, folder, title, filename, date_uploaded
                                        )VALUES(
                                            " . $lead_id . ",
                                            '" . $data['folder'] . "',
                                            '" . stripslashes(str_replace('\r\n', ' ', strtolower($file_name))) . "',
                                            '" . stripslashes(str_replace('\r\n', ' ', strtolower($file_name))) . "',
                                            '" . stripslashes(str_replace('\r\n', ' ', date("Y-m-d H:i:s"))) . "')";  

                    $result = $database->query($q); 
                    $_SESSION['TEMP_VAR']['LEAD_PICTURE']['MESSAGE'] = 'Successfully add Image.';                  
                    header("Location: Pictures.php?lead_id=" . $lead_id . "&folder=". $data['folder']);
                    exit;
                }else{
                 print_r($errors);
                 exit;
                }
            }   

            header("Location: index.php");
        } else {
            $form->setError("", "Cannot save record<br>");
            header("Location: " . $session->referrer);                
        }

    }

    function deletePic() {
        global $session, $database, $form;

        unlink('files/lead_img/'. $_GET['folder'] . '/' . $_GET['file']);

        $del = "DELETE FROM lead_pictures WHERE id = ". $_GET['pic_id'] ." ";  
        $result = $database->query($del);
        $_SESSION['TEMP_VAR']['LEAD_PICTURE_DEL']['MESSAGE'] = 'Successfully delete image.';  
        header("Location: Pictures.php?lead_id=" . $_GET['lead_id'] . "&folder=". $_GET['folder']);
        exit;
    }

}

/* Initialize process */
$pictureHelper = new pictureHelper;
?>
