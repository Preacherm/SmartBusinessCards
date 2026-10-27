<?php
/**
 * QuickCMS by Bylancer Technologies
 * @author Bylancer
 * @version Pro-1.0
 * @Updated Date: 12/Apr/2023
 * @Copyright 2015-23 Bylancer
 * @url https://bylancer.com/
 */
global $config,$link;
define("ROOTPATH", dirname(__DIR__));
define("APPPATH", ROOTPATH."/php/");

require_once ROOTPATH . '/includes/autoload.php';
require_once ROOTPATH . '/includes/lang/lang_'.$config['lang'].'.php';

sec_session_start();

if (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == "ajaxlogin") { ajaxlogin(); }
    if ($_REQUEST['action'] == "email_verify") { email_verify(); }
    if ($_REQUEST['action'] == "subscribeNewsletter") { subscribeNewsletter(); }
    if ($_REQUEST['action'] == "submitBlogComment") { submitBlogComment(); }
    if ($_REQUEST['action'] == "saveVCard") { saveVCard(); }
    if ($_REQUEST['action'] == "deleteVCard") { deleteVCard(); }

    die(0);
}

function ajaxlogin(){
    global $link;
    $loggedin = userlogin($_POST['username'], $_POST['password']);
    $result['success'] = false;
    $result['message'] = __("Error: Please try again.");
    if (!is_array($loggedin)) {
        $result['message'] = __("Username or Password not found");
    } elseif ($loggedin['status'] == 2) {
        $result['message'] = __("This account has been banned");
    } else {
        $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
        $user_id = preg_replace("/[^0-9]+/", "", $loggedin['id']); // XSS protection as we might print this value
        $_SESSION['user']['id'] = $user_id;
        $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $loggedin['username']); // XSS protection as we might print this value
        $_SESSION['user']['username'] = $username;
        $_SESSION['user']['login_string'] = hash('sha512', $loggedin['password'] . $user_browser);
        $_SESSION['user']['user_type'] = $loggedin['user_type'];
        update_lastactive();

        $result['success'] = true;
        $result['message'] = $link['DASHBOARD'];
    }
    die(json_encode($result));
}

function email_verify()
{
    global $config, $lang;

    if (checkloggedin()) {
        /*SEND CONFIRMATION EMAIL*/
        email_template("signup_confirm", $_SESSION['user']['id']);

        $respond = __("Sent");
        echo '<a class="button gray" href="javascript:void(0);">' . $respond . '</a>';
        die();

    } else {
        header("Location: " . $config['site_url'] . "login");
        exit;
    }
}

/**
 * Subscribe emails
 */
function subscribeNewsletter() {
    global $config;
    $_POST = validate_input($_POST);

    $result = array('success' => false, 'message' => __('Email address is not valid.'));

    /* Validate email */
    if($email = filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)) {


        $count = ORM::for_table($config['db']['pre'].'subscriber')
            ->where('email',$email)
            ->count();
        /* Check if email already exists */
        if(!$count) {
            /* Save email */
            $subscriber_insert = ORM::for_table($config['db']['pre'].'subscriber')->create();
            $subscriber_insert->email = $email;
            $subscriber_insert->joined = date('Y-m-d');
            $subscriber_insert->save();

            $result = array('success' => true, 'message' => __("Subscribed Successfully."));
        } else {
            $result = array('success' => false, 'message' => __('Email address is already subscribed.'));
        }

    }

    echo json_encode($result);
}

