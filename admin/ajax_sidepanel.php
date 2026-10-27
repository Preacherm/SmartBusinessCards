<?php
require_once('includes.php');
checkloggedadmin();

if (!isset($_SESSION['admin']['id'])) {
    exit('Access Denied.');
}

//SidePanel Ajax Function
if(isset($_REQUEST['action'])){
    if(!check_allow()){
        $status = "Sorry:";
        $message = "permission denied for demo.";
        echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
        die();
    }
    if ($_REQUEST['action'] == "addEditAdmin") { addEditAdmin(); }
    if ($_REQUEST['action'] == "addEditUser") { addEditUser(); }
    if ($_REQUEST['action'] == "addEditSubscriber") { addEditSubscriber(); }
    if ($_REQUEST['action'] == "addEditCurrency") { addEditCurrency(); }
    if ($_REQUEST['action'] == "addEditTimezone") { addEditTimezone(); }
    if ($_REQUEST['action'] == "addEditTestimonial") { addEditTestimonial(); }
    if ($_REQUEST['action'] == "addEditTax") { addEditTax(); }
    if ($_REQUEST['action'] == "addLanguage") { addLanguage(); }
    if ($_REQUEST['action'] == "editLanguage") { editLanguage(); }
    if ($_REQUEST['action'] == "addMembershipPlan") { addMembershipPlan(); }
    if ($_REQUEST['action'] == "editMembershipPlan") { editMembershipPlan(); }
    if ($_REQUEST['action'] == "addStaticPage") { addStaticPage(); }
    if ($_REQUEST['action'] == "editStaticPage") { editStaticPage(); }
    if ($_REQUEST['action'] == "addFAQentry") { addFAQentry(); }
    if ($_REQUEST['action'] == "editFAQentry") { editFAQentry(); }
    if ($_REQUEST['action'] == "transactionEdit") { transactionEdit(); }
    if ($_REQUEST['action'] == "paymentEdit") { paymentEdit(); }
    if ($_REQUEST['action'] == "addBlogCat") { addBlogCat(); }
    if ($_REQUEST['action'] == "editBlogCat") { editBlogCat(); }
    if ($_REQUEST['action'] == "saveEmailTemplate") { saveEmailTemplate(); }
    if ($_REQUEST['action'] == "testEmailTemplate") { testEmailTemplate(); }

    if ($_GET['action'] == "SaveSettings") { SaveSettings(); }
}

function change_config_file_settings($filePath, $newSettings,$lang){
    // Update $fileSettings with any new values
    $fileSettings = array_merge($lang, $newSettings);
    // Build the new file as a string
    $newFileStr = "<?php\n";
    foreach ($fileSettings as $name => $val) {
        // Using var_export() allows you to set complex values such as arrays and also
        // ensures types will be correct
        $newFileStr .= "\$lang['$name'] = " . var_export($val, true) . ";\n";
    }
    // Closing tag intentionally omitted, you can add one if you want

    // Write it back to the file
    file_put_contents($filePath, $newFileStr);
}

