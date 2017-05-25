<?php
require_once("include/checklogin.php");
require_once("include/session.php");
require_once("include/db_connect.php");

class transactionCoordinatorHelper {

    function transactionCoordinatorHelper() {
        global $session;
        /* Make sure administrator is accessing page */
        if (!$session->isAdmin() && !$session->isMaster()) {
            header("Location: index.php");
            return;
        }
        /* Admin submitted update user level form */
        if (isset($_POST['add_contractpaper_work'])) {
            $this->addContractPaperWork();
            exit;
        }
        elseif( isset($_POST['update_task']) ) {
            $this->updateTask();
            exit;
        }
        else if( isset($_GET['del_paperwork']) ) {
            if($_GET['del_paperwork'] == 1) {
                $this->deleteAttachedPaperwork();
                exit;
            }
        }

        /* Should not get here, redirect to home page */ else {
            header("Location: index.php");
        }
    }

    function addContractPaperWork() {
        global $session, $database, $form;

        $data = $_POST;
        $file = $_FILES;
        if (isset($data["submit_file"]) || isset($data['add_contractpaper_work'])) {

            header('Content-Type: text/plain; charset=utf-8');

            if(isset($file['fileToUpload'])){

                $errors      = array();
                $file_name = $file['fileToUpload']['name'];
                $file_size = $file['fileToUpload']['size'];
                $file_tmp  = $file['fileToUpload']['tmp_name'];
                $file_type = $file['fileToUpload']['type'];
                $file_err  = $file['fileToUpload']['error'];
                $file_title = $data['file_title'];
                $lead_id   = $data['lead_id'];

                if(isset($file_err) && $file_err != 0) {
                    $errors[] = 'Error uploading file..';
                }

                if(!isset($file_title) || $file_title == '') {
                    $errors[] = 'File Name must not be null..';
                }

                if(empty($errors)==true) {
                    move_uploaded_file($file_tmp,"files/contract_paperworks/".strtolower($file_name));
                    //After upload save file to database
                    global $session, $database, $form;          
                    $q = "INSERT INTO lead_attachments (
                                            type, lead_id, title, filename, date_uploaded
                                        )VALUES(
                                            2,
                                            " . $lead_id . ",
                                            '" . stripslashes(str_replace('\r\n', ' ', $_POST['file_title'])) . "',
                                            '" . stripslashes(str_replace('\r\n', ' ', strtolower($file_name))) . "',
                                            '" . stripslashes(str_replace('\r\n', ' ', date("Y-m-d H:i:s"))) . "')";  

                    $result = $database->query($q); 
                    $_SESSION['TEMP_VAR']['CONTRACT_PAPERWORK']['MESSAGE'] = 'Successfully add contract paper work.';                  
                    header("Location: transactionCoordinator.php?lead_id=" . $lead_id);
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

    function updateTask() {
        global $session, $database, $form;

        $data = $_POST;
        if (isset($data["update_task"]) || isset($data['update_task_button'])) {

            $s_data = serialize($data['task']);

            $result_count = $database->query("SELECT COUNT(id) as total FROM lead_trans_task_list WHERE lead_id = " .$data['lead_id']. " ORDER BY id DESC LIMIT 1") or die(mysql_error());
            $row_data     = mysqli_fetch_array($result_count);

            if($row_data['total'] <= 0 ) {
                //Insert New Record
                $q = "INSERT INTO lead_trans_task_list (
                                        lead_id, task_list, roof_ins_claim, combo, date_created
                                    )VALUES(
                                        " . $data['lead_id'] . ",
                                        '" . stripslashes(str_replace('\r\n', ' ', $s_data)) . "',
                                        '" . stripslashes(str_replace('\r\n', ' ', $data['roof_ins_claim'])) . "',
                                        '" . stripslashes(str_replace('\r\n', ' ', $data['combo'])) . "',
                                        '" . stripslashes(str_replace('\r\n', ' ', date("Y-m-d"))) . "')";  

                $result = $database->query($q);                 
                $_SESSION['TEMP_VAR']['UPDATE_TASK']['MESSAGE'] = 'Successfully Update Task Textbox.';
                header("Location: transactionCoordinator.php?lead_id=" . $data['lead_id']);   
                exit;
            } else {
                //Update Exiting Record'
                $s_data = serialize($data['task']);
                $q = "UPDATE lead_trans_task_list SET 
                            task_list= '".stripslashes(str_replace('\r\n', ' ', $s_data))."',
                            roof_ins_claim= '".stripslashes(str_replace('\r\n', ' ', $data['roof_ins_claim']))."',
                            combo= '".stripslashes(str_replace('\r\n', ' ', $data['combo']))."'
                        WHERE lead_id= ".$data['lead_id']." ";

                $result = $database->query($q); 
                $_SESSION['TEMP_VAR']['UPDATE_TASK']['MESSAGE'] = 'Successfully Update Task Textbox.';
                header("Location: transactionCoordinator.php?lead_id=" . $data['lead_id']);   
                exit;
            }

        }

    }

    function deleteAttachedPaperwork() {
        global $session, $database, $form;

        unlink('files/contract_paperworks/'. $_GET['file']);

        $del = "DELETE FROM lead_attachments WHERE id = ". $_GET['attach_id'] ." ";  
        $result = $database->query($del);
        $_SESSION['TEMP_VAR']['CONTRACT_PAPERWORK_DEL']['MESSAGE'] = 'Successfully delete contract paper work.';  
        header("Location: transactionCoordinator.php?lead_id=" . $_GET['lead_id']);   
        
        exit;
    }

}

/* Initialize process */
$transactionCoordinatorHelper = new transactionCoordinatorHelper;
?>
