<?php
global $config;
if(checkloggedin())
{
    if(isset($_GET['id'])){
        $vcards = ORM::for_table($config['db']['pre'].'vcards')
            ->where(array(
                'id' => $_GET['id'],
                'user_id' => $_SESSION['user']['id']
            ))
            ->find_one();
    }
    else
        $_GET['id'] = null;

    if(isset($vcards['id'])){
        $card_id = $vcards['id'];
        $slug = $vcards['slug'];
        $color = $vcards['color'];
        $title = $vcards['title'];
        $sub_title = $vcards['sub_title'];
        $description = $vcards['description'];
        $cover_image = $vcards['cover_image'];
        $main_image = $vcards['main_image'];
        $details = $vcards['details'] ?: '[]';
        $vcards_link = $config['site_url'] . $slug;

    }else{
        $card_id = '';
        $slug = '';
        $color = $config['theme_color'];
        $title = '';
        $sub_title = '';
        $description = '';
        $cover_image = 'default.png';
        $main_image = 'default.png';
        $details = '[]';
        $vcards_link = '#';
    }

    $vcard_templates = array();

    if ($handle = opendir('vcard-templates/'))
    {
        while (false !== ($folder = readdir($handle)))
        {
            if ($folder != "." && $folder != "..")
            {
                $filepath = "vcard-templates/" . $folder . "/theme-info.txt";
                if(file_exists($filepath)){
                    $themefile = fopen($filepath,"r");

                    $themeinfo = array();
                    while(! feof($themefile)) {
                        $lineRead = fgets($themefile);
                        if (strpos($lineRead, ':') !== false) {
                            $line = explode(':',$lineRead);
                            $key = trim($line[0]);
                            $value = trim($line[1]);
                            $themeinfo[$key] = $value;
                        }
                    }
                    $vcard_templates[$folder]['folder'] = $folder;
                    $vcard_templates[$folder]['name'] = $themeinfo['Theme Name'];
                    fclose($themefile);
                }
            }
        }
        closedir($handle);
    }

    // Get usergroup details
    $user_info = ORM::for_table($config['db']['pre'] . 'user')
        ->select('group_id')
        ->find_one($_SESSION['user']['id']);

    $group_id = isset($user_info['group_id']) ? $user_info['group_id'] : 'free';

    // Get membership details
    switch ($group_id) {
        case 'free':
            $plan = json_decode(get_option('free_membership_plan'), true);
            $settings = $plan['settings'];
            $field_limit = $settings['field_limit'];
            break;
        case 'trial':
            $plan = json_decode(get_option('trial_membership_plan'), true);
            $settings = $plan['settings'];
            $field_limit = $settings['field_limit'];
            break;
        default:
            $plan = ORM::for_table($config['db']['pre'] . 'plans')
                ->select('settings')
                ->where('id', $group_id)
                ->find_one();
            if (!isset($plan['settings'])) {
                $plan = json_decode(get_option('free_membership_plan'), true);
                $settings = $plan['settings'];
                $field_limit = $settings['field_limit'];
            } else {
                $settings = json_decode($plan['settings'], true);
                $field_limit = $settings['field_limit'];
            }
            break;
    }

    //Print Template 'Add-vcard Page'
    HtmlTemplate::display('add-vcard', array(
        'vcard_templates' => $vcard_templates,
        'vcard_link' => $vcards_link,
        'vcard_template' => get_vcard_option($card_id, 'vcard_template','classic-theme'),
        'color' => $color,
        'card_id' => $card_id,
        'title' => $title,
        'slug' => $slug,
        'sub_title' => $sub_title,
        'description' => $description,
        'main_image' => $main_image,
        'cover_image' => $cover_image,
        'details' => $details,
        'details_field_limit' => $field_limit
    ));
}
else{
    headerRedirect($link['LOGIN']);
}
?>