<?php
global $config,$link;
if(checkloggedin())
{
    if(isset($_POST['submit'])){
        if(isset($_POST['id'])){
            $vcard = ORM::for_table($config['db']['pre'].'vcards')
                ->where(array(
                    'id' => $_POST['id'],
                    'user_id' => $_SESSION['user']['id']
                ))
                ->find_one();
        }
        else{
            error(__("Invalid vcard id passed"), __LINE__, __FILE__, 1);
            exit();
        }

        $MainFileName = null;
        $main_imageName = get_vcard_option($vcard['id'],'qr_image');

        if(isset($_FILES['qr_image']['name']) && $_FILES['qr_image']['name'] != "") {
            $target_dir = ROOTPATH . "/storage/cards/logo/";
            $result = quick_file_upload('qr_image',$target_dir);
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
                $errors[]['message'] = $result['error'];
            }
        }

        if($MainFileName){
            update_vcard_option($vcard['id'],'qr_image',$MainFileName);
        }
        update_vcard_option($vcard['id'],'qr_fg_color',$_POST['qr_fg_color']);
        update_vcard_option($vcard['id'],'qr_bg_color',$_POST['qr_bg_color']);
        update_vcard_option($vcard['id'],'qr_padding',$_POST['qr_padding']);
        update_vcard_option($vcard['id'],'qr_radius',$_POST['qr_radius']);
        update_vcard_option($vcard['id'],'qr_mode',$_POST['qr_mode']);
        update_vcard_option($vcard['id'],'qr_text',$_POST['qr_text']);
        update_vcard_option($vcard['id'],'qr_text_color',$_POST['qr_text_color']);
        update_vcard_option($vcard['id'],'qr_mode_size',$_POST['qr_mode_size']);
        update_vcard_option($vcard['id'],'qr_position_x',$_POST['qr_position_x']);
        update_vcard_option($vcard['id'],'qr_position_y',$_POST['qr_position_y']);

        transfer($_SERVER['REQUEST_URI'],__('Saved Successfully'));
        exit;
    }

    if(isset($_GET['id'])){
        $vcard = ORM::for_table($config['db']['pre'].'vcards')
            ->where(array(
                'id' => $_GET['id'],
                'user_id' => $_SESSION['user']['id']
            ))
            ->find_one();
    }
    else{
        error(__("Invalid vcard id passed"), __LINE__, __FILE__, 1);
        exit();
    }

    $url = $card_url = $config['site_url'];
    if(isset($vcard['user_id'])){
        $id = $vcard['id'];
        $name = $vcard['name'];
        $slug = $vcard['slug'];
        if(!empty($slug)) {
            $url = $url . $slug . '?qr-id=' . urlencode(quick_xor_encrypt($slug, 'quick-qr'));
            $card_url = $card_url . $slug;
        }
    }

    $qr_image = $config['site_url']. "storage/logo/".$config['site_logo'];
    if($image_name = get_vcard_option($vcard['id'],'qr_image')){
        $qr_image = $config['site_url']. "storage/cards/logo/".$image_name;
    }

    //Print Template 'Add-vcard Page'
    HtmlTemplate::display('qr-builder', array(
        'vcard_id' => $id,
        'scan_url' => $url,
        'card_url' => $card_url,
        'qr_fg_color' => get_vcard_option($vcard['id'],'qr_fg_color','#000000'),
        'qr_bg_color' => get_vcard_option($vcard['id'],'qr_bg_color','#ffffff'),
        'qr_padding' => get_vcard_option($vcard['id'],'qr_padding','2'),
        'qr_radius' => get_vcard_option($vcard['id'],'qr_radius','50'),
        'qr_mode' => get_vcard_option($vcard['id'],'qr_mode','2'),
        'qr_image' => $qr_image,
        'qr_text' => get_vcard_option($vcard['id'],'qr_text',$vcard['title']),
        'qr_text_color' => get_vcard_option($vcard['id'],'qr_text_color', $config['theme_color']),
        'qr_mode_size' => get_vcard_option($vcard['id'],'qr_mode_size','10'),
        'qr_position_x' => get_vcard_option($vcard['id'],'qr_position_x','50'),
        'qr_position_y' => get_vcard_option($vcard['id'],'qr_position_y','50')
    ));
}
else{
    headerRedirect($link['LOGIN']);
}