<?php
/** Build a vCard 3.0 independently of the card's JavaScript and theme. */
function contact_vcard_text($value)
{
    $value = html_entity_decode(strip_tags((string) $value), ENT_QUOTES, 'UTF-8');
    return str_replace(array('\\', "\r\n", "\r", "\n", ';', ','),
        array('\\\\', '\\n', '\\n', '\\n', '\\;', '\\,'), $value);
}

function build_contact_vcard($card, $field_limit, $photo_path = null)
{
    $name = contact_vcard_text($card['title']);
    $lines = array('BEGIN:VCARD', 'VERSION:3.0', 'FN:' . $name,
        'N:' . $name . ';;;;', 'TITLE:' . contact_vcard_text($card['sub_title']),
        'NOTE:' . contact_vcard_text($card['description']));

    if ($photo_path && is_readable($photo_path)) {
        $image = @getimagesize($photo_path);
        $types = array(IMAGETYPE_JPEG => 'JPEG', IMAGETYPE_PNG => 'PNG', IMAGETYPE_GIF => 'GIF');
        if ($image && isset($types[$image[2]])) {
            $lines[] = 'PHOTO;ENCODING=b;TYPE=' . $types[$image[2]] . ':' . base64_encode(file_get_contents($photo_path));
        }
    }

    $prefixes = array('facebook' => 'https://facebook.com/', 'twitter' => 'https://twitter.com/',
        'instagram' => 'https://instagram.com/', 'whatsapp' => 'https://api.whatsapp.com/send?phone=',
        'telegram' => 'https://telegram.me/', 'signal' => 'https://signal.me/',
        'snapchat' => 'https://www.snapchat.com/add/', 'pinterest' => 'https://pinterest.com/',
        'soundcloud' => 'https://soundcloud.com/', 'vimeo' => 'https://vimeo.com/',
        'dribbble' => 'https://dribbble.com/', 'behance' => 'https://www.behance.net/',
        'flickr' => 'https://flickr.com/', 'youtube' => 'https://youtube.com/',
        'tiktok' => 'https://www.tiktok.com/@', 'discord' => 'https://discord.gg/',
        'twitch' => 'https://www.twitch.tv/', 'github' => 'https://github.com/',
        'paypal' => 'https://paypal.me/');
    $details = json_decode($card['details'] ?: '[]', true);
    $count = 0;
    foreach (is_array($details) ? $details : array() as $detail) {
        if ($count++ >= (int) $field_limit) {
            break;
        }
        if (!isset($detail['type'], $detail['value']) || !is_scalar($detail['value'])) {
            continue;
        }
        $type = $detail['type'];
        $value = contact_vcard_text($detail['value']);
        switch ($type) {
            case 'phone': $lines[] = 'TEL:' . $value; break;
            case 'email': $lines[] = 'EMAIL;TYPE=INTERNET:' . $value; break;
            case 'address': $lines[] = 'ADR:;;' . $value . ';;;;'; break;
            case 'website':
            case 'linkedin': $lines[] = 'URL:' . $value; break;
            default:
                if (is_string($type) && isset($prefixes[$type])) {
                    $lines[] = 'URL:' . $prefixes[$type] . $value;
                }
        }
    }
    $lines[] = 'END:VCARD';
    // Fold at 75 bytes without splitting a UTF-8 character.
    $folded = array();
    foreach ($lines as $line) {
        while (strlen($line) > 75) {
            $length = 75;
            while ((ord($line[$length]) & 0xC0) === 0x80) {
                $length--;
            }
            $folded[] = substr($line, 0, $length);
            $line = ' ' . substr($line, $length);
        }
        $folded[] = $line;
    }
    return implode("\r\n", $folded) . "\r\n";
}
