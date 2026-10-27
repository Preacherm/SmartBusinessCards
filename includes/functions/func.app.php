<?php
/**
 * Add vcard option
 *
 * @param $vcard_id
 * @param $option
 * @param null $value
 * @return array|false|mixed
 * @throws Exception
 */
function add_vcard_option($vcard_id, $option, $value = null) {

    global $config;
    $option = trim($option);
    if ( empty($option) )
        return false;

    $option_id = ORM::for_table($config['db']['pre'].'vcard_options')->create();
    $option_id->vcard_id = $vcard_id;
    $option_id->option_name = $option;
    $option_id->option_value = $value;
    $option_id->save();

    return $option_id->id();
}

/**
 * Get vcard option
 *
 * @param $vcard_id
 * @param $option
 * @param null $default
 * @return array|mixed|null
 */
function get_vcard_option($vcard_id, $option, $default = null) {

    global $config;
    $option = trim($option);
    if ( empty($option) )
        return $default;

    $result = ORM::for_table($config['db']['pre'].'vcard_options')
        ->where('option_name', $option)
        ->where('vcard_id', $vcard_id)
        ->find_one();

    if ( isset($result['option_value']))
        return $result['option_value'];
    else
        return $default;
}

/**
 * Check vcard option exist
 *
 * @param $vcard_id
 * @param $option
 * @return bool
 */
function check_vcard_option_exist($vcard_id, $option) {

    global $config;
    $option = trim($option);
    if ( empty($option) )
        return false;

    $num_rows = ORM::for_table($config['db']['pre'].'vcard_options')
        ->where('option_name',$option)
        ->where('vcard_id', $vcard_id)
        ->count();
    if($num_rows == 1)
        return true;
    else
        return false;
}

/**
 * Update vcard option
 *
 * @param $vcard_id
 * @param $option
 * @param $value
 * @return bool
 * @throws Exception
 */
function update_vcard_option($vcard_id, $option, $value) {

    global $config;
    $option = trim($option);
    if ( empty($option) )
        return false;

    if(check_vcard_option_exist($vcard_id, $option )){
        $pdo = ORM::get_db();
        $data = [
            'vcard_id' => $vcard_id,
            'option_value' => $value,
            'option_name' => $option
        ];
        $sql = "UPDATE ".$config['db']['pre']."vcard_options SET option_value=:option_value WHERE option_name=:option_name AND vcard_id=:vcard_id";
        $query_result = $pdo->prepare($sql)->execute($data);

        if (!$query_result)
            return false;
        else
            return true;
    }
    else{
        add_vcard_option($vcard_id,$option,$value);
        return true;
    }
}

/**
 * Delete vcard option
 *
 * @param $vcard_id
 * @param $option
 * @return bool
 */
function delete_vcard_option($vcard_id, $option) {

    global $config;
    $option = trim($option);
    if ( empty($option) )
        return false;

    $result = ORM::for_table($config['db']['pre'].'vcard_options')
        ->where('option_name',$option)
        ->where('vcard_id', $vcard_id)
        ->delete_many();

    if ( ! $result )
        return false;
    else
        return true;
}


/**
 * XOR encrypt/decrypt.
 *
 * @param string $str
 * @param string $password
 * @return string
 */
function quick_xor($str, $password = '')
{
    $len = strlen($str);
    $gamma = '';
    $n = $len > 100 ? 8 : 2;
    while (strlen($gamma) < $len) {
        $gamma .= substr(pack('H*', sha1($password . $gamma)), 0, $n);
    }

    return $str ^ $gamma;
}

/**
 * XOR encrypt with Base64 encode.
 *
 * @param string $str
 * @param string $password
 * @return string
 */
function quick_xor_encrypt($str, $password = '')
{
    return base64_encode(quick_xor($str, $password));
}

/**
 * XOR decrypt with Base64 decode.
 *
 * @param string $str
 * @param string $password
 * @return string
 */
function quick_xor_decrypt($str, $password = '')
{
    return quick_xor(base64_decode($str), $password);
}

/**
 * Cron Jobs
 *
 * @return false|void
 */
function run_cron_job(){

    global $config,$lang,$link;
    $pdo = ORM::get_db();
    $cron_time = isset($config['cron_time']) ? $config['cron_time'] : 0;
    $cron_exec_time = isset($config['cron_exec_time']) ? $config['cron_exec_time'] : "300";

    if((time()-$cron_exec_time) > $cron_time) {

        ignore_user_abort(1);
        @set_time_limit(0);

        $start_time = time();
        update_option('cron_time',time());

        /**
         * START REMOVE OLD PENDING TRANSACTIONS IN 3 Days
         *
         */
        $expiry_time = time()-259200;
        ORM::for_table($config['db']['pre'].'transaction')
            ->where_any_is(array(
                array('status' => 'pending', 'transaction_time' => $expiry_time)), array('transaction_time' => '<'))
            ->delete_many();
        // END REMOVE OLD PENDING TRANSACTIONS

        /**
         * START REMOVE EXPIRED UPGRADES IN 24 Hours
         *
         */
        $expire_membership = 0;
        $expiry_time = time()-86400;


        $result = ORM::for_table($config['db']['pre'].'upgrades')
            ->select_many('upgrade_id','user_id')
            ->where_lt('upgrade_expires', $expiry_time)
            ->find_many();
        foreach ($result as $info)
        {
            $person_count = ORM::for_table($config['db']['pre'].'user')
                ->where('id', $info['user_id'])
                ->count();
            if($person_count){
                $person = ORM::for_table($config['db']['pre'].'user')->find_one($info['user_id']);
                $person->group_id = 'free';
                $person->save();
            }
            ORM::for_table($config['db']['pre'].'upgrades')
                ->where_equal('upgrade_id', $info['upgrade_id'])
                ->delete_many();

            $expire_membership++;
        }
        // END REMOVE EXPIRED UPGRADES

        $end_time = (time()-$start_time);

        $cron_details = "Expire membership: ".$expire_membership."<br>";
        $cron_details.= "<br>";
        $cron_details.= "Cron Took: ".$end_time." seconds";

        log_adm_action('Cron Run',$cron_details);
    }
    else {
        return false;
    }
}
run_cron_job();