function editAdvertise(){
    global $config;

    if (isset($_POST['id'])) {
        $update_adsense = ORM::for_table($config['db']['pre'].'adsense')->find_one($_POST['id']);
        $update_adsense->set('provider_name', $_POST['provider_name']);
        $update_adsense->set('status', $_POST['status']);
        $update_adsense->set('large_track_code', $_POST['large_track_code']);
        $update_adsense->set('tablet_track_code', $_POST['tablet_track_code']);
        $update_adsense->set('phone_track_code', $_POST['phone_track_code']);
        $update_adsense->save();

        $status = "success";
        $message = __("Saved Successfully");

    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addEditAdmin() {
    global $config;
    $_POST = validate_input($_POST);

    $image = null;
    $error = array();
    $name_length = strlen(utf8_decode($_POST['name']));

    if(empty($_POST["name"])) {
        $error[] = __("Enter your full name.");
    }
    elseif( ($name_length < 4) OR ($name_length > 21) ) {
        $error[] = __("Name must be between 4 and 20 characters long.");
    }

    if(empty($_POST['username'])){
        $error[] = __('Username is required');
    }elseif(isset($_POST['id'])){
        $count = ORM::for_table($config['db']['pre'].'admins')
            ->where('username', $_POST['username'])
            ->where_not_equal('id', $_POST['id'])
            ->count();
        if($count) {
            $error[] = __('Username is already available');
        }
    }

    // Check if this is an Email availability
    $_POST["email"] = strtolower($_POST["email"]);
    $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';

    if(empty($_POST["email"])) {
        $error[] = __("Please enter an email address");
    }
    elseif(!preg_match($regex, $_POST['email'])) {
        $error[] = __("This is not a valid email address");
    }
    elseif(isset($_POST['id'])){
        $count = ORM::for_table($config['db']['pre'].'admins')
            ->where('email', $_POST['email'])
            ->where_not_equal('id', $_POST['id'])
            ->count();
        if($count) {
            $error[] = __("An account already exists with that e-mail address");
        }
    }

    if(empty($error)) {
        if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $target_dir = ROOTPATH . "/storage/profile/";
            $result = quick_file_upload('image',$target_dir);
            if($result['success']) {
                $image = $result['file_name'];
                resizeImage(100, $target_dir . $image, $target_dir . $image);
                if (!empty($_POST['id'])){
                    // remove old image
                    $info = ORM::for_table($config['db']['pre'].'admins')
                        ->select('image')
                        ->find_one($_POST['id']);

                    if (!empty(trim($info['image'])) && $info['image'] != "default_user.png") {
                        if (file_exists($target_dir . $info['image'])) {
                            unlink($target_dir . $info['image']);
                        }
                    }
                }
            }else{
                $error[] = $result['error'];
            }
        }
    }

    if (empty($error)) {
        if (isset($_POST['id'])) {
            $admins = ORM::for_table($config['db']['pre'].'admins')->find_one($_POST['id']);
            $admins->name = validate_input($_POST['name']);
            $admins->username = validate_input($_POST['username']);
            $admins->email = validate_input($_POST['email']);
            if (!empty($_POST['password'])) {
                $pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT, ['cost' => 13]);
                $admins->password_hash = $pass_hash;
            }
            if (!empty($image)) {
                $admins->image = $image;
            }
            $admins->save();
        } else {
            $password = $_POST["password"];
            $pass_hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 13]);

            $admins = ORM::for_table($config['db']['pre'].'admins')->create();
            $admins->name = validate_input($_POST['name']);
            $admins->username = validate_input($_POST['username']);
            $admins->email = validate_input($_POST['email']);
            $admins->password_hash = $pass_hash;
            if (!empty($image)) {
                $admins->image = $image;
            }
            $admins->save();
        }

        if ($admins->id()) {
            $status = "success";
            $message = __("Saved Successfully");
        } else{
            $status = "error";
            $message = __("Error: Please try again.");
        }

    } else {
        $status = "error";
        $message = implode('<br>', $error);
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addEditUser(){
    global $config;
    $error = array();
    $image = null;
    $now = date("Y-m-d H:i:s");
    if(!isset($_POST['user_type'])){
        $_POST['user_type'] = "user";
    }

    $name_length = strlen(($_POST['name']));

    if(empty($_POST["name"])) {
        $error[] = __("Enter your full name.");
    }
    elseif( ($name_length < 4) OR ($name_length > 21) ) {
        $error[] = __("Name must be between 4 and 20 characters long.");
    }

    if(empty($_POST['username'])){
        $error[] = __('Username is required');
    }elseif(!isset($_POST['id'])){
        $count = check_username_exists($_POST["username"]);
        if($count) {
            $error[] = __('Username is already available');
        }
    }

    // Check if this is an Email availability
    $_POST["email"] = strtolower($_POST["email"]);
    $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';

    if(empty($_POST["email"])) {
        $error[] = __("Please enter an email address");
    }
    elseif(!preg_match($regex, $_POST['email'])) {
        $error[] = __("This is not a valid email address");
    }
    elseif(!isset($_POST['id'])){
        $count = check_account_exists($_POST["email"]);
        if($count) {
            $error[] = __("An account already exists with that e-mail address");
        }
    }

    if (empty($error)) {
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $target_dir = ROOTPATH . "/storage/profile/";
            $result = quick_file_upload('image', $target_dir);
            if ($result['success']) {
                $image = $result['file_name'];
                resizeImage(100, $target_dir . $image, $target_dir . $image);
                if (isset($_POST['id'])) {
                    // remove old image
                    $info = ORM::for_table($config['db']['pre'] . 'user')
                        ->select('image')
                        ->find_one($_POST['id']);

                    if (!empty(trim($info['image'])) && $info['image'] != "default_user.png") {
                        if (file_exists($target_dir . $info['image'])) {
                            unlink($target_dir . $info['image']);
                        }
                    }
                }
            } else {
                $error[] = $result['error'];
            }
        }
    }

    if(empty($error)){
        if (isset($_POST['id'])) {
            /* Update plan */
            $subsc_check = ORM::for_table($config['db']['pre'].'upgrades')
                ->where('user_id', $_POST['id'])
                ->count();
            if($_POST['current_plan'] != 'free'){
                $expires = strtotime($_POST['plan_expiration_date']);
                if($subsc_check == 1){
                    $upgrades = ORM::for_table($config['db']['pre'].'upgrades')
                        ->use_id_column('upgrade_id')
                        ->where('user_id', validate_input($_POST['id']))
                        ->find_one();
                    $upgrades->sub_id = validate_input($_POST['current_plan']);
                    $upgrades->upgrade_expires = validate_input($expires);
                    $upgrades->save();

                }else{
                    $upgrades_insert = ORM::for_table($config['db']['pre'].'upgrades')->create();
                    $upgrades_insert->sub_id = $_POST['current_plan'];
                    $upgrades_insert->user_id = $_POST['id'];
                    $upgrades_insert->upgrade_lasttime = time();
                    $upgrades_insert->upgrade_expires = $expires;
                    $upgrades_insert->status = "Active";
                    $upgrades_insert->save();
                }
            }else{
                ORM::for_table($config['db']['pre'].'upgrades')
                    ->where_equal('user_id', $_POST['id'])
                    ->delete_many();
            }

            $users = ORM::for_table($config['db']['pre'].'user')->find_one($_POST['id']);
            $users->group_id = validate_input($_POST['current_plan']);
            $users->status = validate_input($_POST['status']);
            $users->name = validate_input($_POST['name']);
            $users->username = validate_input($_POST['username']);
            $users->user_type = validate_input($_POST['user_type']);
            $users->email = validate_input($_POST['email']);
            $users->sex = validate_input($_POST['sex']);
            $users->description = validate_input($_POST['about'],true);
            $users->country = validate_input($_POST['country']);
            if (!empty($_POST['password'])) {
                $pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT, ['cost' => 13]);
                $users->password_hash = $pass_hash;
            }
            if (!empty($image)) {
                $users->image = $image;
            }
            $users->created_at = $now;
            $users->updated_at = $now;
            $users->save();

        } else {
            $password = $_POST["password"];
            $pass_hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 13]);

            $confirm_id = get_random_id();

            $users = ORM::for_table($config['db']['pre'].'user')->create();
            $users->status = '0';
            $users->name = validate_input($_POST['name']);
            $users->username = validate_input($_POST['username']);
            $users->user_type = validate_input($_POST['user_type']);
            $users->email = validate_input($_POST['email']);
            $users->sex = validate_input($_POST['sex']);
            $users->description = validate_input($_POST['about'],true);
            $users->country = validate_input($_POST['country']);
            $users->confirm = $confirm_id;
            $users->password_hash = $pass_hash;
            if (!empty($image)) {
                $users->image = $image;
            }
            $users->created_at = $now;
            $users->updated_at = $now;
            $users->save();
        }

        if ($users->id()) {
            $status = "success";
            $message = __("Saved Successfully");
        } else{
            $status = "error";
            $message = __("Error: Please try again.");
        }
    }else {
        $status = "error";
        $message = implode('<br>', $error);
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addEditSubscriber() {
    global $config;
    $_POST = validate_input($_POST);

    if(isset($_POST['id']) && $_POST['id'] != ""){
        $subscriber_update = ORM::for_table($config['db']['pre'].'subscriber')->find_one($_POST['id']);
        $subscriber_update->set('email', $_POST['email']);
        $subscriber_update->set('joined', date('Y-m-d'));
        $subscriber_update->save();
    }else{
        /* Save email */
        $subscriber_insert = ORM::for_table($config['db']['pre'].'subscriber')->create();
        $subscriber_insert->email = $_POST['email'];
        $subscriber_insert->joined = date('Y-m-d');
        $subscriber_insert->save();
    }

    $result = array('status' => 'success', 'message' => __('Saved Successfully.'));

    echo json_encode($result);
}

function addEditCurrency(){
    global $config;
    $_POST = validate_input($_POST);
    $in_left = $_POST['in_left'];
    $decimal_places = ($_POST['decimal_places'] != "")? $_POST['decimal_places'] : 2;
    if(strlen($_POST['code']) > 3){
        $status = "error";
        $message = __('Currecny code max length 3.');
        echo '{"status" : "' . $status . '","message" : "' . $message . '"}';
        die();
    }

    if (isset($_POST['id'])) {
        $update_currency = ORM::for_table($config['db']['pre'].'currencies')->find_one($_POST['id']);
        $update_currency->set('name', $_POST['name']);
        $update_currency->set('code', $_POST['code']);
        $update_currency->set('html_entity', $_POST['html_entity']);
        $update_currency->set('font_arial', $_POST['font_arial']);
        $update_currency->set('font_code2000', $_POST['font_code2000']);
        $update_currency->set('unicode_decimal', $_POST['unicode_decimal']);
        $update_currency->set('unicode_hex', $_POST['unicode_hex']);
        $update_currency->set('decimal_places', $decimal_places);
        $update_currency->set('decimal_separator', $_POST['decimal_separator']);
        $update_currency->set('thousand_separator', $_POST['thousand_separator']);
        $update_currency->set('in_left', $in_left);
        $update_currency->save();
    } else {
        $insert_currency = ORM::for_table($config['db']['pre'].'currencies')->create();
        $insert_currency->name = $_POST['name'];
        $insert_currency->code = $_POST['code'];
        $insert_currency->html_entity = $_POST['html_entity'];
        $insert_currency->font_arial = $_POST['font_arial'];
        $insert_currency->font_code2000 = $_POST['font_code2000'];
        $insert_currency->unicode_decimal = $_POST['unicode_decimal'];
        $insert_currency->unicode_hex = $_POST['unicode_hex'];
        $insert_currency->decimal_places = $decimal_places;
        $insert_currency->decimal_separator = $_POST['decimal_separator'];
        $insert_currency->thousand_separator = $_POST['thousand_separator'];
        $insert_currency->in_left = $in_left;
        $insert_currency->save();
    }
    $status = "success";
    $message = __("Saved Successfully");

    echo '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addEditTimezone(){
    global $config;

    $_POST = validate_input($_POST);
    if($_POST['time_zone_id'] == ""){
        $status = "error";
        $message = __('Please fill the required fields.');
        echo '{"status" : "' . $status . '","message" : "' . $message . '"}';
        die();
    }
    $gmt = ($_POST['gmt'] != "")? $_POST['gmt'] : 0;
    $dst = ($_POST['dst'] != "")? $_POST['dst'] : 0;
    $raw = ($_POST['raw'] != "")? $_POST['raw'] : 0;

    if (isset($_POST['id'])) {
        $timezones = ORM::for_table($config['db']['pre'].'time_zones')->find_one($_POST['id']);
        $timezones->set('country_code', $_POST['country_code']);
        $timezones->set('time_zone_id', $_POST['time_zone_id']);
        $timezones->set('gmt', $gmt);
        $timezones->set('dst', $dst);
        $timezones->set('raw', $raw);
        $timezones->save();

    } else {
        $timezones = ORM::for_table($config['db']['pre'].'time_zones')->create();
        $timezones->country_code = $_POST['country_code'];
        $timezones->time_zone_id = $_POST['time_zone_id'];
        $timezones->gmt = $gmt;
        $timezones->dst = $dst;
        $timezones->raw = $raw;
        $timezones->save();
    }

    if ($timezones->id()) {
        $status = "success";
        $message = __("Saved Successfully");
    } else{
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addEditTestimonial(){
    global $config;

    $_POST = validate_input($_POST);

    $title = $_POST['name'];
    $designation = $_POST['designation'];
    $description = $_POST['content'];

    $image = null;
    $error = array();

    if(empty($title)){
        $error[] = __("Name is required.");
    }
    if(empty($designation)){
        $error[] = __("Designation is required.");
    }
    if(empty($description)){
        $error[] = __("Content is required.");
    }

    if (empty($error)) {
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $target_dir = ROOTPATH . "/storage/testimonials/";
            $result = quick_file_upload('image', $target_dir);
            if ($result['success']) {
                $image = $result['file_name'];
                resizeImage(100, $target_dir . $image, $target_dir . $image);
                if (isset($_POST['id'])) {
                    // remove old image
                    $info = ORM::for_table($config['db']['pre'] . 'testimonials')
                        ->select('image')
                        ->find_one($_POST['id']);

                    if (!empty(trim($info['image'])) && $info['image'] != "default_user.png") {
                        if (file_exists($target_dir . $info['image'])) {
                            unlink($target_dir . $info['image']);
                        }
                    }
                }
            } else {
                $error[] = $result['error'];
            }
        }
    }

    if (empty($error)) {
        if(isset($_POST['id'])){
            $test = ORM::for_table($config['db']['pre'].'testimonials')->find_one($_POST['id']);
            $test->name = $title;
            $test->designation = $designation;
            $test->content = $description;
            if($image){
                $test->image = $image;
            }
            $test->save();
        } else {
            $test = ORM::for_table($config['db']['pre'].'testimonials')->create();
            $test->name = $title;
            $test->designation = $designation;
            $test->image = $image;
            $test->content = $description;
            $test->save();
        }

        $status = "success";
        $message = __("Saved Successfully");

        echo '{"status" : "' . $status . '","message" : "' . $message . '"}';
        die();
    } else {
        $status = "error";
        $message = implode('<br>', $error);
    }
    $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    echo $json;
    die();
}

function addEditTax(){
    global $config;

    $_POST = validate_input($_POST);

    if (isset($_POST['submit'])) {

        if ($_POST['internal_name'] == "") {
            echo $json = '{"status" : "error","message" : "' . __('Please fill the required fields.') . '"}';
            die();
        }
        if ($_POST['name'] == "") {
            echo $json = '{"status" : "error","message" : "' . __('Please fill the required fields.') . '"}';
            die();
        }
        if ($_POST['description'] == "") {
            echo $json = '{"status" : "error","message" : "' . __('Please fill the required fields.') . '"}';
            die();
        }
        if ($_POST['value'] == "") {
            echo $json = '{"status" : "error","message" : "' . __('Please fill the required fields.') . '"}';
            die();
        }

        if (isset($_POST['id'])) {
            $taxes = ORM::for_table($config['db']['pre'].'taxes')->find_one($_POST['id']);
            $taxes->internal_name = validate_input($_POST['internal_name']);
            $taxes->name = validate_input($_POST['name']);
            $taxes->description = validate_input($_POST['description']);
            $taxes->value = validate_input($_POST['value']);
            $taxes->value_type = validate_input($_POST['value_type']);
            $taxes->type = validate_input($_POST['type']);
            $taxes->billing_type = validate_input($_POST['billing_type']);
            $taxes->countries = isset($_POST['countries'])? validate_input(implode(',',$_POST['countries'])) : null;
            $taxes->save();
        } else {
            $taxes = ORM::for_table($config['db']['pre'].'taxes')->create();
            $taxes->internal_name = validate_input($_POST['internal_name']);
            $taxes->name = validate_input($_POST['name']);
            $taxes->description = validate_input($_POST['description']);
            $taxes->value = validate_input($_POST['value']);
            $taxes->value_type = validate_input($_POST['value_type']);
            $taxes->type = validate_input($_POST['type']);
            $taxes->billing_type = validate_input($_POST['billing_type']);
            $taxes->countries = isset($_POST['countries'])? validate_input(implode(',',$_POST['countries'])) : null;
            $taxes->datetime = date('Y-m-d H:i:s');
            $taxes->save();
        }

        if ($taxes->id()) {
            $status = "success";
            $message = __("Saved Successfully");
        } else{
            $status = "error";
            $message = __("Error: Please try again.");
        }

    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addLanguage(){
    global $config,$lang;
    $_POST = validate_input($_POST);

    if (isset($_POST['submit'])) {
        if(isset($_POST['name']) && $_POST['name'] != ""){

            $post_langname = str_replace(' ', '', $_POST['name']);
            $post_filename = str_replace(' ', '', strtolower($_POST['file_name']));

            $filePath = '../includes/lang/lang_'.$post_filename.'.php';
            if (!file_exists($filePath)) {
                $source = 'en';
                $target = $_POST['code'];
                $trans = new GoogleTranslate();
                $newLangArray = array();
                foreach ($lang as $key => $value)
                {
                    if($_POST['auto_tran'] == 1){
                        $result = $trans->translate($source, $target, $value);
                        $result = !empty($result)?$result:$value;
                    }else{
                        $result = $value;
                    }

                    $newLangArray[$key] = $result;
                }
                fopen($filePath, "w");
                change_config_file_settings($filePath, $newLangArray,$lang);

                $insert_language = ORM::for_table($config['db']['pre'].'languages')->create();
                $insert_language->code = $_POST['code'];
                $insert_language->name = $post_langname;
                $insert_language->direction = $_POST['direction'];
                $insert_language->file_name = $post_filename;
                $insert_language->active = $_POST['active'];
                $insert_language->save();

                if ($insert_language->id()) {
                    $status = "success";
                    $message = __("Saved Successfully");
                } else{
                    $status = "error";
                    $message = __("Error: Please try again.");
                }


            } else {
                $message = __("Same language file is exist. Change language file name.");
                echo $json = '{"status" : "error","message" : "' . $message . '"}';
                die();
            }
        }else{
            $status = "error";
            $message = __("Error: Please try again.");
        }

    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function editLanguage(){
    global $config;
    $_POST = validate_input($_POST);
    if (isset($_POST['id'])) {

        $update_language = ORM::for_table($config['db']['pre'].'languages')->find_one($_POST['id']);
        $update_language->set('code', $_POST['code']);
        $update_language->set('name', $_POST['name']);
        $update_language->set('direction', $_POST['direction']);
        $update_language->set('active', $_POST['active']);
        $update_language->save();

        if ($update_language) {
            $status = "success";
            $message = __("Saved Successfully");
        } else{
            $status = "error";
            $message = __("Error: Please try again.");
        }


    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addMembershipPlan(){
    global $config;
    $_POST = validate_input($_POST);
    if (isset($_POST['submit'])) {
        if ($_POST['name'] == "") {
            echo $json = '{"status" : "error","message" : "' . __('Please fill the required fields.') . '"}';
            die();
        }
        if ($_POST['monthly_price'] == "") {
            echo $json = '{"status" : "error","message" : "' . __('Please fill the required fields.') . '"}';
            die();
        }
        if ($_POST['annual_price'] == "") {
            echo $json = '{"status" : "error","message" : "' . __('Please fill the required fields.') . '"}';
            die();
        }
        if ($_POST['lifetime_price'] == "") {
            echo $json = '{"status" : "error","message" : "' . __('Please fill the required fields.') . '"}';
            die();
        }

        $recommended = ($_POST['recommended'] == 1) ? "yes" : "no";
        $active = $_POST['active'];

        $settings = array(
            'field_limit' => (int) $_POST['field_limit'],
            'scan_limit' =>(int) $_POST['scan_limit'],
            'hide_branding' => (int) $_POST['hide_branding'],
            'custom' => array()
        );

        $plan_custom = ORM::for_table($config['db']['pre'].'plan_options')
            ->where('active', 1)
            ->order_by_asc('position')
            ->find_many();
        foreach ($plan_custom as $custom){
            if(!empty($custom['title']) && trim($custom['title']) != '' && !empty($_POST['custom_'.$custom['id']])) {
                $settings['custom'][$custom['id']] = 1;
            }
        }

        $insert_subscription = ORM::for_table($config['db']['pre'].'plans')->create();
        $insert_subscription->name = validate_input($_POST['name']);
        $insert_subscription->badge = $_POST['badge'];
        $insert_subscription->monthly_price = $_POST['monthly_price'];
        $insert_subscription->annual_price = $_POST['annual_price'];
        $insert_subscription->lifetime_price = $_POST['lifetime_price'];
        $insert_subscription->settings = json_encode($settings);
        $insert_subscription->taxes_ids = isset($_POST['taxes'])? validate_input(implode(',',$_POST['taxes'])) : null;
        $insert_subscription->status = $active;
        $insert_subscription->recommended = $recommended;
        $insert_subscription->date = date('Y-m-d H:i:s');
        $insert_subscription->save();

        if ($insert_subscription->id()) {
            $status = "success";
            $message = __("Saved Successfully");
        } else{
            $status = "error";
            $message = __("Error: Please try again.");
        }

    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function editMembershipPlan()
{
    global $config;
    $_POST = validate_input($_POST);
    if (isset($_POST['submit'])) {
        if ($_POST['name'] == "") {
            echo $json = '{"status" : "error","message" : "' . __('Please fill the required fields.') . '"}';
            die();
        }

        $settings = array(
            'field_limit' => (int) $_POST['field_limit'],
            'scan_limit' =>(int) $_POST['scan_limit'],
            'hide_branding' => (int) $_POST['hide_branding'],
            'custom' => array()
        );

        $plan_custom = ORM::for_table($config['db']['pre'].'plan_options')
            ->where('active', 1)
            ->order_by_asc('position')
            ->find_many();
        foreach ($plan_custom as $custom){
            if(!empty($custom['title']) && trim($custom['title']) != '' && !empty($_POST['custom_'.$custom['id']])) {
                $settings['custom'][$custom['id']] = 1;
            }
        }

        switch ($_POST['id']){
            case 'free':
                $plan = json_encode(array(
                    'id' => 'free',
                    'name' => $_POST['name'],
                    'badge' => $_POST['badge'],
                    'settings' => $settings,
                    'status' => $_POST['active']
                ));
                update_option('free_membership_plan', $plan);
                break;
            case 'trial':
                $plan = json_encode(array(
                    'id' => 'trial',
                    'name' => $_POST['name'],
                    'badge' => $_POST['badge'],
                    'days' => (int) $_POST['days'],
                    'settings' => $settings,
                    'status' => $_POST['active']
                ));
                update_option('trial_membership_plan', $plan);
                break;
            default:
                if ($_POST['monthly_price'] == "") {
                    echo $json = '{"status" : "error","message" : "' . __('Please fill the required fields.') . '"}';
                    die();
                }
                if ($_POST['annual_price'] == "") {
                    echo $json = '{"status" : "error","message" : "' . __('Please fill the required fields.') . '"}';
                    die();
                }
                if ($_POST['lifetime_price'] == "") {
                    echo $json = '{"status" : "error","message" : "' . __('Please fill the required fields.') . '"}';
                    die();
                }

                $recommended = ($_POST['recommended'] == 1) ? "yes" : "no";

                $insert_subscription = ORM::for_table($config['db']['pre'].'plans')->find_one($_POST['id']);
                $insert_subscription->name = validate_input($_POST['name']);
                $insert_subscription->badge = $_POST['badge'];
                $insert_subscription->monthly_price = $_POST['monthly_price'];
                $insert_subscription->annual_price = $_POST['annual_price'];
                $insert_subscription->lifetime_price = $_POST['lifetime_price'];
                $insert_subscription->settings = json_encode($settings);
                $insert_subscription->taxes_ids = isset($_POST['taxes'])? validate_input(implode(',',$_POST['taxes'])) : null;
                $insert_subscription->status = $_POST['active'];
                $insert_subscription->recommended = $recommended;
                $insert_subscription->date = date('Y-m-d H:i:s');
                $insert_subscription->save();
                break;
        }

        $status = "success";
        $message = __("Saved Successfully");

    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addStaticPage(){
    global $config;
    $error = array();

    if (empty($_POST['name'])) {
        $error[] = __('Name required');
    }
    if (empty($_POST['title'])) {
        $error[] = __('Title required.');
    }
    if (empty($_POST['content'])) {
        $error[] = __('Content required.');
    }
    if (empty($error)) {
        if (empty($_POST['slug']))
            $slug = create_slug($_POST['name']);
        else
            $slug = create_slug($_POST['slug']);

        $insert_page = ORM::for_table($config['db']['pre'].'pages')->create();
        $insert_page->translation_lang = 'en';
        $insert_page->name = validate_input($_POST['name']);
        $insert_page->title = validate_input($_POST['title']);
        $insert_page->content = validate_input($_POST['content'],true);
        $insert_page->slug = $slug;
        $insert_page->type = validate_input($_POST['type']);
        $insert_page->active = validate_input($_POST['active']);
        $insert_page->save();

        $id = $insert_page->id();

        $update_page = ORM::for_table($config['db']['pre'].'pages')->find_one($id);
        $update_page->set('translation_of', $id);
        $update_page->set('parent_id', $id);
        $update_page->save();

        $rows = ORM::for_table($config['db']['pre'].'languages')
            ->select_many('code','name')
            ->where('active', '1')
            ->where_not_equal('code', 'en')
            ->find_many();

        foreach ($rows as $fetch){
            $insert_page = ORM::for_table($config['db']['pre'].'pages')->create();
            $insert_page->translation_lang = $fetch['code'];
            $insert_page->translation_of = $id;
            $insert_page->parent_id = $id;
            $insert_page->name = $_POST['name'];
            $insert_page->title = $_POST['title'];
            $insert_page->content = $_POST['content'];
            $insert_page->slug = $slug;
            $insert_page->type = $_POST['type'];
            $insert_page->active = $_POST['active'];
            $insert_page->save();
        }
        $status = "success";
        $message = __('Saved Successfully');
    }else {
        $status = "error";
        $message = implode('<br>', $error);
    }
    $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    echo $json;
    die();
}

function editStaticPage(){
    global $config;
    $error = array();

    if (isset($_POST['id'])) {

        if (empty($_POST['name'])) {
            $error[] = __('Name required');
        }
        if (empty($_POST['title'])) {
            $error[] = __('Title required.');
        }
        if (empty($_POST['content'])) {
            $error[] = __('Content required.');
        }
        if (empty($error)) {
            if (empty($_POST['slug']))
                $slug = create_slug($_POST['name']);
            else
                $slug = create_slug($_POST['slug']);

            $update_page = ORM::for_table($config['db']['pre'].'pages')->find_one($_POST['id']);
            $update_page->set('name', validate_input($_POST['name']));
            $update_page->set('title', validate_input($_POST['title']));
            $update_page->set('content', validate_input($_POST['content'],true));
            $update_page->set('slug', $slug);
            $update_page->set('type', validate_input($_POST['type']));
            $update_page->set('active', validate_input($_POST['active']));
            $update_page->save();

            $status = "success";
            $message = __('Saved Successfully');

            echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
            die();
        }else {
            $status = "error";
            $message = __("Error: Please try again.");
        }
    } else {
        $status = "error";
        $message = implode('<br>', $error);
    }
    $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    echo $json;
    die();
}

function addFAQentry(){
    global $config;
    $error = array();

    if (empty($_POST['title'])) {
        $error[] = __('Title required.');
    }
    if (empty($_POST['content'])) {
        $error[] = __('Content required.');
    }
    if (empty($error)) {

        $insert_faq = ORM::for_table($config['db']['pre'].'faq_entries')->create();
        $insert_faq->translation_lang = 'en';
        $insert_faq->faq_title = validate_input($_POST['title']);
        $insert_faq->faq_content = validate_input($_POST['content'],true);
        $insert_faq->active = $_POST['active'];
        $insert_faq->save();

        $id = $insert_faq->id();

        $faqs = ORM::for_table($config['db']['pre'].'faq_entries')->find_one($id);
        $faqs->translation_of = $id;
        $faqs->parent_id = $id;
        $faqs->save();

        $rows = ORM::for_table($config['db']['pre'].'languages')
            ->select_many('code','name')
            ->where_not_equal('code', 'en')
            ->find_many();

        foreach ($rows as $fetch){
            $insert_faq = ORM::for_table($config['db']['pre'].'faq_entries')->create();
            $insert_faq->translation_lang = $fetch['code'];
            $insert_faq->translation_of = $id;
            $insert_faq->parent_id = $id;
            $insert_faq->faq_title = validate_input($_POST['title']);
            $insert_faq->faq_content = validate_input($_POST['content'],true);
            $insert_faq->active = $_POST['active'];
            $insert_faq->save();
        }
        $status = "success";
        $message = __("Saved Successfully");

    }else {
        $status = "error";
        $message = implode('<br>', $error);
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function editFAQentry(){
    global $config;
    $error = array();

    if (isset($_POST['id'])) {

        if (empty($_POST['title'])) {
            $error[] = __('Title required.');
        }
        if (empty($_POST['content'])) {
            $error[] = __('Content required.');
        }
        if (empty($error)) {

            $faqs = ORM::for_table($config['db']['pre'].'faq_entries')->find_one(validate_input($_POST['id']));
            $faqs->faq_title = validate_input($_POST['title']);
            $faqs->faq_content = validate_input($_POST['content'],true);
            $faqs->active = validate_input($_POST['active']);
            $faqs->save();

            if ($faqs->id()) {
                $status = "success";
                $message = __("Saved Successfully");
            } else{
                $status = "error";
                $message = __("Error: Please try again.");
            }
        }else {
            $status = "error";
            $message = __("Error: Please try again.");
        }
    } else {
        $status = "error";
        $message = implode('<br>', $error);
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function transactionEdit(){
    global $config;
    if (isset($_POST['id'])) {

        if (isset($_POST['status'])) {

            if($_POST['status'] == "success"){
                $transaction_id = $_POST['id'];
                transaction_success($transaction_id);
            }else{
                $transaction = ORM::for_table($config['db']['pre'].'transaction')->find_one($_POST['id']);
                $transaction->status = $_POST['status'];
                $transaction->save();
            }
            $status = "success";
            $message = __("Saved Successfully");


        }else {
            $status = "error";
            $message = __("Error: Please try again.");
        }
    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function paymentEdit()
{
    global $config;

    if (isset($_POST['id'])) {

        $pdo = ORM::get_db();
        $query = "UPDATE `".$config['db']['pre']."payments` SET
            `payment_title` = '" . validate_input($_POST['title']) . "',
            `payment_install` = '" . validate_input($_POST['install']) . "'
            WHERE `id` = '".$_POST['id']."' LIMIT 1 ";
        $query_result = $pdo->query($query);

        if(isset($_POST['paypal_sandbox_mode'])){
            update_option("paypal_sandbox_mode",isset($_POST['paypal_sandbox_mode'])? $_POST['paypal_sandbox_mode'] : "");
            update_option("paypal_payment_mode",isset($_POST['paypal_payment_mode'])? $_POST['paypal_payment_mode'] : "");
            update_option("paypal_api_client_id",isset($_POST['paypal_api_client_id'])? $_POST['paypal_api_client_id'] : "");
            update_option("paypal_api_secret",isset($_POST['paypal_api_secret'])? $_POST['paypal_api_secret'] : "");
        }

        if(isset($_POST['stripe_secret_key'])){
            update_option("stripe_payment_mode",$_POST['stripe_payment_mode']);
            update_option("stripe_publishable_key",$_POST['stripe_publishable_key']);
            update_option("stripe_secret_key",$_POST['stripe_secret_key']);

            if(empty($_POST['stripe_webhook_secret'])){
                // create webhook dynamically
                try{
                    if(file_exists('../includes/payments/stripe/stripe-php/init.php')) {
                        include '../includes/payments/stripe/stripe-php/init.php';
                        $stripe = new \Stripe\StripeClient($_POST['stripe_secret_key']);
                        $data = $stripe->webhookEndpoints->create([
                            'url' => $config['site_url'] . 'webhook/stripe',
                            'enabled_events' => [
                                'checkout.session.completed',
                                'invoice.paid',
                                'invoice.upcoming',
                                'invoice.payment_succeeded'
                            ],
                            'api_version' => '2019-12-03'
                        ]);

                        update_option("stripe_webhook_secret", $data['secret']);
                    }
                } catch (Exception $e){
                    $message = $e->getMessage();
                    echo $json = '{"status" : "error","message" : "' . $message . '"}';
                    die();
                }
            } else {
                update_option("stripe_webhook_secret", $_POST['stripe_webhook_secret']);
            }
        }

        if(isset($_POST['paystack_public_key'])){
            update_option("paystack_public_key",$_POST['paystack_public_key']);
            update_option("paystack_secret_key",$_POST['paystack_secret_key']);
        }

        if(isset($_POST['payumoney_merchant_key'])){
            update_option("payumoney_sandbox_mode",$_POST['payumoney_sandbox_mode']);
            update_option("payumoney_merchant_key",$_POST['payumoney_merchant_key']);
            update_option("payumoney_merchant_salt",$_POST['payumoney_merchant_salt']);
            update_option("payumoney_merchant_id",$_POST['payumoney_merchant_id']);
        }

        if(isset($_POST['checkout_account_number'])){
            update_option("2checkout_sandbox_mode",$_POST['2checkout_sandbox_mode']);
            update_option("checkout_account_number",$_POST['checkout_account_number']);
            update_option("checkout_public_key",$_POST['checkout_public_key']);
            update_option("checkout_private_key",$_POST['checkout_private_key']);
        }

        if(isset($_POST['company_bank_info'])){
            update_option("company_bank_info",$_POST['company_bank_info']);
        }

        if(isset($_POST['company_cheque_info'])){
            update_option("company_cheque_info",$_POST['company_cheque_info']);
            update_option("cheque_payable_to",$_POST['cheque_payable_to']);
        }

        if(isset($_POST['skrill_merchant_id'])){
            update_option("skrill_merchant_id",$_POST['skrill_merchant_id']);
        }

        if(isset($_POST['nochex_merchant_id'])){
            update_option("nochex_merchant_id",$_POST['nochex_merchant_id']);
        }

        if(isset($_POST['CCAVENUE_MERCHANT_KEY'])){
            update_option("CCAVENUE_MERCHANT_KEY",$_POST['CCAVENUE_MERCHANT_KEY']);
            update_option("CCAVENUE_ACCESS_CODE",$_POST['CCAVENUE_ACCESS_CODE']);
            update_option("CCAVENUE_WORKING_KEY",$_POST['CCAVENUE_WORKING_KEY']);
        }

        if(isset($_POST['PAYTM_ENVIRONMENT'])){
            update_option("PAYTM_ENVIRONMENT",$_POST['PAYTM_ENVIRONMENT']);
            update_option("PAYTM_MERCHANT_KEY",$_POST['PAYTM_MERCHANT_KEY']);
            update_option("PAYTM_MERCHANT_MID",$_POST['PAYTM_MERCHANT_MID']);
            update_option("PAYTM_MERCHANT_WEBSITE",$_POST['PAYTM_MERCHANT_WEBSITE']);
        }

        if(isset($_POST['mollie_api_key'])){
            update_option("mollie_api_key",$_POST['mollie_api_key']);
        }

        if(isset($_POST['iyzico_api_key'])){
            update_option("iyzico_sandbox_mode",$_POST['iyzico_sandbox_mode']);
            update_option("iyzico_api_key",$_POST['iyzico_api_key']);
            update_option("iyzico_secret_key",$_POST['iyzico_secret_key']);
        }

        if(isset($_POST['midtrans_client_key'])){
            update_option("midtrans_sandbox_mode",$_POST['midtrans_sandbox_mode']);
            update_option("midtrans_client_key",$_POST['midtrans_client_key']);
            update_option("midtrans_server_key",$_POST['midtrans_server_key']);
        }

        if(isset($_POST['paytabs_profile_id'])){
            update_option("paytabs_profile_id",$_POST['paytabs_profile_id']);
            update_option("paytabs_secret_key",$_POST['paytabs_secret_key']);
        }

        if(isset($_POST['telr_store_id'])){
            update_option("telr_sandbox_mode",$_POST['telr_sandbox_mode']);
            update_option("telr_store_id",$_POST['telr_store_id']);
            update_option("telr_authkey",$_POST['telr_authkey']);
        }

        if(isset($_POST['razorpay_api_key'])){
            update_option("razorpay_api_key",$_POST['razorpay_api_key']);
            update_option("razorpay_secret_key",$_POST['razorpay_secret_key']);
        }

        $status = "success";
        $message = __("Saved Successfully");

    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addBlogCat(){
    global $config;
    $_POST = validate_input($_POST);
    $name = $_POST['title'];
    $slug = $_POST['slug'];
    if (trim($name) != '' && is_string($name)) {
        if (empty($slug))
            $slug = create_blog_cat_slug($name);
        else
            $slug = create_blog_cat_slug($slug);

        if(check_allow()){
            $blog_cat = ORM::for_table($config['db']['pre'].'blog_categories')->create();
            $blog_cat->title = $name;
            $blog_cat->slug = $slug;
            $blog_cat->active = $_POST['active'];
            $blog_cat->save();

            $id = $blog_cat->id();
            if($id){
                $blog_pos = ORM::for_table($config['db']['pre'].'blog_categories')->find_one($id);
                $blog_pos->position = validate_input($id);
                $blog_pos->save();
            }
        }
        $status = "success";
        $message = __("Saved Successfully");
    } else{
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function editBlogCat(){
    global $config;
    $_POST = validate_input($_POST);
    $name = $_POST['title'];
    $slug = $_POST['slug'];
    $id = $_POST['id'];

    if (trim($name) != '' && is_string($name) && trim($id) != '') {
        if(check_allow()) {
            $blog_cat = ORM::for_table($config['db']['pre'] . 'blog_categories')->find_one($id);

            if($blog_cat['slug'] != $slug){
                if($slug == "")
                    $slug = create_blog_cat_slug($name);
                else
                    $slug = create_blog_cat_slug($slug);
            }

            $blog_cat->title = $name;
            $blog_cat->slug = $slug;
            $blog_cat->active = $_POST['active'];
            $blog_cat->save();
        }
        $status = "success";
        $message = __("Saved Successfully");
    } else{
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function saveEmailTemplate(){
    update_option("email_message_signup_details",stripslashes($_POST['email_message_signup_details']));
    update_option("email_message_signup_confirm",stripslashes($_POST['email_message_signup_confirm']));
    update_option("email_message_forgot_pass",stripslashes($_POST['email_message_forgot_pass']));
    update_option("email_message_contact",stripslashes($_POST['email_message_contact']));
    update_option("email_message_feedback",stripslashes($_POST['email_message_feedback']));
    update_option("email_message_report",stripslashes($_POST['email_message_report']));

    update_option("email_sub_signup_details",stripslashes(validate_input($_POST['email_sub_signup_details'])));
    update_option("email_sub_signup_confirm",stripslashes(validate_input($_POST['email_sub_signup_confirm'])));
    update_option("email_sub_forgot_pass",stripslashes(validate_input($_POST['email_sub_forgot_pass'])));
    update_option("email_sub_contact",stripslashes(validate_input($_POST['email_sub_contact'])));
    update_option("email_sub_feedback",stripslashes(validate_input($_POST['email_sub_feedback'])));
    update_option("email_sub_report",stripslashes(validate_input($_POST['email_sub_report'])));

    $status = "success";
    $message = 'Email setting updated Successfully';

    echo '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function testEmailTemplate(){

    global $config,$lang,$link;
    $_POST = validate_input($_POST);

    $test_to_email = $_POST['test_to_email'];
    $test_to_name = $_POST['test_to_name'];

    foreach ($_POST['templates'] as $template){
        switch ($template){
            case 'signup-details':
                $html = $config['email_sub_signup_details'];
                $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace ('{EMAIL}', $test_to_email, $html);
                $html = str_replace ('{USER_FULLNAME}', $test_to_name, $html);
                $email_subject = $html;

                $html = $config['email_message_signup_details'];
                $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace ('{USERNAME}', "demo", $html);
                $html = str_replace ('{PASSWORD}', "demo", $html);
                $html = str_replace ('{USER_ID}', "1", $html);
                $html = str_replace ('{EMAIL}', $test_to_email, $html);
                $html = str_replace ('{USER_FULLNAME}', $test_to_name, $html);
                $email_body = $html;

                email($test_to_email,$test_to_name,$email_subject,$email_body);
                break;

            case 'create-account':
                $html = $config['email_sub_signup_confirm'];
                $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace ('{EMAIL}', $test_to_email, $html);
                $html = str_replace ('{USER_FULLNAME}', $test_to_name, $html);
                $email_subject = $html;

                $confirmation_link = $link['SIGNUP']."?confirm=123456&user=1";

                $html = $config['email_message_signup_confirm'];
                $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace ('{CONFIRMATION_LINK}', $confirmation_link, $html);
                $html = str_replace ('{USERNAME}', "demo", $html);
                $html = str_replace ('{USER_ID}', "1", $html);
                $html = str_replace ('{EMAIL}', $test_to_email, $html);
                $html = str_replace ('{USER_FULLNAME}', $test_to_name, $html);
                $email_body = $html;

                email($test_to_email,$test_to_name,$email_subject,$email_body);
                break;

            case 'forgot-pass':
                $html = $config['email_sub_forgot_pass'];
                $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace ('{EMAIL}', $test_to_email, $html);
                $html = str_replace ('{USER_FULLNAME}', $test_to_name, $html);
                $email_subject = $html;

                $forget_password_link = $config['site_url']."login?forgot=sd1213f1x1&r=21d1d2d12&e=12&t=1213231";

                $html = $config['email_message_forgot_pass'];
                $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace ('FORGET_PASSWORD_LINK', $forget_password_link, $html);
                $html = str_replace ('{EMAIL}', $test_to_email, $html);
                $html = str_replace ('{USER_FULLNAME}', $test_to_name, $html);
                $email_body = $html;

                email($test_to_email,$test_to_name,$email_subject,$email_body);
                break;

            case 'contact_us':
                $html = $config['email_sub_contact'];
                $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace ('{CONTACT_SUBJECT}', "Contact Email", $html);
                $html = str_replace ('{EMAIL}', $test_to_email, $html);
                $html = str_replace ('{NAME}', $test_to_name, $html);
                $email_subject = $html;


                $html = $config['email_message_contact'];
                $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace ('{EMAIL}', $test_to_email, $html);
                $html = str_replace ('{NAME}', $test_to_name, $html);
                $html = str_replace ('{CONTACT_SUBJECT}', "Contact Email", $html);
                $html = str_replace ('{MESSAGE}', "Test Message", $html);
                $email_body = $html;

                email($test_to_email,$test_to_name,$email_subject,$email_body);
                break;
            case 'feedback':
                $html = $config['email_sub_feedback'];
                $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace ('{FEEDBACK_SUBJECT}', "Feedback Email", $html);
                $html = str_replace ('{EMAIL}', $test_to_email, $html);
                $html = str_replace ('{NAME}', $test_to_name, $html);
                $email_subject = $html;


                $html = $config['email_message_feedback'];
                $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace ('{EMAIL}', $test_to_email, $html);
                $html = str_replace ('{NAME}', $test_to_name, $html);
                $html = str_replace ('{PHONE}', "1234567890", $html);
                $html = str_replace ('{FEEDBACK_SUBJECT}', "Feedback Email", $html);
                $html = str_replace ('{MESSAGE}', "Test Message", $html);
                $email_body = $html;

                email($test_to_email,$test_to_name,$email_subject,$email_body);
                break;

            case 'report':
                $html = $config['email_sub_report'];
                $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace ('{EMAIL}', $test_to_email, $html);
                $html = str_replace ('{NAME}', $test_to_name, $html);
                $html = str_replace ('{USERNAME}', $test_to_name, $html);
                $html = str_replace ('{VIOLATION}', __("Advertising another website"), $html);
                $email_subject = $html;


                $html = $config['email_message_report'];
                $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace ('{EMAIL}', $test_to_email, $html);
                $html = str_replace ('{NAME}', $test_to_name, $html);
                $html = str_replace ('{USERNAME}', $test_to_name, $html);
                $html = str_replace ('{USERNAME2}', "Violator Username", $html);
                $html = str_replace ('{VIOLATION}', __("Advertising another website"), $html);
                $html = str_replace ('{URL}', "https://example.com", $html);
                $html = str_replace ('{DETAILS}', "Violator Message details here", $html);
                $email_body = $html;

                email($test_to_email,$test_to_name,$email_subject,$email_body);
                break;
        }
    }

    $status = "success";
    $message = 'Email Sent Successfully';

    echo '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function SaveSettings(){
    global $config;
    $status = "";

    if (isset($_POST['logo_watermark'])) {
        $valid_formats = array("jpg","jpeg","png"); // Valid image formats
        if (isset($_FILES['banner']) && $_FILES['banner']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['banner']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            //File extension check
            if (in_array($ext, $valid_formats)) {
                $uploaddir = "../storage/banner/"; //Image upload directory
                $bannername = stripslashes($_FILES['banner']['name']);
                $size = filesize($_FILES['banner']['tmp_name']);
                //Convert extension into a lower case format

                $ext = getExtension($bannername);
                $ext = strtolower($ext);
                $banner_name = "bg" . '.' . $ext;
                $newBgname = $uploaddir . $config['home_banner'];
                //Moving file to uploads folder
                if(file_exists($newBgname)){
                    unlink($newBgname);
                }
                $result = quick_file_upload('banner', $uploaddir);
                //Moving file to uploads folder
                if ($result['success']) {
                    update_option("home_banner",$result['file_name']);
                    $status = "success";
                    $message = __("Banner updated Successfully");

                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            }
            else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }

        }

        if (isset($_FILES['favicon']) && $_FILES['favicon']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['favicon']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            //File extension check
            if (in_array($ext, $valid_formats)) {
                $uploaddir = "../storage/logo/"; //Image upload directory
                $filename = stripslashes($_FILES['favicon']['name']);

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $image_name = "favicon" . '.' . $ext;
                $newLogo = $uploaddir . $config['site_favicon'];
                if(file_exists($newLogo) && !is_dir($newLogo)){
                    unlink($newLogo);
                }
                $result = quick_file_upload('favicon', $uploaddir);
                //Moving file to uploads folder
                if ($result['success']) {
                    update_option("site_favicon",$result['file_name']);
                    $status = "success";
                    $message = __("Site Favicon icon updated Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            }
            else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }

        }

        if (isset($_FILES['file']) && $_FILES['file']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['file']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            //File extension check
            if (in_array($ext, $valid_formats)) {
                $uploaddir = "../storage/logo/"; //Image upload directory
                $filename = stripslashes($_FILES['file']['name']);

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $image_name = $config['tpl_name']."_logo" . '.' . $ext;
                $newLogo = $uploaddir . $config['site_logo'];
                if(file_exists($newLogo) && !is_dir($newLogo)){
                    unlink($newLogo);
                }
                $result = quick_file_upload('file', $uploaddir);
                //Moving file to uploads folder
                if ($result['success']) {
                    update_option("site_logo",$result['file_name']);
                    $status = "success";
                    $message = __("Site Logo updated Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            }
            else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }

        }

        if (isset($_FILES['footer_logo']) && $_FILES['footer_logo']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['footer_logo']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            //File extension check
            if (in_array($ext, $valid_formats)) {
                $uploaddir = "../storage/logo/"; //Image upload directory
                $filename = stripslashes($_FILES['footer_logo']['name']);

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $image_name = $config['tpl_name']."_footer_logo" . '.' . $ext;
                $newLogo = $uploaddir . $config['site_logo_footer'];
                if(file_exists($newLogo) && !is_dir($newLogo)){
                    unlink($newLogo);
                }
                $result = quick_file_upload('footer_logo', $uploaddir);
                //Moving file to uploads folder
                if ($result['success']) {

                    update_option("site_logo_footer",$result['file_name']);
                    $status = "success";
                    $message = __("Site Logo updated Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            }
            else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }

        }

        if (isset($_FILES['adminlogo']) && $_FILES['adminlogo']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['adminlogo']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            //File extension check
            if (in_array($ext, $valid_formats)) {
                $uploaddir = "../storage/logo/"; //Image upload directory
                $filename = stripslashes($_FILES['adminlogo']['name']);
                $size = filesize($_FILES['adminlogo']['tmp_name']);
                //Convert extension into a lower case format

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $adminlogo_name = "adminlogo" . '.' . $ext;
                $adminlogo = $uploaddir . $config['site_admin_logo'];
                if(file_exists($adminlogo) && !is_dir($adminlogo)){
                    unlink($adminlogo);
                }
                $result = quick_file_upload('adminlogo', $uploaddir);
                //Moving file to uploads folder
                if ($result['success']) {
                    update_option("site_admin_logo",$result['file_name']);
                    $status = "success";
                    $message = __("Admin Logo updated Successfully");
                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            }
            else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }

        }

        if($status == ""){
            $status = "success";
            $message = __("Saved Successfully");
        }
    }

    if (isset($_POST['general_setting'])) {
        update_option("site_url",$_POST['site_url']);
        update_option("site_title",$_POST['site_title']);
        update_option("meta_keywords",validate_input($_POST['meta_keywords']));
        update_option("meta_description",validate_input($_POST['meta_description']));
        update_option("try_demo_link",validate_input($_POST['try_demo_link']));
        update_option("default_user_plan",validate_input($_POST['default_user_plan']));
        update_option("cron_exec_time",validate_input($_POST['cron_exec_time']));
        update_option("termcondition_link",validate_input($_POST['termcondition_link']));
        update_option("privacy_link",validate_input($_POST['privacy_link']));
        update_option("cookie_link",validate_input($_POST['cookie_link']));
        update_option("non_active_msg",$_POST['non_active_msg']);
        update_option("non_active_allow",$_POST['non_active_allow']);
        update_option("userlangsel",$_POST['userlangsel']);
        update_option("userthemesel",$_POST['userthemesel']);
        update_option("color_switcher",$_POST['color_switcher']);
        update_option("cookie_consent",$_POST['cookie_consent']);
        update_option("demo_mode",$_POST['demo_mode']);
        update_option("enable_maintenance_mode",validate_input($_POST['enable_maintenance_mode']));
        update_option("developer_credit",$_POST['developer_credit']);
        update_option("quickad_debug",$_POST['quickad_debug']);
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['quick_map'])) {
        update_option("map_type",validate_input($_POST['map_type']));
        update_option("openstreet_access_token",validate_input($_POST['openstreet_access_token']));
        update_option("gmap_api_key",validate_input($_POST['gmap_api_key']));
        update_option("map_color",validate_input($_POST['map_color']));
        update_option("home_map_latitude",validate_input($_POST['home_map_latitude']));
        update_option("home_map_longitude",validate_input($_POST['home_map_longitude']));
        update_option("contact_latitude",validate_input($_POST['contact_latitude']));
        update_option("contact_longitude",validate_input($_POST['contact_longitude']));
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['international'])) {

        if(isset($_POST['currency'])) {
            $info = ORM::for_table($config['db']['pre'].'currencies')->find_one($_POST['currency']);

            $currency_sign = $info['html_entity'];
            $currency_code = $info['code'];
            $currency_pos = $info['in_left'];
        }
        if(isset($_POST['lang'])) {
            change_user_lang($_POST['lang']);
        }
        update_option("lang",$_POST['lang']);
        update_option("specific_country",$_POST['specific_country']);
        update_option("timezone",$_POST['timezone']);
        update_option("currency_sign",$currency_sign);
        update_option("currency_code",$currency_code);
        update_option("currency_pos",$currency_pos);
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['email_setting'])) {
        update_option("admin_email",$_POST['admin_email']);
        update_option("email_type",'smtp');
        update_option("smtp_host",$_POST['smtp_host']);
        update_option("smtp_port",$_POST['smtp_port']);
        update_option("smtp_username",$_POST['smtp_username']);
        update_option("smtp_password",$_POST['smtp_password']);
        update_option("smtp_secure",$_POST['smtp_secure']);
        update_option("smtp_auth",$_POST['smtp_auth']);

        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['theme_setting'])) {
        update_option("header_sticky",validate_input($_POST['header_sticky']));
        update_option("transparent_header",validate_input($_POST['transparent_header']));
        update_option("banner_overlay",validate_input($_POST['banner_overlay']));
        update_option("show_membershipplan_home",validate_input($_POST['show_membershipplan_home']));
        update_option("show_partner_logo_home",validate_input($_POST['show_partner_logo_home']));
        update_option("theme_color",validate_input($_POST['theme_color']));
        update_option("contact_address",validate_input($_POST['contact_address']));
        update_option("contact_phone",validate_input($_POST['contact_phone']));
        update_option("contact_email",validate_input($_POST['contact_email']));
        update_option("footer_text",validate_input($_POST['footer_text']));
        update_option("copyright_text",validate_input($_POST['copyright_text']));
        update_option("facebook_link",validate_input($_POST['facebook_link']));
        update_option("twitter_link",validate_input($_POST['twitter_link']));
        update_option("instagram_link",validate_input($_POST['instagram_link']));
        update_option("linkedin_link",validate_input($_POST['linkedin_link']));
        update_option("pinterest_link",validate_input($_POST['pinterest_link']));
        update_option("youtube_link",validate_input($_POST['youtube_link']));
        update_option("external_code",$_POST['external_code']);
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['billing_details'])) {
        update_option("invoice_nr_prefix", validate_input($_POST['invoice_nr_prefix']));
        update_option("invoice_admin_name", validate_input($_POST['invoice_admin_name']));
        update_option("invoice_admin_email", validate_input($_POST['invoice_admin_email']));
        update_option("invoice_admin_phone", validate_input($_POST['invoice_admin_phone']));
        update_option("invoice_admin_address", validate_input($_POST['invoice_admin_address']));
        update_option("invoice_admin_city", validate_input($_POST['invoice_admin_city']));
        update_option("invoice_admin_state", validate_input($_POST['invoice_admin_state']));
        update_option("invoice_admin_zipcode", validate_input($_POST['invoice_admin_zipcode']));
        update_option("invoice_admin_country", validate_input($_POST['invoice_admin_country']));
        update_option("invoice_admin_tax_type", validate_input($_POST['invoice_admin_tax_type']));
        update_option("invoice_admin_tax_id", validate_input($_POST['invoice_admin_tax_id']));

        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['social_login_setting'])) {
        update_option("facebook_login",validate_input($_POST['facebook_login']));
        update_option("facebook_app_id",validate_input($_POST['facebook_app_id']));
        update_option("facebook_app_secret",validate_input($_POST['facebook_app_secret']));
        update_option("google_login",validate_input($_POST['google_login']));
        update_option("google_app_id",validate_input($_POST['google_app_id']));
        update_option("google_app_secret",validate_input($_POST['google_app_secret']));
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['recaptcha_setting'])) {

        update_option("recaptcha_mode",validate_input($_POST['recaptcha_mode']));
        update_option("recaptcha_public_key",validate_input($_POST['recaptcha_public_key']));
        update_option("recaptcha_private_key",validate_input($_POST['recaptcha_private_key']));
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['blog_setting'])) {

        update_option("blog_enable",validate_input($_POST['blog_enable']));
        update_option("blog_banner",validate_input($_POST['blog_banner']));
        update_option("show_blog_home",validate_input($_POST['show_blog_home']));
        update_option("blog_comment_enable",validate_input($_POST['blog_comment_enable']));
        update_option("blog_comment_approval",validate_input($_POST['blog_comment_approval']));
        update_option("blog_comment_user",validate_input($_POST['blog_comment_user']));
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['testimonials_setting'])) {

        update_option("testimonials_enable",validate_input($_POST['testimonials_enable']));
        update_option("show_testimonials_blog",validate_input($_POST['show_testimonials_blog']));
        update_option("show_testimonials_home",validate_input($_POST['show_testimonials_home']));
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['valid_purchase_setting'])) {

        // Set API Key
        $code = $_POST['purchase_key'];
        $buyer_email = (isset($_POST['buyer_email']))? validate_input($_POST['buyer_email']) : "";
        $installing_version = 'pro';

        $url = "https://bylancer.com/api/api.php?verify-purchase=" . $code . "&version=" . $installing_version . "&site_url=". $config['site_url']."&email=" . $buyer_email;
        // Open cURL channel
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //Set the user agent
        $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
        // Decode returned JSON
        $output = json_decode(curl_exec($ch), true);
        // Close Channel
        curl_close($ch);

        if ($output['success']) {
            if(isset($config['quickad_secret_file']) && $config['quickad_secret_file'] != ""){
                $fileName = $config['quickad_secret_file'];
            }else{
                $fileName = get_random_string();
            }

            if(isset($config['quickad_user_secret_file']) && $config['quickad_user_secret_file'] != ""){
                $userFileName = $config['quickad_user_secret_file'];
            }else{
                $userFileName = get_random_string();
            }
            file_put_contents( $fileName . '.php', $output['data']);
            file_put_contents( '../php/'.$userFileName . '.php', $output['user_data']);
            $success = true;
            update_option("quickad_secret_file",$fileName);
            update_option("quickad_user_secret_file",$userFileName);

            update_option("purchase_key", $_POST['purchase_key']);
            update_option("purchase_type", $output['purchase_type']);
            $status = "success";
            $message = __("Purchase code verified successfully.");
        } else {
            $status = "error";
            $message = $output['error'];
        }
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}