<?php
global $config,$link;
if (isset($_GET['slug'])) {
    $vcard = ORM::for_table($config['db']['pre'] . 'vcards')
            ->where('slug', $_GET['slug'])
            ->find_one();

    if (isset($vcard['title'])) {

        // Get usergroup details
        $user_info = ORM::for_table($config['db']['pre'] . 'user')
            ->select('group_id')
            ->find_one($vcard['user_id']);

        $group_id = isset($user_info['group_id']) ? $user_info['group_id'] : 'free';

        // Get membership details
        switch ($group_id) {
            case 'free':
                $plan = json_decode(get_option('free_membership_plan'), true);
                $settings = $plan['settings'];
                $limit = $settings['scan_limit'];
                break;
            case 'trial':
                $plan = json_decode(get_option('trial_membership_plan'), true);
                $settings = $plan['settings'];
                $limit = $settings['scan_limit'];
                break;
            default:
                $plan = ORM::for_table($config['db']['pre'] . 'plans')
                    ->select('settings')
                    ->where('id', $group_id)
                    ->find_one();
                if (!isset($plan['settings'])) {
                    $plan = json_decode(get_option('free_membership_plan'), true);
                    $settings = $plan['settings'];
                    $limit = $settings['scan_limit'];
                } else {
                    $settings = json_decode($plan['settings'], true);
                    $limit = $settings['scan_limit'];
                }
                break;
        }

        // Serve a real file: mobile contact import must not depend on data URLs or JavaScript.
        if (isset($_GET['download-contact'])) {
            require_once ROOTPATH . '/includes/lib/contact-vcard.php';
            $photo = ROOTPATH . '/storage/cards/logo/' . basename($vcard['main_image'] ?: 'default.png');
            $content = build_contact_vcard($vcard, $settings['field_limit'], $photo);
            $filename = preg_replace('/[^A-Za-z0-9_-]+/', '-', $vcard['title']);
            $filename = substr(trim($filename, '-'), 0, 80) ?: 'contact';
            header('Content-Type: text/vcard; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '.vcf"');
            header('Cache-Control: private, no-store');
            header('X-Content-Type-Options: nosniff');
            echo $content;
            exit();
        }

        // check for url
        if (!empty($_GET['qr-id'])) {
            $qr_id = quick_xor_decrypt(($_GET['qr-id']), 'quick-qr');
            if ($_GET['slug'] == $qr_id) {

                if ($limit != "999") {
                    $start = date('Y-m-01');
                    $end = date('Y-m-t');

                    $total = ORM::for_table($config['db']['pre'] . 'vcard_view')
                        ->where('vcard_id', $vcard['id'])
                        ->where_raw("`date` BETWEEN '$start' AND '$end'")
                        ->count();

                    if ($total >= $limit) {
                        message(__("Notify"), __("Scan Limit Exceed"));
                        exit();
                    }
                }

                $add_view = ORM::for_table($config['db']['pre'] . 'vcard_view')->create();
                $add_view->vcard_id = $vcard['id'];
                $add_view->ip = get_client_ip();
                $add_view->date = date('Y-m-d H:i:s');
                $add_view->save();

                headerRedirect($config['site_url'] . $vcard['slug']);
            }
        }

        $vcard_id = $vcard['id'];
        $title = validate_input($vcard['title']);
        $sub_title = validate_input($vcard['sub_title']);
        $description = validate_input($vcard['description']);
        $main_image = $vcard['main_image'] ?: 'default.png';
        $cover_image = $vcard['cover_image'] ?: 'default.png';
        $color = $vcard['color'];

        $details = $vcard['details'] ?: '[]';

        $main_image_path = ROOTPATH.'/storage/cards/logo/'.$main_image;
        $logo_64encoded = base64_encode(file_get_contents($main_image_path));

        $template = get_vcard_option($vcard_id, 'vcard_template','classic-theme');
        $template_path = 'vcard-templates/'.$template.'/index';
        $page_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $colors = array();
        list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");
        $i = 0.01;
        while ($i <= 1) {
            $colors["$i"]['id'] = str_replace('.', '_', $i);
            $colors["$i"]['value'] = "rgba($r,$g,$b,$i)";
            $i += 0.01;
        }
        $colors[1]['id'] = 1;
        $colors[1]['value'] = "rgba($r,$g,$b,1)";

        //Print Template 'vcard profile Page'
        HtmlTemplate::display('vcard-templates/'.$template.'/index', array(
            'page_title' => $title,
            'contact_download_url' => $config['site_url'] . rawurlencode($vcard['slug']) . '?download-contact=1',
            'page_link' => $page_link,
            'site_title' => $config['site_title'],
            'page_meta_keywords' => $config['meta_keywords'],
            'page_meta_description' => $config['meta_description'],
            'lang_direction' => get_current_lang_direction(),
            'vcard_id' => $vcard_id,
            'title' => $title,
            'sub_title' => $sub_title,
            'description' => $description,
            'description_js' => json_encode(str_replace("\r\n",'\n',$vcard['description'])),
            'main_image' => $main_image,
            'main_image_64_encoded' => $logo_64encoded,
            'cover_image' => $cover_image,
            'details' => $details,
            'details_field_limit' => $settings['field_limit'],
            'show_details' => (int) !empty($vcard['details']),
            'hide_branding' => (int) $settings['hide_branding'],
            'color' => $color,
            'colors' => $colors
        ),true,true);

    } else {
        header("HTTP/1.0 404 Not Found");
        error(__("Page Not Found"), __LINE__, __FILE__, 1);
        exit();
    }
} else {
    header("HTTP/1.0 404 Not Found");
    error(__("Page Not Found"), __LINE__, __FILE__, 1);
    exit();
}
?>