function submitBlogComment()
{
    global $config, $lang;
    $comment_error = $name = $email = $user_id = $comment = null;
    $result = array();
    $is_admin = '0';
    $is_login = false;
    if (checkloggedin()) {
        $is_login = true;
    }
    $avatar = $config['site_url'] . 'storage/profile/default_user.png';
    if (!($is_login || isset($_SESSION['admin']['id']))) {
        if (empty($_POST['user_name']) || empty($_POST['user_email'])) {
            $comment_error = __('All fields are required.');
        } else {
            $name = validate_input($_POST['user_name']);
            $email = validate_input($_POST['user_email']);

            $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
            if (!preg_match($regex, $email)) {
                $comment_error = __('This is not a valid email address.');
            }
        }
    } else if ($is_login && isset($_SESSION['admin']['id'])) {
        $commenting_as = 'admin';
        if (!empty($_POST['commenting-as'])) {
            if (in_array($_POST['commenting-as'], array('admin', 'user'))) {
                $commenting_as = $_POST['commenting-as'];
            }
        }
        if ($commenting_as == 'admin') {
            $is_admin = '1';
            $info = ORM::for_table($config['db']['pre'] . 'admins')->find_one($_SESSION['admin']['id']);
            $user_id = $_SESSION['admin']['id'];
            $name = $info['name'];
            $email = $info['email'];
            if (!empty($info['image'])) {
                $avatar = $config['site_url'] . 'storage/profile/' . $info['image'];
            }
        } else {
            $user_id = $_SESSION['user']['id'];
            $user_data = get_user_data(null, $user_id);
            $name = $user_data['name'];
            $email = $user_data['email'];
            if (!empty($user_data['image'])) {
                $avatar = $config['site_url'] . 'storage/profile/' . $user_data['image'];
            }
        }
    } else if ($is_login) {
        $user_id = $_SESSION['user']['id'];
        $user_data = get_user_data(null, $user_id);
        $name = $user_data['name'];
        $email = $user_data['email'];
        if (!empty($user_data['image'])) {
            $avatar = $config['site_url'] . 'storage/profile/' . $user_data['image'];
        }
    } else if (isset($_SESSION['admin']['id'])) {
        $is_admin = '1';
        $info = ORM::for_table($config['db']['pre'] . 'admins')->find_one($_SESSION['admin']['id']);
        $user_id = $_SESSION['admin']['id'];
        $name = $info['name'];
        $email = $info['email'];
        if (!empty($info['image'])) {
            $avatar = $config['site_url'] . 'storage/profile/' . $info['image'];
        }
    } else {
        $comment_error = __('Please login to post a comment.');
    }

    if (empty($_POST['comment'])) {
        $comment_error = __('All fields are required.');
    } else {
        $comment = validate_input($_POST['comment']);
    }

    $duplicates = ORM::for_table($config['db']['pre'] . 'blog_comment')
        ->where('blog_id', $_POST['comment_post_ID'])
        ->where('name', $name)
        ->where('email', $email)
        ->where('comment', $comment)
        ->count();

    if ($duplicates > 0) {
        $comment_error = __('Duplicate Comment: This comment is already exists.');
    }

    if (!$comment_error) {
        if ($is_admin) {
            $approve = '1';
        } else {
            if ($config['blog_comment_approval'] == 1) {
                $approve = '0';
            } else if ($config['blog_comment_approval'] == 2) {
                if ($is_login) {
                    $approve = '1';
                } else {
                    $approve = '0';
                }
            } else {
                $approve = '1';
            }
        }

        $blog_cmnt = ORM::for_table($config['db']['pre'] . 'blog_comment')->create();
        $blog_cmnt->blog_id = $_POST['comment_post_ID'];
        $blog_cmnt->user_id = $user_id;
        $blog_cmnt->is_admin = $is_admin;
        $blog_cmnt->name = $name;
        $blog_cmnt->email = $email;
        $blog_cmnt->comment = $comment;
        $blog_cmnt->created_at = date('Y-m-d H:i:s');
        $blog_cmnt->active = $approve;
        $blog_cmnt->parent = $_POST['comment_parent'];
        $blog_cmnt->save();

        $id = $blog_cmnt->id();
        $date = date('d, M Y');
        $approve_txt = '';
        if ($approve == '0') {
            $approve_txt = '<em><small>' . __('Comment is posted, wait for the reviewer to approve.') . '</small></em>';
        }

        $html = '<li id="li-comment-' . $id . '"';
        if ($_POST['comment_parent'] != 0) {
            $html .= 'class="children-2"';
        }
        $html .= '>
                   <div class="comments-box" id="comment-' . $id . '">
                        <div class="comments-avatar">
                            <img src="' . $avatar . '" alt="' . $name . '">
                        </div>
                        <div class="comments-text">
                            <div class="avatar-name">
                                <h5>' . $name . '</h5>
                                <span>' . $date . '</span>
                            </div>
                            ' . $approve_txt . '
                            <p>' . nl2br(stripcslashes($comment)) . '</p>
                        </div>
                    </div>
                </li>';

        $result['success'] = true;
        $result['html'] = $html;
        $result['id'] = $id;
    } else {
        $result['success'] = false;
        $result['error'] = $comment_error;
    }
    die(json_encode($result));
}

