(function ($) {
    "use strict";

    // Shared script for Afriitech premium vCard themes (black-gold / rose-elegant
    // / professional-cover). Contact-style fields render as rows in `.vCard-list`;
    // social fields render as an icon bar in `.vCard-social` (falls back to the
    // list if a theme has no social bar). vCard (.vcf) download is preserved.

    var PLATFORM_NAMES = {
        phone: 'Phone', email: 'Email', address: 'Address', website: 'Website',
        text: '', facebook: 'Facebook', twitter: 'Twitter', instagram: 'Instagram',
        whatsapp: 'WhatsApp', telegram: 'Telegram', skype: 'Skype', wechat: 'WeChat',
        signal: 'Signal', snapchat: 'Snapchat', linkedin: 'LinkedIn', pinterest: 'Pinterest',
        soundcloud: 'SoundCloud', vimeo: 'Vimeo', dribbble: 'Dribbble', behance: 'Behance',
        flickr: 'Flickr', youtube: 'YouTube', tiktok: 'TikTok', discord: 'Discord',
        twitch: 'Twitch', github: 'GitHub', paypal: 'PayPal'
    };

    var ACTIONS = {
        phone: 'Call', email: 'Send email', address: 'View on map', website: 'Visit website',
        text: '', facebook: 'View profile', twitter: 'View profile', instagram: 'View profile',
        whatsapp: 'Send a message', telegram: 'Open chat', skype: 'Start a call', wechat: 'Connect',
        signal: 'Open chat', snapchat: 'Add', linkedin: 'View profile', pinterest: 'View profile',
        soundcloud: 'Listen', vimeo: 'Watch', dribbble: 'View work', behance: 'View work',
        flickr: 'View photos', youtube: 'Watch', tiktok: 'View profile', discord: 'Join',
        twitch: 'Watch', github: 'View profile', paypal: 'Send payment'
    };

    // Fields whose own value is short & meaningful — keep showing it.
    var SHOW_VALUE = ['phone', 'email', 'address', 'text'];
    // Fields shown as icons in the bottom social bar.
    var SOCIAL = ['facebook', 'twitter', 'instagram', 'whatsapp', 'telegram', 'skype',
        'wechat', 'signal', 'snapchat', 'linkedin', 'pinterest', 'soundcloud', 'vimeo',
        'dribbble', 'behance', 'flickr', 'youtube', 'tiktok', 'discord', 'twitch',
        'github', 'paypal'];
    // Open in a new tab.
    var EXTERNAL = ['website', 'facebook', 'twitter', 'instagram', 'telegram', 'signal',
        'snapchat', 'linkedin', 'pinterest', 'soundcloud', 'vimeo', 'dribbble', 'behance',
        'flickr', 'youtube', 'tiktok', 'discord', 'twitch', 'github', 'paypal', 'whatsapp'];

    var hasSocialBar = $('.vCard-social').length > 0;

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
                        icon = '<i class="fa fa-phone"></i>'; link = 'tel:' + value;
                    case 'email':
                        icon = '<i class="fa fa-envelope"></i>'; link = 'mailto:' + value;
                    case 'address':
                        icon = '<i class="fa fa-map-marker"></i>';
                        link = 'https://www.google.com/maps/search/' + value;
                    case 'website':
                        icon = '<i class="fa fa-link"></i>'; link = value;
                    case 'text':
                        icon = '<i class="fa fa-align-left"></i>'; link = 'javascript:void(0)'; break;
                    case 'facebook':
                        icon = '<i class="fa fa-facebook"></i>'; link = 'https://facebook.com/' + value;
                    case 'twitter':
                        icon = '<i class="fa fa-twitter"></i>'; link = 'https://twitter.com/' + value;
                    case 'instagram':
                        icon = '<i class="fa fa-instagram"></i>'; link = 'https://instagram.com/' + value;
                    case 'whatsapp':
                        icon = '<i class="fa fa-whatsapp"></i>';
                        link = 'https://api.whatsapp.com/send?phone=' + value;
                    case 'telegram':
                        icon = '<i class="fa fa-send"></i>'; link = 'https://telegram.me/' + value;
                    case 'skype':
                        icon = '<i class="fa fa-skype"></i>'; link = 'skype:' + value; break;
                    case 'wechat':
                        icon = '<i class="fa fa-wechat"></i>'; link = 'javascript:void(0)'; break;
                    case 'signal':
                        icon = '<i class="fa fa-comment"></i>'; link = 'https://signal.me/' + value;
                    case 'snapchat':
                        icon = '<i class="fa fa-snapchat-ghost"></i>';
                        link = 'https://www.snapchat.com/add/' + value;
                    case 'linkedin':
                        icon = '<i class="fa fa-linkedin"></i>'; link = value;
                    case 'pinterest':
                        icon = '<i class="fa fa-pinterest"></i>'; link = 'https://pinterest.com/' + value;
                    case 'soundcloud':
                        icon = '<i class="fa fa-soundcloud"></i>'; link = 'https://soundcloud.com/' + value;
                    case 'vimeo':
                        icon = '<i class="fa fa-vimeo"></i>'; link = 'https://vimeo.com/' + value;
                    case 'dribbble':
                        icon = '<i class="fa fa-dribbble"></i>'; link = 'https://dribbble.com/' + value;
                    case 'behance':
                        icon = '<i class="fa fa-behance"></i>'; link = 'https://www.behance.net/' + value;
                    case 'flickr':
                        icon = '<i class="fa fa-flickr"></i>'; link = 'https://flickr.com/' + value;
                    case 'youtube':
                        icon = '<i class="fa fa-youtube-play"></i>'; link = 'https://youtube.com/' + value;
                    case 'tiktok':
                        icon = '<i class="fa fa-music"></i>'; link = 'https://www.tiktok.com/@' + value;
                    case 'discord':
                        icon = '<i class="fa fa-comments"></i>'; link = 'https://discord.gg/' + value;
                    case 'twitch':
                        icon = '<i class="fa fa-twitch"></i>'; link = 'https://www.twitch.tv/' + value;
                    case 'github':
                        icon = '<i class="fa fa-github"></i>'; link = 'https://github.com/' + value;
                    case 'paypal':
                        icon = '<i class="fa fa-paypal"></i>'; link = 'https://paypal.me/' + value;
                    default:
                        icon = '<i class="fa fa-circle"></i>'; link = 'javascript:void(0)';
                }

                let target = (EXTERNAL.indexOf(type) !== -1) ? ' target="_blank" rel="noopener"' : '';

                // Social fields → compact icon bar (when the theme provides one).
                if (hasSocialBar && SOCIAL.indexOf(type) !== -1) {
                    let title = (label !== '') ? label : (PLATFORM_NAMES[type] || type);
                    let $soc = $('<a class="vc-social vcard-type-' + type + '" href="' + link + '"' + target +
                        ' title="' + title + '" aria-label="' + title + '">' + icon + '</a>');
                    $('.vCard-social').append($soc);
                    continue;
                }

                // Everything else → contact rows.
                let primary, hint;
                if (SHOW_VALUE.indexOf(type) !== -1) {
                    primary = value;
                    hint = (label !== '') ? label : (ACTIONS[type] || '');
                } else {
                    primary = (label !== '') ? label : (PLATFORM_NAMES[type] || value);
                    hint = ACTIONS[type] || '';
                }

                let $tpl = $('<li class="vc-item vcard-type-' + type + '">' +
                    '<a href="' + link + '"' + target + ' class="vc-link">' +
                    '<span class="vc-icon">' + icon + '</span>' +
                    '<span class="vc-text">' +
                    '<span class="vc-title">' + primary + '</span>' +
                    (hint !== '' ? '<span class="vc-hint">' + hint + '</span>' : '') +
                    '</span>' +
                    '<span class="vc-go"><i class="fa fa-angle-right"></i></span>' +
                    '</a>' +
                    '</li>');

                $('.vCard-list').append($tpl);
            }
        }
    }

})(jQuery);
