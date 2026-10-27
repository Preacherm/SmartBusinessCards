(function ($) {
    "use strict";

    // Friendly, human readable names for each field type. Instead of showing a
    // long raw URL on the card we show the platform name (e.g. "Facebook") and
    // make the whole row a clickable link.
    var PLATFORM_NAMES = {
        phone: 'Phone', email: 'Email', address: 'Address', website: 'Website',
        text: '', facebook: 'Facebook', twitter: 'Twitter', instagram: 'Instagram',
        whatsapp: 'WhatsApp', telegram: 'Telegram', skype: 'Skype', wechat: 'WeChat',
        signal: 'Signal', snapchat: 'Snapchat', linkedin: 'LinkedIn', pinterest: 'Pinterest',
        soundcloud: 'SoundCloud', vimeo: 'Vimeo', dribbble: 'Dribbble', behance: 'Behance',
        flickr: 'Flickr', youtube: 'YouTube', tiktok: 'TikTok', discord: 'Discord',
        twitch: 'Twitch', github: 'GitHub', paypal: 'PayPal'
    };

    // For these types the value itself is short & meaningful, so we keep showing it.
    var SHOW_VALUE = ['phone', 'email', 'address', 'text'];
    // These open in a new browser tab.
    var EXTERNAL = ['website', 'facebook', 'twitter', 'instagram', 'telegram', 'signal',
        'snapchat', 'linkedin', 'pinterest', 'soundcloud', 'vimeo', 'dribbble', 'behance',
        'flickr', 'youtube', 'tiktok', 'discord', 'twitch', 'github', 'paypal', 'whatsapp'];

    // add card detail
    if (VCARD_DETAILS.length) {
        for (var i in VCARD_DETAILS) {
            if (VCARD_DETAILS.hasOwnProperty(i)) {
                if (DETAILS_FIELD_LIMIT == i) {
                    break;
                }

                let type = VCARD_DETAILS[i].type,
                    value = VCARD_DETAILS[i].value,
                    label = VCARD_DETAILS[i].label || '',
                    icon, link;

                value = $('<div>' + value + '</div>').text();
                label = $('<div>' + label + '</div>').text();

                if (type == 'text') {
                    value = value.replace(/(?:\r\n|\r|\n)/g, '<br>');
                    label = label.replace(/(?:\r\n|\r|\n)/g, '<br>');
                }

                switch (type) {
                    case 'phone':
                        icon = '<i class="fa fa-phone"></i>';
                        link = 'tel:' + value;
                        break;
                    case 'email':
                        icon = '<i class="fa fa-envelope"></i>';
                        link = 'mailto:' + value;
                        break;
                    case 'address':
                        icon = '<i class="fa fa-map-marker"></i>';
                        link = 'https://www.google.com/maps/search/' + value;
                        break;
                    case 'website':
                        icon = '<i class="fa fa-link"></i>';
                        link = value;
                        break;
                    case 'text':
                        icon = '<i class="fa fa-align-left"></i>';
                        link = 'javascript:void(0)';
                        break;
                    case 'facebook':
                        icon = '<i class="fa fa-facebook"></i>';
                        link = 'https://facebook.com/' + value;
                        break;
                    case 'twitter':
                        icon = '<i class="fa fa-twitter"></i>';
                        link = 'https://twitter.com/' + value;
                        break;
                    case 'instagram':
                        icon = '<i class="fa fa-instagram"></i>';
                        link = 'https://instagram.com/' + value;
                        break;
                    case 'whatsapp':
                        icon = '<i class="fa fa-whatsapp"></i>';
                        link = 'https://api.whatsapp.com/send?phone=' + value;
                        break;
                    case 'telegram':
                        icon = '<i class="fa fa-send"></i>';
                        link = 'https://telegram.me/' + value;
                        break;
                    case 'skype':
                        icon = '<i class="fa fa-skype"></i>';
                        link = 'skype:' + value;
                        break;
                    case 'wechat':
                        icon = '<i class="fa fa-wechat"></i>';
                        link = 'javascript:void(0)';
                        break;
                    case 'signal':
                        icon = '<i class="fa fa-comment"></i>';
                        link = 'https://signal.me/' + value;
                        break;
                    case 'snapchat':
                        icon = '<i class="fa fa-snapchat-ghost"></i>';
                        link = 'https://www.snapchat.com/add/' + value;
                        break;
                    case 'linkedin':
                        icon = '<i class="fa fa-linkedin"></i>';
                        link = value;
                        break;
                    case 'pinterest':
                        icon = '<i class="fa fa-pinterest"></i>';
                        link = 'https://pinterest.com/' + value;
                        break;
                    case 'soundcloud':
                        icon = '<i class="fa fa-soundcloud"></i>';
                        link = 'https://soundcloud.com/' + value;
                        break;
                    case 'vimeo':
                        icon = '<i class="fa fa-vimeo"></i>';
                        link = 'https://vimeo.com/' + value;
                        break;
                    case 'dribbble':
                        icon = '<i class="fa fa-dribbble"></i>';
                        link = 'https://dribbble.com/' + value;
                        break;
                    case 'behance':
                        icon = '<i class="fa fa-behance"></i>';
                        link = 'https://www.behance.net/' + value;
                        break;
                    case 'flickr':
                        icon = '<i class="fa fa-flickr"></i>';
                        link = 'https://flickr.com/' + value;
                        break;
                    case 'youtube':
                        icon = '<i class="fa fa-youtube-play"></i>';
                        link = 'https://youtube.com/' + value;
                        break;
                    case 'tiktok':
                        icon = '<i class="fa fa-music"></i>';
                        link = 'https://www.tiktok.com/@' + value;
                        break;
                    case 'discord':
                        icon = '<i class="fa fa-comments"></i>';
                        link = 'https://discord.gg/' + value;
                        break;
                    case 'twitch':
                        icon = '<i class="fa fa-twitch"></i>';
                        link = 'https://www.twitch.tv/' + value;
                        break;
                    case 'github':
                        icon = '<i class="fa fa-github"></i>';
                        link = 'https://github.com/' + value;
                        break;
                    case 'paypal':
                        icon = '<i class="fa fa-paypal"></i>';
                        link = 'https://paypal.me/' + value;
                        break;
                    default:
                        icon = '<i class="fa fa-circle"></i>';
                        link = 'javascript:void(0)';
                }

                // Decide what text to show. Contact-style fields (phone/email/
                // address/text) keep their actual value so it stays visible;
                // social fields show the platform's friendly name (e.g. "Facebook")
                // instead of a long raw URL. A custom label, when set, replaces the
                // platform name for social fields only.
                let primary;
                if (SHOW_VALUE.indexOf(type) !== -1) {
                    primary = value;
                } else {
                    primary = (label !== '') ? label : (PLATFORM_NAMES[type] || value);
                }

                let target = (EXTERNAL.indexOf(type) !== -1) ? ' target="_blank" rel="noopener"' : '';

                let $tpl = $('<li class="min-item vcard-type-' + type + '">' +
                    '<a href="' + link + '"' + target + ' class="min-item-link">' +
                    '<span class="min-item-icon">' + icon + '</span>' +
                    '<span class="min-item-text">' + primary + '</span>' +
                    '<span class="min-item-arrow"><i class="fa fa-angle-right"></i></span>' +
                    '</a>' +
                    '</li>');

                $('.vCard-list').append($tpl);
            }
        }
    }

})(jQuery);