function saveVCard(){
    global $config, $link;
    $_POST = validate_input($_POST);
    $error = array();
    if(checkloggedin()) {
        if(!isset($_POST['vcard_template'])){
            $error[] = __("Choose vcard template");
        }
        if (empty($_POST['title'])) {
            $error[] = __("Title Required");
        }
        if (empty($_POST['slug'])) {
            $error[] = __("Slug Required");

        } else if (!preg_match('/^[a-z0-9]+(-?[a-z0-9]+)*$/i', $_POST['slug'])) {
            $error[] = __("Slug Invalid");
        } else {
            if (isset($_POST['id'])) {
                $count = ORM::for_table($config['db']['pre'] . 'vcards')
                    ->where('slug', $_POST['slug'])
                    ->where_not_equal('id', $_POST['id'])
                    ->count();
            }else{
                $count = ORM::for_table($config['db']['pre'] . 'vcards')
                    ->where('slug', $_POST['slug'])
                    ->count();
            }
            // check row exist
            if ($count) {
                $error[] = __("Slug not exist");
            } else if (in_array($config['site_url'] . $_POST['slug'], $link)) {
                $error[] = __("Slug not exist");
            }
        }

        if(empty($error)) {
            $MainFileName = null;
            $CoverFileName = null;
            $main_imageName = "";
            $cover_imageName = "";
            if (isset($_POST['id'])) {
                $orm = ORM::for_table($config['db']['pre'] . 'vcards')
                    ->where(array(
                        'id' => $_POST['id'],
                        'user_id' => $_SESSION['user']['id']
                    ));
                $vcard_count = $orm->count();
                if($vcard_count){
                    $vcards = $orm->find_one();
                    $main_imageName = $vcards['main_image'];
                    $cover_imageName = $vcards['cover_image'];
                }else{
                    $error[] = __("This vcard not belongs to you.");
                }
            }
        }

        if(empty($error)) {
            if(isset($_FILES['main_image']['name']) && $_FILES['main_image']['name'] != "") {
                $target_dir = ROOTPATH . "/storage/cards/logo/";
                $result = quick_file_upload('main_image',$target_dir);
                if($result['success']) {
                    $MainFileName = $result['file_name'];
                    resizeImage(150, $target_dir . $MainFileName, $target_dir . $MainFileName);
                    if (!empty($_POST['id'])){
                        // remove old image
                        if (!empty(trim($main_imageName)) && $main_imageName != "default.png") {
                            if (file_exists($target_dir . $main_imageName)) {
                                unlink($target_dir . $main_imageName);
                            }
                        }
                    }
                }else{
                    $error[] = $result['error'];
                }
            }
        }

        if(empty($error)) {
            if(isset($_FILES['cover_image']['name']) && $_FILES['cover_image']['name'] != "") {
                $target_dir = ROOTPATH . "/storage/cards/cover/";
                $result = quick_file_upload('cover_image',$target_dir);
                if($result['success']) {
                    $CoverFileName = $result['file_name'];
                    resizeImage(550, $target_dir . $CoverFileName, $target_dir . $CoverFileName);
                    if (!empty($_POST['id'])){
                        // remove old image
                        if (!empty(trim($main_imageName)) && $main_imageName != "default.png") {
                            if (file_exists($target_dir . $main_imageName)) {
                                unlink($target_dir . $main_imageName);
                            }
                        }
                    }
                }else{
                    $error[] = $result['error'];
                }
            }
        }

        if(empty($error)){
            $now = date("Y-m-d H:i:s");
            $details = null;
            if (!empty($_POST['card-details'])) {
                $_POST['card-details'] = array_values($_POST['card-details']);
                $details = json_encode($_POST['card-details'], JSON_UNESCAPED_UNICODE);
            }

            if (isset($_POST['id'])) {
                $vcards_update = ORM::for_table($config['db']['pre'] . 'vcards')->find_one($_POST['id']);
                $vcards_update->color = validate_input($_POST['color']);
                $vcards_update->slug = validate_input($_POST['slug']);
                $vcards_update->title = validate_input($_POST['title']);
                $vcards_update->sub_title = validate_input($_POST['sub_title']);
                $vcards_update->description = validate_input($_POST['description']);
                $vcards_update->details = $details;
                if ($MainFileName) {
                    $vcards_update->main_image = $MainFileName;
                }
                if ($CoverFileName) {
                    $vcards_update->cover_image = $CoverFileName;
                }

            } else {
                $vcards_update = ORM::for_table($config['db']['pre'] . 'vcards')->create();
                $vcards_update->user_id = validate_input($_SESSION['user']['id']);
                $vcards_update->color = validate_input($_POST['color']);
                $vcards_update->slug = validate_input($_POST['slug']);
                $vcards_update->title = validate_input($_POST['title']);
                $vcards_update->sub_title = validate_input($_POST['sub_title']);
                $vcards_update->description = validate_input($_POST['description']);
                $vcards_update->details = $details;
                $vcards_update->created_at = $now;
                if ($MainFileName) {
                    $vcards_update->main_image = $MainFileName;
                }
                if ($CoverFileName) {
                    $vcards_update->cover_image = $CoverFileName;
                }
            }
            if ($vcards_update->save()) {
                $id = $vcards_update->id();
                update_vcard_option($id, 'vcard_template', validate_input($_POST['vcard_template']));

                $status = "success";
                $message = __("Saved Successfully");
            }else{
                $status = "error";
                $message = __("Error: Please try again.");
            }

        }else{
            $status = "error";
            $message = implode('<br>', $error);
        }
    }else{
        $status = "error";
        $message = __("Error: please login.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();

}

function deleteVCard(){
    global $config;

    if(isset($_POST['id']) && checkloggedin()) {
        $id = $_POST['id'];
    }else{
        $result = array('success' => false, 'message' => __("Invalid ID."));
    }

    if(get_option('demo_mode',0)) {
        /*$vcards = ORM::for_table($config['db']['pre'] . 'vcards')
            ->select_many('main_image', 'cover_image')
            ->where_id_is($id);
        foreach ($vcards->find_array() as $row) {
            $logo_dir = ROOTPATH . "/storage/cards/logo/";
            $cover_dir = ROOTPATH . "/storage/cards/cover/";
            $main_image = $row['main_image'];
            $cover_image = $row['cover_image'];
            // delete logo
            if (trim($main_image) != "" && $main_image != "default.png") {
                $file = $logo_dir . $main_image;
                if (file_exists($file))
                    unlink($file);
            }
            // delete cover
            if (trim($cover_image) != "" && $cover_image != "default.png") {
                $file = $cover_dir . $cover_image;
                if (file_exists($file))
                    unlink($file);
            }
        }
        // delete Vcards
        $vcards->delete_many();*/

        $result = array('success' => true, 'message' => __('Deleted successfully.'));
    }else{
        $result = array('success' => false, 'message' => __('This function is disable for demo.'));
    }

    echo json_encode($result);
    die();
}