<?php
// Run with: php tests/contact-vcard.php
require_once __DIR__ . '/../includes/lib/contact-vcard.php';
function check_contact($condition, $message)
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}
$card = array('title' => 'Zoë, Smith; Example', 'sub_title' => 'Sales &amp; Support',
    'description' => "First line\r\nSecond line\\value\nTEL:injected",
    'details' => json_encode(array(
        array('type' => 'phone', 'value' => '+27123456789'),
        array('type' => 'email', 'value' => 'zoe@example.com'),
        array('type' => 'address', 'value' => '12 Main; Cape Town'),
        array('type' => 'github', 'value' => 'example'),
        array('type' => 'website', 'value' => 'https://example.com/hidden')
    )));
$content = build_contact_vcard($card, 4);
check_contact(strpos($content, "FN:Zoë\\, Smith\\; Example\r\n") !== false, 'Escape names');
check_contact(strpos($content, 'TITLE:Sales & Support') !== false, 'Decode HTML entities');
check_contact(strpos($content, "\r\nTEL:injected") === false, 'Prevent property injection');
check_contact(strpos($content, 'TEL:+27123456789') !== false, 'Include phone');
check_contact(strpos($content, 'EMAIL;TYPE=INTERNET:zoe@example.com') !== false, 'Include email');
check_contact(strpos($content, 'ADR:;;12 Main\\; Cape Town;;;;') !== false, 'Structured address');
check_contact(strpos($content, 'URL:https://github.com/example') !== false, 'Preserve social links');
check_contact(strpos($content, '/hidden') === false, 'Respect plan field limit');
check_contact(substr($content, -11) === "END:VCARD\r\n", 'Terminate vCard');
$card['description'] = str_repeat('é😀', 100);
$content = build_contact_vcard($card, 0, '/nonexistent/contact-photo.png');
foreach (explode("\r\n", $content) as $line) {
    check_contact(strlen($line) <= 75, 'Fold long lines to 75 bytes');
    check_contact(preg_match('//u', $line) === 1, 'Preserve UTF-8 across folds');
}
$unfolded = str_replace("\r\n ", '', $content);
check_contact(strpos($unfolded, 'NOTE:' . $card['description']) !== false, 'Unfold without data loss');
$card['details'] = '{invalid';
check_contact(strpos(build_contact_vcard($card, 4), 'END:VCARD') !== false, 'Handle malformed details');
echo "Contact vCard checks passed.\n";
