<?php

$count = 0;
$faq = array();
$rows = ORM::for_table($config['db']['pre'].'faq_entries')
    ->select_many('id','faq_title','faq_content')
    ->where(array(
        'translation_lang' => $config['lang_code'],
        'active' => 1
    ))
    ->order_by_asc('id')
    ->find_many();

foreach ($rows as $info)
{
    $count++;

    $faq[$count]['id'] = $info['id'];
    $faq[$count]['title'] = stripslashes($info['faq_title']);
    $faq[$count]['content'] = stripslashes($info['faq_content']);
}

//Print Template
HtmlTemplate::display('global/faq', array(
    'faq' => $faq
));
exit;
?>