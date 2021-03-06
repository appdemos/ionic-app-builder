/* global tinymce */

tinymce.PluginManager.add('gui_ion_icons', function (editor, url) {
    var icon_text = 'Ionic Icons';
    var icon_selector = 'span.fi';
    var icon_name = 'gui_ion_icons';
    var icon_class = 'guicon guicon guicon-ionicons';
    var icon_command = 'showGuiIonicons';
    var css_list = [url + '/assets/css/plugin.min.css'];
    var galau_ui_title = 'Galau UI - Ionic Icons';
    var galau_ui_desc = 'Icon for ionic framework';
    var icon_list = [
        ["Alert", "ion ion-alert", "0xf101"],
        ["Alert Circled", "ion ion-alert-circled", "0xf100"],
        ["Android Add", "ion ion-android-add", "0xf2c7"],
        ["Android Add Circle", "ion ion-android-add-circle", "0xf359"],
        ["Android Alarm Clock", "ion ion-android-alarm-clock", "0xf35a"],
        ["Android Alert", "ion ion-android-alert", "0xf35b"],
        ["Android Apps", "ion ion-android-apps", "0xf35c"],
        ["Android Archive", "ion ion-android-archive", "0xf2c9"],
        ["Android Arrow Back", "ion ion-android-arrow-back", "0xf2ca"],
        ["Android Arrow Down", "ion ion-android-arrow-down", "0xf35d"],
        ["Android Arrow Dropdown", "ion ion-android-arrow-dropdown", "0xf35f"],
        ["Android Arrow Dropdown Circle", "ion ion-android-arrow-dropdown-circle", "0xf35e"],
        ["Android Arrow Dropleft", "ion ion-android-arrow-dropleft", "0xf361"],
        ["Android Arrow Dropleft Circle", "ion ion-android-arrow-dropleft-circle", "0xf360"],
        ["Android Arrow Dropright", "ion ion-android-arrow-dropright", "0xf363"],
        ["Android Arrow Dropright Circle", "ion ion-android-arrow-dropright-circle", "0xf362"],
        ["Android Arrow Dropup", "ion ion-android-arrow-dropup", "0xf365"],
        ["Android Arrow Dropup Circle", "ion ion-android-arrow-dropup-circle", "0xf364"],
        ["Android Arrow Forward", "ion ion-android-arrow-forward", "0xf30f"],
        ["Android Arrow Up", "ion ion-android-arrow-up", "0xf366"],
        ["Android Attach", "ion ion-android-attach", "0xf367"],
        ["Android Bar", "ion ion-android-bar", "0xf368"],
        ["Android Bicycle", "ion ion-android-bicycle", "0xf369"],
        ["Android Boat", "ion ion-android-boat", "0xf36a"],
        ["Android Bookmark", "ion ion-android-bookmark", "0xf36b"],
        ["Android Bulb", "ion ion-android-bulb", "0xf36c"],
        ["Android Bus", "ion ion-android-bus", "0xf36d"],
        ["Android Calendar", "ion ion-android-calendar", "0xf2d1"],
        ["Android Call", "ion ion-android-call", "0xf2d2"],
        ["Android Camera", "ion ion-android-camera", "0xf2d3"],
        ["Android Cancel", "ion ion-android-cancel", "0xf36e"],
        ["Android Car", "ion ion-android-car", "0xf36f"],
        ["Android Cart", "ion ion-android-cart", "0xf370"],
        ["Android Chat", "ion ion-android-chat", "0xf2d4"],
        ["Android Checkbox", "ion ion-android-checkbox", "0xf374"],
        ["Android Checkbox Blank", "ion ion-android-checkbox-blank", "0xf371"],
        ["Android Checkbox Outline", "ion ion-android-checkbox-outline", "0xf373"],
        ["Android Checkbox Outline Blank", "ion ion-android-checkbox-outline-blank", "0xf372"],
        ["Android Checkmark Circle", "ion ion-android-checkmark-circle", "0xf375"],
        ["Android Clipboard", "ion ion-android-clipboard", "0xf376"],
        ["Android Close", "ion ion-android-close", "0xf2d7"],
        ["Android Cloud", "ion ion-android-cloud", "0xf37a"],
        ["Android Cloud Circle", "ion ion-android-cloud-circle", "0xf377"],
        ["Android Cloud Done", "ion ion-android-cloud-done", "0xf378"],
        ["Android Cloud Outline", "ion ion-android-cloud-outline", "0xf379"],
        ["Android Color Palette", "ion ion-android-color-palette", "0xf37b"],
        ["Android Compass", "ion ion-android-compass", "0xf37c"],
        ["Android Contact", "ion ion-android-contact", "0xf2d8"],
        ["Android Contacts", "ion ion-android-contacts", "0xf2d9"],
        ["Android Contract", "ion ion-android-contract", "0xf37d"],
        ["Android Create", "ion ion-android-create", "0xf37e"],
        ["Android Delete", "ion ion-android-delete", "0xf37f"],
        ["Android Desktop", "ion ion-android-desktop", "0xf380"],
        ["Android Document", "ion ion-android-document", "0xf381"],
        ["Android Done", "ion ion-android-done", "0xf383"],
        ["Android Done All", "ion ion-android-done-all", "0xf382"],
        ["Android Download", "ion ion-android-download", "0xf2dd"],
        ["Android Drafts", "ion ion-android-drafts", "0xf384"],
        ["Android Exit", "ion ion-android-exit", "0xf385"],
        ["Android Expand", "ion ion-android-expand", "0xf386"],
        ["Android Favorite", "ion ion-android-favorite", "0xf388"],
        ["Android Favorite Outline", "ion ion-android-favorite-outline", "0xf387"],
        ["Android Film", "ion ion-android-film", "0xf389"],
        ["Android Folder", "ion ion-android-folder", "0xf2e0"],
        ["Android Folder Open", "ion ion-android-folder-open", "0xf38a"],
        ["Android Funnel", "ion ion-android-funnel", "0xf38b"],
        ["Android Globe", "ion ion-android-globe", "0xf38c"],
        ["Android Hand", "ion ion-android-hand", "0xf2e3"],
        ["Android Hangout", "ion ion-android-hangout", "0xf38d"],
        ["Android Happy", "ion ion-android-happy", "0xf38e"],
        ["Android Home", "ion ion-android-home", "0xf38f"],
        ["Android Image", "ion ion-android-image", "0xf2e4"],
        ["Android Laptop", "ion ion-android-laptop", "0xf390"],
        ["Android List", "ion ion-android-list", "0xf391"],
        ["Android Locate", "ion ion-android-locate", "0xf2e9"],
        ["Android Lock", "ion ion-android-lock", "0xf392"],
        ["Android Mail", "ion ion-android-mail", "0xf2eb"],
        ["Android Map", "ion ion-android-map", "0xf393"],
        ["Android Menu", "ion ion-android-menu", "0xf394"],
        ["Android Microphone", "ion ion-android-microphone", "0xf2ec"],
        ["Android Microphone Off", "ion ion-android-microphone-off", "0xf395"],
        ["Android More Horizontal", "ion ion-android-more-horizontal", "0xf396"],
        ["Android More Vertical", "ion ion-android-more-vertical", "0xf397"],
        ["Android Navigate", "ion ion-android-navigate", "0xf398"],
        ["Android Notifications", "ion ion-android-notifications", "0xf39b"],
        ["Android Notifications None", "ion ion-android-notifications-none", "0xf399"],
        ["Android Notifications Off", "ion ion-android-notifications-off", "0xf39a"],
        ["Android Open", "ion ion-android-open", "0xf39c"],
        ["Android Options", "ion ion-android-options", "0xf39d"],
        ["Android People", "ion ion-android-people", "0xf39e"],
        ["Android Person", "ion ion-android-person", "0xf3a0"],
        ["Android Person Add", "ion ion-android-person-add", "0xf39f"],
        ["Android Phone Landscape", "ion ion-android-phone-landscape", "0xf3a1"],
        ["Android Phone Portrait", "ion ion-android-phone-portrait", "0xf3a2"],
        ["Android Pin", "ion ion-android-pin", "0xf3a3"],
        ["Android Plane", "ion ion-android-plane", "0xf3a4"],
        ["Android Playstore", "ion ion-android-playstore", "0xf2f0"],
        ["Android Print", "ion ion-android-print", "0xf3a5"],
        ["Android Radio Button Off", "ion ion-android-radio-button-off", "0xf3a6"],
        ["Android Radio Button On", "ion ion-android-radio-button-on", "0xf3a7"],
        ["Android Refresh", "ion ion-android-refresh", "0xf3a8"],
        ["Android Remove", "ion ion-android-remove", "0xf2f4"],
        ["Android Remove Circle", "ion ion-android-remove-circle", "0xf3a9"],
        ["Android Restaurant", "ion ion-android-restaurant", "0xf3aa"],
        ["Android Sad", "ion ion-android-sad", "0xf3ab"],
        ["Android Search", "ion ion-android-search", "0xf2f5"],
        ["Android Send", "ion ion-android-send", "0xf2f6"],
        ["Android Settings", "ion ion-android-settings", "0xf2f7"],
        ["Android Share", "ion ion-android-share", "0xf2f8"],
        ["Android Share Alt", "ion ion-android-share-alt", "0xf3ac"],
        ["Android Star", "ion ion-android-star", "0xf2fc"],
        ["Android Star Half", "ion ion-android-star-half", "0xf3ad"],
        ["Android Star Outline", "ion ion-android-star-outline", "0xf3ae"],
        ["Android Stopwatch", "ion ion-android-stopwatch", "0xf2fd"],
        ["Android Subway", "ion ion-android-subway", "0xf3af"],
        ["Android Sunny", "ion ion-android-sunny", "0xf3b0"],
        ["Android Sync", "ion ion-android-sync", "0xf3b1"],
        ["Android Textsms", "ion ion-android-textsms", "0xf3b2"],
        ["Android Time", "ion ion-android-time", "0xf3b3"],
        ["Android Train", "ion ion-android-train", "0xf3b4"],
        ["Android Unlock", "ion ion-android-unlock", "0xf3b5"],
        ["Android Upload", "ion ion-android-upload", "0xf3b6"],
        ["Android Volume Down", "ion ion-android-volume-down", "0xf3b7"],
        ["Android Volume Mute", "ion ion-android-volume-mute", "0xf3b8"],
        ["Android Volume Off", "ion ion-android-volume-off", "0xf3b9"],
        ["Android Volume Up", "ion ion-android-volume-up", "0xf3ba"],
        ["Android Walk", "ion ion-android-walk", "0xf3bb"],
        ["Android Warning", "ion ion-android-warning", "0xf3bc"],
        ["Android Watch", "ion ion-android-watch", "0xf3bd"],
        ["Android Wifi", "ion ion-android-wifi", "0xf305"],
        ["Aperture", "ion ion-aperture", "0xf313"],
        ["Archive", "ion ion-archive", "0xf102"],
        ["Arrow Down A", "ion ion-arrow-down-a", "0xf103"],
        ["Arrow Down B", "ion ion-arrow-down-b", "0xf104"],
        ["Arrow Down C", "ion ion-arrow-down-c", "0xf105"],
        ["Arrow Expand", "ion ion-arrow-expand", "0xf25e"],
        ["Arrow Graph Down Left", "ion ion-arrow-graph-down-left", "0xf25f"],
        ["Arrow Graph Down Right", "ion ion-arrow-graph-down-right", "0xf260"],
        ["Arrow Graph Up Left", "ion ion-arrow-graph-up-left", "0xf261"],
        ["Arrow Graph Up Right", "ion ion-arrow-graph-up-right", "0xf262"],
        ["Arrow Left A", "ion ion-arrow-left-a", "0xf106"],
        ["Arrow Left B", "ion ion-arrow-left-b", "0xf107"],
        ["Arrow Left C", "ion ion-arrow-left-c", "0xf108"],
        ["Arrow Move", "ion ion-arrow-move", "0xf263"],
        ["Arrow Resize", "ion ion-arrow-resize", "0xf264"],
        ["Arrow Return Left", "ion ion-arrow-return-left", "0xf265"],
        ["Arrow Return Right", "ion ion-arrow-return-right", "0xf266"],
        ["Arrow Right A", "ion ion-arrow-right-a", "0xf109"],
        ["Arrow Right B", "ion ion-arrow-right-b", "0xf10a"],
        ["Arrow Right C", "ion ion-arrow-right-c", "0xf10b"],
        ["Arrow Shrink", "ion ion-arrow-shrink", "0xf267"],
        ["Arrow Swap", "ion ion-arrow-swap", "0xf268"],
        ["Arrow Up A", "ion ion-arrow-up-a", "0xf10c"],
        ["Arrow Up B", "ion ion-arrow-up-b", "0xf10d"],
        ["Arrow Up C", "ion ion-arrow-up-c", "0xf10e"],
        ["Asterisk", "ion ion-asterisk", "0xf314"],
        ["At", "ion ion-at", "0xf10f"],
        ["Backspace", "ion ion-backspace", "0xf3bf"],
        ["Backspace Outline", "ion ion-backspace-outline", "0xf3be"],
        ["Bag", "ion ion-bag", "0xf110"],
        ["Battery Charging", "ion ion-battery-charging", "0xf111"],
        ["Battery Empty", "ion ion-battery-empty", "0xf112"],
        ["Battery Full", "ion ion-battery-full", "0xf113"],
        ["Battery Half", "ion ion-battery-half", "0xf114"],
        ["Battery Low", "ion ion-battery-low", "0xf115"],
        ["Beaker", "ion ion-beaker", "0xf269"],
        ["Beer", "ion ion-beer", "0xf26a"],
        ["Bluetooth", "ion ion-bluetooth", "0xf116"],
        ["Bonfire", "ion ion-bonfire", "0xf315"],
        ["Bookmark", "ion ion-bookmark", "0xf26b"],
        ["Bowtie", "ion ion-bowtie", "0xf3c0"],
        ["Briefcase", "ion ion-briefcase", "0xf26c"],
        ["Bug", "ion ion-bug", "0xf2be"],
        ["Calculator", "ion ion-calculator", "0xf26d"],
        ["Calendar", "ion ion-calendar", "0xf117"],
        ["Camera", "ion ion-camera", "0xf118"],
        ["Card", "ion ion-card", "0xf119"],
        ["Cash", "ion ion-cash", "0xf316"],
        ["Chatbox", "ion ion-chatbox", "0xf11b"],
        ["Chatbox Working", "ion ion-chatbox-working", "0xf11a"],
        ["Chatboxes", "ion ion-chatboxes", "0xf11c"],
        ["Chatbubble", "ion ion-chatbubble", "0xf11e"],
        ["Chatbubble Working", "ion ion-chatbubble-working", "0xf11d"],
        ["Chatbubbles", "ion ion-chatbubbles", "0xf11f"],
        ["Checkmark", "ion ion-checkmark", "0xf122"],
        ["Checkmark Circled", "ion ion-checkmark-circled", "0xf120"],
        ["Checkmark Round", "ion ion-checkmark-round", "0xf121"],
        ["Chevron Down", "ion ion-chevron-down", "0xf123"],
        ["Chevron Left", "ion ion-chevron-left", "0xf124"],
        ["Chevron Right", "ion ion-chevron-right", "0xf125"],
        ["Chevron Up", "ion ion-chevron-up", "0xf126"],
        ["Clipboard", "ion ion-clipboard", "0xf127"],
        ["Clock", "ion ion-clock", "0xf26e"],
        ["Close", "ion ion-close", "0xf12a"],
        ["Close Circled", "ion ion-close-circled", "0xf128"],
        ["Close Round", "ion ion-close-round", "0xf129"],
        ["Closed Captioning", "ion ion-closed-captioning", "0xf317"],
        ["Cloud", "ion ion-cloud", "0xf12b"],
        ["Code", "ion ion-code", "0xf271"],
        ["Code Download", "ion ion-code-download", "0xf26f"],
        ["Code Working", "ion ion-code-working", "0xf270"],
        ["Coffee", "ion ion-coffee", "0xf272"],
        ["Compass", "ion ion-compass", "0xf273"],
        ["Compose", "ion ion-compose", "0xf12c"],
        ["Connection Bars", "ion ion-connection-bars", "0xf274"],
        ["Contrast", "ion ion-contrast", "0xf275"],
        ["Crop", "ion ion-crop", "0xf3c1"],
        ["Cube", "ion ion-cube", "0xf318"],
        ["Disc", "ion ion-disc", "0xf12d"],
        ["Document", "ion ion-document", "0xf12f"],
        ["Document Text", "ion ion-document-text", "0xf12e"],
        ["Drag", "ion ion-drag", "0xf130"],
        ["Earth", "ion ion-earth", "0xf276"],
        ["Easel", "ion ion-easel", "0xf3c2"],
        ["Edit", "ion ion-edit", "0xf2bf"],
        ["Egg", "ion ion-egg", "0xf277"],
        ["Eject", "ion ion-eject", "0xf131"],
        ["Email", "ion ion-email", "0xf132"],
        ["Email Unread", "ion ion-email-unread", "0xf3c3"],
        ["Erlenmeyer Flask", "ion ion-erlenmeyer-flask", "0xf3c5"],
        ["Erlenmeyer Flask Bubbles", "ion ion-erlenmeyer-flask-bubbles", "0xf3c4"],
        ["Eye", "ion ion-eye", "0xf133"],
        ["Eye Disabled", "ion ion-eye-disabled", "0xf306"],
        ["Female", "ion ion-female", "0xf278"],
        ["Filing", "ion ion-filing", "0xf134"],
        ["Film Marker", "ion ion-film-marker", "0xf135"],
        ["Fireball", "ion ion-fireball", "0xf319"],
        ["Flag", "ion ion-flag", "0xf279"],
        ["Flame", "ion ion-flame", "0xf31a"],
        ["Flash", "ion ion-flash", "0xf137"],
        ["Flash Off", "ion ion-flash-off", "0xf136"],
        ["Folder", "ion ion-folder", "0xf139"],
        ["Fork", "ion ion-fork", "0xf27a"],
        ["Fork Repo", "ion ion-fork-repo", "0xf2c0"],
        ["Forward", "ion ion-forward", "0xf13a"],
        ["Funnel", "ion ion-funnel", "0xf31b"],
        ["Gear A", "ion ion-gear-a", "0xf13d"],
        ["Gear B", "ion ion-gear-b", "0xf13e"],
        ["Grid", "ion ion-grid", "0xf13f"],
        ["Hammer", "ion ion-hammer", "0xf27b"],
        ["Happy", "ion ion-happy", "0xf31c"],
        ["Happy Outline", "ion ion-happy-outline", "0xf3c6"],
        ["Headphone", "ion ion-headphone", "0xf140"],
        ["Heart", "ion ion-heart", "0xf141"],
        ["Heart Broken", "ion ion-heart-broken", "0xf31d"],
        ["Help", "ion ion-help", "0xf143"],
        ["Help Buoy", "ion ion-help-buoy", "0xf27c"],
        ["Help Circled", "ion ion-help-circled", "0xf142"],
        ["Home", "ion ion-home", "0xf144"],
        ["Icecream", "ion ion-icecream", "0xf27d"],
        ["Image", "ion ion-image", "0xf147"],
        ["Images", "ion ion-images", "0xf148"],
        ["Information", "ion ion-information", "0xf14a"],
        ["Information Circled", "ion ion-information-circled", "0xf149"],
        ["Ionic", "ion ion-ionic", "0xf14b"],
        ["Ios Alarm", "ion ion-ios-alarm", "0xf3c8"],
        ["Ios Alarm Outline", "ion ion-ios-alarm-outline", "0xf3c7"],
        ["Ios Albums", "ion ion-ios-albums", "0xf3ca"],
        ["Ios Albums Outline", "ion ion-ios-albums-outline", "0xf3c9"],
        ["Ios Americanfootball", "ion ion-ios-americanfootball", "0xf3cc"],
        ["Ios Americanfootball Outline", "ion ion-ios-americanfootball-outline", "0xf3cb"],
        ["Ios Analytics", "ion ion-ios-analytics", "0xf3ce"],
        ["Ios Analytics Outline", "ion ion-ios-analytics-outline", "0xf3cd"],
        ["Ios Arrow Back", "ion ion-ios-arrow-back", "0xf3cf"],
        ["Ios Arrow Down", "ion ion-ios-arrow-down", "0xf3d0"],
        ["Ios Arrow Forward", "ion ion-ios-arrow-forward", "0xf3d1"],
        ["Ios Arrow Left", "ion ion-ios-arrow-left", "0xf3d2"],
        ["Ios Arrow Right", "ion ion-ios-arrow-right", "0xf3d3"],
        ["Ios Arrow Thin Down", "ion ion-ios-arrow-thin-down", "0xf3d4"],
        ["Ios Arrow Thin Left", "ion ion-ios-arrow-thin-left", "0xf3d5"],
        ["Ios Arrow Thin Right", "ion ion-ios-arrow-thin-right", "0xf3d6"],
        ["Ios Arrow Thin Up", "ion ion-ios-arrow-thin-up", "0xf3d7"],
        ["Ios Arrow Up", "ion ion-ios-arrow-up", "0xf3d8"],
        ["Ios At", "ion ion-ios-at", "0xf3da"],
        ["Ios At Outline", "ion ion-ios-at-outline", "0xf3d9"],
        ["Ios Barcode", "ion ion-ios-barcode", "0xf3dc"],
        ["Ios Barcode Outline", "ion ion-ios-barcode-outline", "0xf3db"],
        ["Ios Baseball", "ion ion-ios-baseball", "0xf3de"],
        ["Ios Baseball Outline", "ion ion-ios-baseball-outline", "0xf3dd"],
        ["Ios Basketball", "ion ion-ios-basketball", "0xf3e0"],
        ["Ios Basketball Outline", "ion ion-ios-basketball-outline", "0xf3df"],
        ["Ios Bell", "ion ion-ios-bell", "0xf3e2"],
        ["Ios Bell Outline", "ion ion-ios-bell-outline", "0xf3e1"],
        ["Ios Body", "ion ion-ios-body", "0xf3e4"],
        ["Ios Body Outline", "ion ion-ios-body-outline", "0xf3e3"],
        ["Ios Bolt", "ion ion-ios-bolt", "0xf3e6"],
        ["Ios Bolt Outline", "ion ion-ios-bolt-outline", "0xf3e5"],
        ["Ios Book", "ion ion-ios-book", "0xf3e8"],
        ["Ios Book Outline", "ion ion-ios-book-outline", "0xf3e7"],
        ["Ios Bookmarks", "ion ion-ios-bookmarks", "0xf3ea"],
        ["Ios Bookmarks Outline", "ion ion-ios-bookmarks-outline", "0xf3e9"],
        ["Ios Box", "ion ion-ios-box", "0xf3ec"],
        ["Ios Box Outline", "ion ion-ios-box-outline", "0xf3eb"],
        ["Ios Briefcase", "ion ion-ios-briefcase", "0xf3ee"],
        ["Ios Briefcase Outline", "ion ion-ios-briefcase-outline", "0xf3ed"],
        ["Ios Browsers", "ion ion-ios-browsers", "0xf3f0"],
        ["Ios Browsers Outline", "ion ion-ios-browsers-outline", "0xf3ef"],
        ["Ios Calculator", "ion ion-ios-calculator", "0xf3f2"],
        ["Ios Calculator Outline", "ion ion-ios-calculator-outline", "0xf3f1"],
        ["Ios Calendar", "ion ion-ios-calendar", "0xf3f4"],
        ["Ios Calendar Outline", "ion ion-ios-calendar-outline", "0xf3f3"],
        ["Ios Camera", "ion ion-ios-camera", "0xf3f6"],
        ["Ios Camera Outline", "ion ion-ios-camera-outline", "0xf3f5"],
        ["Ios Cart", "ion ion-ios-cart", "0xf3f8"],
        ["Ios Cart Outline", "ion ion-ios-cart-outline", "0xf3f7"],
        ["Ios Chatboxes", "ion ion-ios-chatboxes", "0xf3fa"],
        ["Ios Chatboxes Outline", "ion ion-ios-chatboxes-outline", "0xf3f9"],
        ["Ios Chatbubble", "ion ion-ios-chatbubble", "0xf3fc"],
        ["Ios Chatbubble Outline", "ion ion-ios-chatbubble-outline", "0xf3fb"],
        ["Ios Checkmark", "ion ion-ios-checkmark", "0xf3ff"],
        ["Ios Checkmark Empty", "ion ion-ios-checkmark-empty", "0xf3fd"],
        ["Ios Checkmark Outline", "ion ion-ios-checkmark-outline", "0xf3fe"],
        ["Ios Circle Filled", "ion ion-ios-circle-filled", "0xf400"],
        ["Ios Circle Outline", "ion ion-ios-circle-outline", "0xf401"],
        ["Ios Clock", "ion ion-ios-clock", "0xf403"],
        ["Ios Clock Outline", "ion ion-ios-clock-outline", "0xf402"],
        ["Ios Close", "ion ion-ios-close", "0xf406"],
        ["Ios Close Empty", "ion ion-ios-close-empty", "0xf404"],
        ["Ios Close Outline", "ion ion-ios-close-outline", "0xf405"],
        ["Ios Cloud", "ion ion-ios-cloud", "0xf40c"],
        ["Ios Cloud Download", "ion ion-ios-cloud-download", "0xf408"],
        ["Ios Cloud Download Outline", "ion ion-ios-cloud-download-outline", "0xf407"],
        ["Ios Cloud Outline", "ion ion-ios-cloud-outline", "0xf409"],
        ["Ios Cloud Upload", "ion ion-ios-cloud-upload", "0xf40b"],
        ["Ios Cloud Upload Outline", "ion ion-ios-cloud-upload-outline", "0xf40a"],
        ["Ios Cloudy", "ion ion-ios-cloudy", "0xf410"],
        ["Ios Cloudy Night", "ion ion-ios-cloudy-night", "0xf40e"],
        ["Ios Cloudy Night Outline", "ion ion-ios-cloudy-night-outline", "0xf40d"],
        ["Ios Cloudy Outline", "ion ion-ios-cloudy-outline", "0xf40f"],
        ["Ios Cog", "ion ion-ios-cog", "0xf412"],
        ["Ios Cog Outline", "ion ion-ios-cog-outline", "0xf411"],
        ["Ios Color Filter", "ion ion-ios-color-filter", "0xf414"],
        ["Ios Color Filter Outline", "ion ion-ios-color-filter-outline", "0xf413"],
        ["Ios Color Wand", "ion ion-ios-color-wand", "0xf416"],
        ["Ios Color Wand Outline", "ion ion-ios-color-wand-outline", "0xf415"],
        ["Ios Compose", "ion ion-ios-compose", "0xf418"],
        ["Ios Compose Outline", "ion ion-ios-compose-outline", "0xf417"],
        ["Ios Contact", "ion ion-ios-contact", "0xf41a"],
        ["Ios Contact Outline", "ion ion-ios-contact-outline", "0xf419"],
        ["Ios Copy", "ion ion-ios-copy", "0xf41c"],
        ["Ios Copy Outline", "ion ion-ios-copy-outline", "0xf41b"],
        ["Ios Crop", "ion ion-ios-crop", "0xf41e"],
        ["Ios Crop Strong", "ion ion-ios-crop-strong", "0xf41d"],
        ["Ios Download", "ion ion-ios-download", "0xf420"],
        ["Ios Download Outline", "ion ion-ios-download-outline", "0xf41f"],
        ["Ios Drag", "ion ion-ios-drag", "0xf421"],
        ["Ios Email", "ion ion-ios-email", "0xf423"],
        ["Ios Email Outline", "ion ion-ios-email-outline", "0xf422"],
        ["Ios Eye", "ion ion-ios-eye", "0xf425"],
        ["Ios Eye Outline", "ion ion-ios-eye-outline", "0xf424"],
        ["Ios Fastforward", "ion ion-ios-fastforward", "0xf427"],
        ["Ios Fastforward Outline", "ion ion-ios-fastforward-outline", "0xf426"],
        ["Ios Filing", "ion ion-ios-filing", "0xf429"],
        ["Ios Filing Outline", "ion ion-ios-filing-outline", "0xf428"],
        ["Ios Film", "ion ion-ios-film", "0xf42b"],
        ["Ios Film Outline", "ion ion-ios-film-outline", "0xf42a"],
        ["Ios Flag", "ion ion-ios-flag", "0xf42d"],
        ["Ios Flag Outline", "ion ion-ios-flag-outline", "0xf42c"],
        ["Ios Flame", "ion ion-ios-flame", "0xf42f"],
        ["Ios Flame Outline", "ion ion-ios-flame-outline", "0xf42e"],
        ["Ios Flask", "ion ion-ios-flask", "0xf431"],
        ["Ios Flask Outline", "ion ion-ios-flask-outline", "0xf430"],
        ["Ios Flower", "ion ion-ios-flower", "0xf433"],
        ["Ios Flower Outline", "ion ion-ios-flower-outline", "0xf432"],
        ["Ios Folder", "ion ion-ios-folder", "0xf435"],
        ["Ios Folder Outline", "ion ion-ios-folder-outline", "0xf434"],
        ["Ios Football", "ion ion-ios-football", "0xf437"],
        ["Ios Football Outline", "ion ion-ios-football-outline", "0xf436"],
        ["Ios Game Controller A", "ion ion-ios-game-controller-a", "0xf439"],
        ["Ios Game Controller A Outline", "ion ion-ios-game-controller-a-outline", "0xf438"],
        ["Ios Game Controller B", "ion ion-ios-game-controller-b", "0xf43b"],
        ["Ios Game Controller B Outline", "ion ion-ios-game-controller-b-outline", "0xf43a"],
        ["Ios Gear", "ion ion-ios-gear", "0xf43d"],
        ["Ios Gear Outline", "ion ion-ios-gear-outline", "0xf43c"],
        ["Ios Glasses", "ion ion-ios-glasses", "0xf43f"],
        ["Ios Glasses Outline", "ion ion-ios-glasses-outline", "0xf43e"],
        ["Ios Grid View", "ion ion-ios-grid-view", "0xf441"],
        ["Ios Grid View Outline", "ion ion-ios-grid-view-outline", "0xf440"],
        ["Ios Heart", "ion ion-ios-heart", "0xf443"],
        ["Ios Heart Outline", "ion ion-ios-heart-outline", "0xf442"],
        ["Ios Help", "ion ion-ios-help", "0xf446"],
        ["Ios Help Empty", "ion ion-ios-help-empty", "0xf444"],
        ["Ios Help Outline", "ion ion-ios-help-outline", "0xf445"],
        ["Ios Home", "ion ion-ios-home", "0xf448"],
        ["Ios Home Outline", "ion ion-ios-home-outline", "0xf447"],
        ["Ios Infinite", "ion ion-ios-infinite", "0xf44a"],
        ["Ios Infinite Outline", "ion ion-ios-infinite-outline", "0xf449"],
        ["Ios Information", "ion ion-ios-information", "0xf44d"],
        ["Ios Information Empty", "ion ion-ios-information-empty", "0xf44b"],
        ["Ios Information Outline", "ion ion-ios-information-outline", "0xf44c"],
        ["Ios Ionic Outline", "ion ion-ios-ionic-outline", "0xf44e"],
        ["Ios Keypad", "ion ion-ios-keypad", "0xf450"],
        ["Ios Keypad Outline", "ion ion-ios-keypad-outline", "0xf44f"],
        ["Ios Lightbulb", "ion ion-ios-lightbulb", "0xf452"],
        ["Ios Lightbulb Outline", "ion ion-ios-lightbulb-outline", "0xf451"],
        ["Ios List", "ion ion-ios-list", "0xf454"],
        ["Ios List Outline", "ion ion-ios-list-outline", "0xf453"],
        ["Ios Location", "ion ion-ios-location", "0xf456"],
        ["Ios Location Outline", "ion ion-ios-location-outline", "0xf455"],
        ["Ios Locked", "ion ion-ios-locked", "0xf458"],
        ["Ios Locked Outline", "ion ion-ios-locked-outline", "0xf457"],
        ["Ios Loop", "ion ion-ios-loop", "0xf45a"],
        ["Ios Loop Strong", "ion ion-ios-loop-strong", "0xf459"],
        ["Ios Medical", "ion ion-ios-medical", "0xf45c"],
        ["Ios Medical Outline", "ion ion-ios-medical-outline", "0xf45b"],
        ["Ios Medkit", "ion ion-ios-medkit", "0xf45e"],
        ["Ios Medkit Outline", "ion ion-ios-medkit-outline", "0xf45d"],
        ["Ios Mic", "ion ion-ios-mic", "0xf461"],
        ["Ios Mic Off", "ion ion-ios-mic-off", "0xf45f"],
        ["Ios Mic Outline", "ion ion-ios-mic-outline", "0xf460"],
        ["Ios Minus", "ion ion-ios-minus", "0xf464"],
        ["Ios Minus Empty", "ion ion-ios-minus-empty", "0xf462"],
        ["Ios Minus Outline", "ion ion-ios-minus-outline", "0xf463"],
        ["Ios Monitor", "ion ion-ios-monitor", "0xf466"],
        ["Ios Monitor Outline", "ion ion-ios-monitor-outline", "0xf465"],
        ["Ios Moon", "ion ion-ios-moon", "0xf468"],
        ["Ios Moon Outline", "ion ion-ios-moon-outline", "0xf467"],
        ["Ios More", "ion ion-ios-more", "0xf46a"],
        ["Ios More Outline", "ion ion-ios-more-outline", "0xf469"],
        ["Ios Musical Note", "ion ion-ios-musical-note", "0xf46b"],
        ["Ios Musical Notes", "ion ion-ios-musical-notes", "0xf46c"],
        ["Ios Navigate", "ion ion-ios-navigate", "0xf46e"],
        ["Ios Navigate Outline", "ion ion-ios-navigate-outline", "0xf46d"],
        ["Ios Nutrition", "ion ion-ios-nutrition", "0xf470"],
        ["Ios Nutrition Outline", "ion ion-ios-nutrition-outline", "0xf46f"],
        ["Ios Paper", "ion ion-ios-paper", "0xf472"],
        ["Ios Paper Outline", "ion ion-ios-paper-outline", "0xf471"],
        ["Ios Paperplane", "ion ion-ios-paperplane", "0xf474"],
        ["Ios Paperplane Outline", "ion ion-ios-paperplane-outline", "0xf473"],
        ["Ios Partlysunny", "ion ion-ios-partlysunny", "0xf476"],
        ["Ios Partlysunny Outline", "ion ion-ios-partlysunny-outline", "0xf475"],
        ["Ios Pause", "ion ion-ios-pause", "0xf478"],
        ["Ios Pause Outline", "ion ion-ios-pause-outline", "0xf477"],
        ["Ios Paw", "ion ion-ios-paw", "0xf47a"],
        ["Ios Paw Outline", "ion ion-ios-paw-outline", "0xf479"],
        ["Ios People", "ion ion-ios-people", "0xf47c"],
        ["Ios People Outline", "ion ion-ios-people-outline", "0xf47b"],
        ["Ios Person", "ion ion-ios-person", "0xf47e"],
        ["Ios Person Outline", "ion ion-ios-person-outline", "0xf47d"],
        ["Ios Personadd", "ion ion-ios-personadd", "0xf480"],
        ["Ios Personadd Outline", "ion ion-ios-personadd-outline", "0xf47f"],
        ["Ios Photos", "ion ion-ios-photos", "0xf482"],
        ["Ios Photos Outline", "ion ion-ios-photos-outline", "0xf481"],
        ["Ios Pie", "ion ion-ios-pie", "0xf484"],
        ["Ios Pie Outline", "ion ion-ios-pie-outline", "0xf483"],
        ["Ios Pint", "ion ion-ios-pint", "0xf486"],
        ["Ios Pint Outline", "ion ion-ios-pint-outline", "0xf485"],
        ["Ios Play", "ion ion-ios-play", "0xf488"],
        ["Ios Play Outline", "ion ion-ios-play-outline", "0xf487"],
        ["Ios Plus", "ion ion-ios-plus", "0xf48b"],
        ["Ios Plus Empty", "ion ion-ios-plus-empty", "0xf489"],
        ["Ios Plus Outline", "ion ion-ios-plus-outline", "0xf48a"],
        ["Ios Pricetag", "ion ion-ios-pricetag", "0xf48d"],
        ["Ios Pricetag Outline", "ion ion-ios-pricetag-outline", "0xf48c"],
        ["Ios Pricetags", "ion ion-ios-pricetags", "0xf48f"],
        ["Ios Pricetags Outline", "ion ion-ios-pricetags-outline", "0xf48e"],
        ["Ios Printer", "ion ion-ios-printer", "0xf491"],
        ["Ios Printer Outline", "ion ion-ios-printer-outline", "0xf490"],
        ["Ios Pulse", "ion ion-ios-pulse", "0xf493"],
        ["Ios Pulse Strong", "ion ion-ios-pulse-strong", "0xf492"],
        ["Ios Rainy", "ion ion-ios-rainy", "0xf495"],
        ["Ios Rainy Outline", "ion ion-ios-rainy-outline", "0xf494"],
        ["Ios Recording", "ion ion-ios-recording", "0xf497"],
        ["Ios Recording Outline", "ion ion-ios-recording-outline", "0xf496"],
        ["Ios Redo", "ion ion-ios-redo", "0xf499"],
        ["Ios Redo Outline", "ion ion-ios-redo-outline", "0xf498"],
        ["Ios Refresh", "ion ion-ios-refresh", "0xf49c"],
        ["Ios Refresh Empty", "ion ion-ios-refresh-empty", "0xf49a"],
        ["Ios Refresh Outline", "ion ion-ios-refresh-outline", "0xf49b"],
        ["Ios Reload", "ion ion-ios-reload", "0xf49d"],
        ["Ios Reverse Camera", "ion ion-ios-reverse-camera", "0xf49f"],
        ["Ios Reverse Camera Outline", "ion ion-ios-reverse-camera-outline", "0xf49e"],
        ["Ios Rewind", "ion ion-ios-rewind", "0xf4a1"],
        ["Ios Rewind Outline", "ion ion-ios-rewind-outline", "0xf4a0"],
        ["Ios Rose", "ion ion-ios-rose", "0xf4a3"],
        ["Ios Rose Outline", "ion ion-ios-rose-outline", "0xf4a2"],
        ["Ios Search", "ion ion-ios-search", "0xf4a5"],
        ["Ios Search Strong", "ion ion-ios-search-strong", "0xf4a4"],
        ["Ios Settings", "ion ion-ios-settings", "0xf4a7"],
        ["Ios Settings Strong", "ion ion-ios-settings-strong", "0xf4a6"],
        ["Ios Shuffle", "ion ion-ios-shuffle", "0xf4a9"],
        ["Ios Shuffle Strong", "ion ion-ios-shuffle-strong", "0xf4a8"],
        ["Ios Skipbackward", "ion ion-ios-skipbackward", "0xf4ab"],
        ["Ios Skipbackward Outline", "ion ion-ios-skipbackward-outline", "0xf4aa"],
        ["Ios Skipforward", "ion ion-ios-skipforward", "0xf4ad"],
        ["Ios Skipforward Outline", "ion ion-ios-skipforward-outline", "0xf4ac"],
        ["Ios Snowy", "ion ion-ios-snowy", "0xf4ae"],
        ["Ios Speedometer", "ion ion-ios-speedometer", "0xf4b0"],
        ["Ios Speedometer Outline", "ion ion-ios-speedometer-outline", "0xf4af"],
        ["Ios Star", "ion ion-ios-star", "0xf4b3"],
        ["Ios Star Half", "ion ion-ios-star-half", "0xf4b1"],
        ["Ios Star Outline", "ion ion-ios-star-outline", "0xf4b2"],
        ["Ios Stopwatch", "ion ion-ios-stopwatch", "0xf4b5"],
        ["Ios Stopwatch Outline", "ion ion-ios-stopwatch-outline", "0xf4b4"],
        ["Ios Sunny", "ion ion-ios-sunny", "0xf4b7"],
        ["Ios Sunny Outline", "ion ion-ios-sunny-outline", "0xf4b6"],
        ["Ios Telephone", "ion ion-ios-telephone", "0xf4b9"],
        ["Ios Telephone Outline", "ion ion-ios-telephone-outline", "0xf4b8"],
        ["Ios Tennisball", "ion ion-ios-tennisball", "0xf4bb"],
        ["Ios Tennisball Outline", "ion ion-ios-tennisball-outline", "0xf4ba"],
        ["Ios Thunderstorm", "ion ion-ios-thunderstorm", "0xf4bd"],
        ["Ios Thunderstorm Outline", "ion ion-ios-thunderstorm-outline", "0xf4bc"],
        ["Ios Time", "ion ion-ios-time", "0xf4bf"],
        ["Ios Time Outline", "ion ion-ios-time-outline", "0xf4be"],
        ["Ios Timer", "ion ion-ios-timer", "0xf4c1"],
        ["Ios Timer Outline", "ion ion-ios-timer-outline", "0xf4c0"],
        ["Ios Toggle", "ion ion-ios-toggle", "0xf4c3"],
        ["Ios Toggle Outline", "ion ion-ios-toggle-outline", "0xf4c2"],
        ["Ios Trash", "ion ion-ios-trash", "0xf4c5"],
        ["Ios Trash Outline", "ion ion-ios-trash-outline", "0xf4c4"],
        ["Ios Undo", "ion ion-ios-undo", "0xf4c7"],
        ["Ios Undo Outline", "ion ion-ios-undo-outline", "0xf4c6"],
        ["Ios Unlocked", "ion ion-ios-unlocked", "0xf4c9"],
        ["Ios Unlocked Outline", "ion ion-ios-unlocked-outline", "0xf4c8"],
        ["Ios Upload", "ion ion-ios-upload", "0xf4cb"],
        ["Ios Upload Outline", "ion ion-ios-upload-outline", "0xf4ca"],
        ["Ios Videocam", "ion ion-ios-videocam", "0xf4cd"],
        ["Ios Videocam Outline", "ion ion-ios-videocam-outline", "0xf4cc"],
        ["Ios Volume High", "ion ion-ios-volume-high", "0xf4ce"],
        ["Ios Volume Low", "ion ion-ios-volume-low", "0xf4cf"],
        ["Ios Wineglass", "ion ion-ios-wineglass", "0xf4d1"],
        ["Ios Wineglass Outline", "ion ion-ios-wineglass-outline", "0xf4d0"],
        ["Ios World", "ion ion-ios-world", "0xf4d3"],
        ["Ios World Outline", "ion ion-ios-world-outline", "0xf4d2"],
        ["Ipad", "ion ion-ipad", "0xf1f9"],
        ["Iphone", "ion ion-iphone", "0xf1fa"],
        ["Ipod", "ion ion-ipod", "0xf1fb"],
        ["Jet", "ion ion-jet", "0xf295"],
        ["Key", "ion ion-key", "0xf296"],
        ["Knife", "ion ion-knife", "0xf297"],
        ["Laptop", "ion ion-laptop", "0xf1fc"],
        ["Leaf", "ion ion-leaf", "0xf1fd"],
        ["Levels", "ion ion-levels", "0xf298"],
        ["Lightbulb", "ion ion-lightbulb", "0xf299"],
        ["Link", "ion ion-link", "0xf1fe"],
        ["Load A", "ion ion-load-a", "0xf29a"],
        ["Load B", "ion ion-load-b", "0xf29b"],
        ["Load C", "ion ion-load-c", "0xf29c"],
        ["Load D", "ion ion-load-d", "0xf29d"],
        ["Location", "ion ion-location", "0xf1ff"],
        ["Lock Combination", "ion ion-lock-combination", "0xf4d4"],
        ["Locked", "ion ion-locked", "0xf200"],
        ["Log In", "ion ion-log-in", "0xf29e"],
        ["Log Out", "ion ion-log-out", "0xf29f"],
        ["Loop", "ion ion-loop", "0xf201"],
        ["Magnet", "ion ion-magnet", "0xf2a0"],
        ["Male", "ion ion-male", "0xf2a1"],
        ["Man", "ion ion-man", "0xf202"],
        ["Map", "ion ion-map", "0xf203"],
        ["Medkit", "ion ion-medkit", "0xf2a2"],
        ["Merge", "ion ion-merge", "0xf33f"],
        ["Mic A", "ion ion-mic-a", "0xf204"],
        ["Mic B", "ion ion-mic-b", "0xf205"],
        ["Mic C", "ion ion-mic-c", "0xf206"],
        ["Minus", "ion ion-minus", "0xf209"],
        ["Minus Circled", "ion ion-minus-circled", "0xf207"],
        ["Minus Round", "ion ion-minus-round", "0xf208"],
        ["Model S", "ion ion-model-s", "0xf2c1"],
        ["Monitor", "ion ion-monitor", "0xf20a"],
        ["More", "ion ion-more", "0xf20b"],
        ["Mouse", "ion ion-mouse", "0xf340"],
        ["Music Note", "ion ion-music-note", "0xf20c"],
        ["Navicon", "ion ion-navicon", "0xf20e"],
        ["Navicon Round", "ion ion-navicon-round", "0xf20d"],
        ["Navigate", "ion ion-navigate", "0xf2a3"],
        ["Network", "ion ion-network", "0xf341"],
        ["No Smoking", "ion ion-no-smoking", "0xf2c2"],
        ["Nuclear", "ion ion-nuclear", "0xf2a4"],
        ["Outlet", "ion ion-outlet", "0xf342"],
        ["Paintbrush", "ion ion-paintbrush", "0xf4d5"],
        ["Paintbucket", "ion ion-paintbucket", "0xf4d6"],
        ["Paper Airplane", "ion ion-paper-airplane", "0xf2c3"],
        ["Paperclip", "ion ion-paperclip", "0xf20f"],
        ["Pause", "ion ion-pause", "0xf210"],
        ["Person", "ion ion-person", "0xf213"],
        ["Person Add", "ion ion-person-add", "0xf211"],
        ["Person Stalker", "ion ion-person-stalker", "0xf212"],
        ["Pie Graph", "ion ion-pie-graph", "0xf2a5"],
        ["Pin", "ion ion-pin", "0xf2a6"],
        ["Pinpoint", "ion ion-pinpoint", "0xf2a7"],
        ["Pizza", "ion ion-pizza", "0xf2a8"],
        ["Plane", "ion ion-plane", "0xf214"],
        ["Planet", "ion ion-planet", "0xf343"],
        ["Play", "ion ion-play", "0xf215"],
        ["Playstation", "ion ion-playstation", "0xf30a"],
        ["Plus", "ion ion-plus", "0xf218"],
        ["Plus Circled", "ion ion-plus-circled", "0xf216"],
        ["Plus Round", "ion ion-plus-round", "0xf217"],
        ["Podium", "ion ion-podium", "0xf344"],
        ["Pound", "ion ion-pound", "0xf219"],
        ["Power", "ion ion-power", "0xf2a9"],
        ["Pricetag", "ion ion-pricetag", "0xf2aa"],
        ["Pricetags", "ion ion-pricetags", "0xf2ab"],
        ["Printer", "ion ion-printer", "0xf21a"],
        ["Pull Request", "ion ion-pull-request", "0xf345"],
        ["Qr Scanner", "ion ion-qr-scanner", "0xf346"],
        ["Quote", "ion ion-quote", "0xf347"],
        ["Radio Waves", "ion ion-radio-waves", "0xf2ac"],
        ["Record", "ion ion-record", "0xf21b"],
        ["Refresh", "ion ion-refresh", "0xf21c"],
        ["Reply", "ion ion-reply", "0xf21e"],
        ["Reply All", "ion ion-reply-all", "0xf21d"],
        ["Ribbon A", "ion ion-ribbon-a", "0xf348"],
        ["Ribbon B", "ion ion-ribbon-b", "0xf349"],
        ["Sad", "ion ion-sad", "0xf34a"],
        ["Sad Outline", "ion ion-sad-outline", "0xf4d7"],
        ["Scissors", "ion ion-scissors", "0xf34b"],
        ["Search", "ion ion-search", "0xf21f"],
        ["Settings", "ion ion-settings", "0xf2ad"],
        ["Share", "ion ion-share", "0xf220"],
        ["Shuffle", "ion ion-shuffle", "0xf221"],
        ["Skip Backward", "ion ion-skip-backward", "0xf222"],
        ["Skip Forward", "ion ion-skip-forward", "0xf223"],
        ["Social Android", "ion ion-social-android", "0xf225"],
        ["Social Android Outline", "ion ion-social-android-outline", "0xf224"],
        ["Social Angular", "ion ion-social-angular", "0xf4d9"],
        ["Social Angular Outline", "ion ion-social-angular-outline", "0xf4d8"],
        ["Social Apple", "ion ion-social-apple", "0xf227"],
        ["Social Apple Outline", "ion ion-social-apple-outline", "0xf226"],
        ["Social Bitcoin", "ion ion-social-bitcoin", "0xf2af"],
        ["Social Bitcoin Outline", "ion ion-social-bitcoin-outline", "0xf2ae"],
        ["Social Buffer", "ion ion-social-buffer", "0xf229"],
        ["Social Buffer Outline", "ion ion-social-buffer-outline", "0xf228"],
        ["Social Chrome", "ion ion-social-chrome", "0xf4db"],
        ["Social Chrome Outline", "ion ion-social-chrome-outline", "0xf4da"],
        ["Social Codepen", "ion ion-social-codepen", "0xf4dd"],
        ["Social Codepen Outline", "ion ion-social-codepen-outline", "0xf4dc"],
        ["Social Css3", "ion ion-social-css3", "0xf4df"],
        ["Social Css3 Outline", "ion ion-social-css3-outline", "0xf4de"],
        ["Social Designernews", "ion ion-social-designernews", "0xf22b"],
        ["Social Designernews Outline", "ion ion-social-designernews-outline", "0xf22a"],
        ["Social Dribbble", "ion ion-social-dribbble", "0xf22d"],
        ["Social Dribbble Outline", "ion ion-social-dribbble-outline", "0xf22c"],
        ["Social Dropbox", "ion ion-social-dropbox", "0xf22f"],
        ["Social Dropbox Outline", "ion ion-social-dropbox-outline", "0xf22e"],
        ["Social Euro", "ion ion-social-euro", "0xf4e1"],
        ["Social Euro Outline", "ion ion-social-euro-outline", "0xf4e0"],
        ["Social Facebook", "ion ion-social-facebook", "0xf231"],
        ["Social Facebook Outline", "ion ion-social-facebook-outline", "0xf230"],
        ["Social Foursquare", "ion ion-social-foursquare", "0xf34d"],
        ["Social Foursquare Outline", "ion ion-social-foursquare-outline", "0xf34c"],
        ["Social Freebsd Devil", "ion ion-social-freebsd-devil", "0xf2c4"],
        ["Social Github", "ion ion-social-github", "0xf233"],
        ["Social Github Outline", "ion ion-social-github-outline", "0xf232"],
        ["Social Google", "ion ion-social-google", "0xf34f"],
        ["Social Google Outline", "ion ion-social-google-outline", "0xf34e"],
        ["Social Googleplus", "ion ion-social-googleplus", "0xf235"],
        ["Social Googleplus Outline", "ion ion-social-googleplus-outline", "0xf234"],
        ["Social Hackernews", "ion ion-social-hackernews", "0xf237"],
        ["Social Hackernews Outline", "ion ion-social-hackernews-outline", "0xf236"],
        ["Social Html5", "ion ion-social-html5", "0xf4e3"],
        ["Social Html5 Outline", "ion ion-social-html5-outline", "0xf4e2"],
        ["Social Instagram", "ion ion-social-instagram", "0xf351"],
        ["Social Instagram Outline", "ion ion-social-instagram-outline", "0xf350"],
        ["Social Javascript", "ion ion-social-javascript", "0xf4e5"],
        ["Social Javascript Outline", "ion ion-social-javascript-outline", "0xf4e4"],
        ["Social Linkedin", "ion ion-social-linkedin", "0xf239"],
        ["Social Linkedin Outline", "ion ion-social-linkedin-outline", "0xf238"],
        ["Social Markdown", "ion ion-social-markdown", "0xf4e6"],
        ["Social Nodejs", "ion ion-social-nodejs", "0xf4e7"],
        ["Social Octocat", "ion ion-social-octocat", "0xf4e8"],
        ["Social Pinterest", "ion ion-social-pinterest", "0xf2b1"],
        ["Social Pinterest Outline", "ion ion-social-pinterest-outline", "0xf2b0"],
        ["Social Python", "ion ion-social-python", "0xf4e9"],
        ["Social Reddit", "ion ion-social-reddit", "0xf23b"],
        ["Social Reddit Outline", "ion ion-social-reddit-outline", "0xf23a"],
        ["Social Rss", "ion ion-social-rss", "0xf23d"],
        ["Social Rss Outline", "ion ion-social-rss-outline", "0xf23c"],
        ["Social Sass", "ion ion-social-sass", "0xf4ea"],
        ["Social Skype", "ion ion-social-skype", "0xf23f"],
        ["Social Skype Outline", "ion ion-social-skype-outline", "0xf23e"],
        ["Social Snapchat", "ion ion-social-snapchat", "0xf4ec"],
        ["Social Snapchat Outline", "ion ion-social-snapchat-outline", "0xf4eb"],
        ["Social Tumblr", "ion ion-social-tumblr", "0xf241"],
        ["Social Tumblr Outline", "ion ion-social-tumblr-outline", "0xf240"],
        ["Social Tux", "ion ion-social-tux", "0xf2c5"],
        ["Social Twitch", "ion ion-social-twitch", "0xf4ee"],
        ["Social Twitch Outline", "ion ion-social-twitch-outline", "0xf4ed"],
        ["Social Twitter", "ion ion-social-twitter", "0xf243"],
        ["Social Twitter Outline", "ion ion-social-twitter-outline", "0xf242"],
        ["Social Usd", "ion ion-social-usd", "0xf353"],
        ["Social Usd Outline", "ion ion-social-usd-outline", "0xf352"],
        ["Social Vimeo", "ion ion-social-vimeo", "0xf245"],
        ["Social Vimeo Outline", "ion ion-social-vimeo-outline", "0xf244"],
        ["Social Whatsapp", "ion ion-social-whatsapp", "0xf4f0"],
        ["Social Whatsapp Outline", "ion ion-social-whatsapp-outline", "0xf4ef"],
        ["Social Windows", "ion ion-social-windows", "0xf247"],
        ["Social Windows Outline", "ion ion-social-windows-outline", "0xf246"],
        ["Social Wordpress", "ion ion-social-wordpress", "0xf249"],
        ["Social Wordpress Outline", "ion ion-social-wordpress-outline", "0xf248"],
        ["Social Yahoo", "ion ion-social-yahoo", "0xf24b"],
        ["Social Yahoo Outline", "ion ion-social-yahoo-outline", "0xf24a"],
        ["Social Yen", "ion ion-social-yen", "0xf4f2"],
        ["Social Yen Outline", "ion ion-social-yen-outline", "0xf4f1"],
        ["Social Youtube", "ion ion-social-youtube", "0xf24d"],
        ["Social Youtube Outline", "ion ion-social-youtube-outline", "0xf24c"],
        ["Soup Can", "ion ion-soup-can", "0xf4f4"],
        ["Soup Can Outline", "ion ion-soup-can-outline", "0xf4f3"],
        ["Speakerphone", "ion ion-speakerphone", "0xf2b2"],
        ["Speedometer", "ion ion-speedometer", "0xf2b3"],
        ["Spoon", "ion ion-spoon", "0xf2b4"],
        ["Star", "ion ion-star", "0xf24e"],
        ["Stats Bars", "ion ion-stats-bars", "0xf2b5"],
        ["Steam", "ion ion-steam", "0xf30b"],
        ["Stop", "ion ion-stop", "0xf24f"],
        ["Thermometer", "ion ion-thermometer", "0xf2b6"],
        ["Thumbsdown", "ion ion-thumbsdown", "0xf250"],
        ["Thumbsup", "ion ion-thumbsup", "0xf251"],
        ["Toggle", "ion ion-toggle", "0xf355"],
        ["Toggle Filled", "ion ion-toggle-filled", "0xf354"],
        ["Transgender", "ion ion-transgender", "0xf4f5"],
        ["Trash A", "ion ion-trash-a", "0xf252"],
        ["Trash B", "ion ion-trash-b", "0xf253"],
        ["Trophy", "ion ion-trophy", "0xf356"],
        ["Tshirt", "ion ion-tshirt", "0xf4f7"],
        ["Tshirt Outline", "ion ion-tshirt-outline", "0xf4f6"],
        ["Umbrella", "ion ion-umbrella", "0xf2b7"],
        ["University", "ion ion-university", "0xf357"],
        ["Unlocked", "ion ion-unlocked", "0xf254"],
        ["Upload", "ion ion-upload", "0xf255"],
        ["Usb", "ion ion-usb", "0xf2b8"],
        ["Videocamera", "ion ion-videocamera", "0xf256"],
        ["Volume High", "ion ion-volume-high", "0xf257"],
        ["Volume Low", "ion ion-volume-low", "0xf258"],
        ["Volume Medium", "ion ion-volume-medium", "0xf259"],
        ["Volume Mute", "ion ion-volume-mute", "0xf25a"],
        ["Wand", "ion ion-wand", "0xf358"],
        ["Waterdrop", "ion ion-waterdrop", "0xf25b"],
        ["Wifi", "ion ion-wifi", "0xf25c"],
        ["Wineglass", "ion ion-wineglass", "0xf2b9"],
        ["Woman", "ion ion-woman", "0xf25d"],
        ["Wrench", "ion ion-wrench", "0xf2ba"],
        ["Xbox", "ion ion-xbox", "0xf30c"]
    ];
    var config = '';
    if (typeof editor.settings[icon_name] === 'object') {
        var config = editor.settings[icon_name];
    }
    var display_menu = true;
    var display_toolbar_text = true;
    if (typeof config === 'object') {
        if (typeof config.css !== 'undefined') {
            if (!config.css.exist) {
                if (!config.css.external) {
                    css_list.push(url + '/assets/css/ionicons.min.css');
                    if (window.galau_ui_debug === true) {
                        console.log('ionicons => css : internal');
                    }
                } else {
                    css_list.push(config.css.external);
                    if (window.galau_ui_debug === true) {
                        console.log('ionicons => css : external');
                    }
                }
            } else {
                if (window.galau_ui_debug === true) {
                    console.log('ionicons => css : exist');
                }
            }
        } else {
            css_list.push(url + '/assets/css/ionicons.min.css');
            if (window.galau_ui_debug === true) {
                console.log('ionicons => css : internal');
            }
        }
        if (config.toolbar_text) {
            display_toolbar_text = true;
        } else {
            display_toolbar_text = false;
        }
        if (config.menu) {
            display_menu = true;
        } else {
            display_menu = false;
        }
    } else {
        css_list.push(url + '/assets/css/ionicons.min.css');
        if (window.galau_ui_debug === true) {
            console.log('ionicons => css : internal');
        }
    }

    function showDialog(callback) {
        if (!callback) {
            callback = false;
        }
        //set current icon
        var selection = editor.selection;
        var dom = editor.dom;
        //window.console && console.log(icon_class);

        function getParentTd(elm) {
            while (elm) {
                if (elm.nodeName === 'TD') {
                    return elm;
                }
                elm = elm.parentNode;
            }
        }

        function displayIcons(icons_list, obj) {
            var newTable, gridHtml, x, y, win;
            gridHtml = '<table role="presentation" cellspacing="0" ><tbody>';
            var width = 12;
            var height = Math.ceil(icons_list.length / width);
            for (y = 0; y < height; y++) {
                gridHtml += '<tr>';
                for (x = 0; x < width; x++) {
                    var index = y * width + x;
                    if (index < icons_list.length) {
                        var chr = icons_list[index];
                        gridHtml += '<td title="' + chr[0] + '" data-icon="' + chr[1] + '" ><div tabindex="-1" title="' + chr[0] + '" role="button"><span class="' + chr[1] + '"></span></div></td>';
                    } else {
                        gridHtml += '<td />';
                    }
                }
                gridHtml += '</tr>';
            }
            gridHtml += '</tbody></table>';
            if (obj === true) {
                newTable = document.createElement('div');
                newTable.setAttribute('id', 'icon-table');
                newTable.setAttribute('class', 'mce-icon-table');
                newTable.innerHTML = gridHtml;
            } else {
                newTable = '<div class="mce-icon-table" id="icon-table">';
                newTable += gridHtml;
                newTable += '</div>';
            }
            return newTable;
        }

        function onSearch(keyword) {
            var filter = [];
            //icon_list
            for (var x = 0; x < icon_list.length; x++) {
                var chr = icon_list[x];
                if (chr[1].toLowerCase().indexOf(keyword) >= 0) {
                    filter.push(chr);
                }
            }
            ;
            var newTable = displayIcons(filter, true);
            var oldTable = document.querySelector('#icon-table');
            oldTable.parentNode.replaceChild(newTable, oldTable);
            //window.console && console.log(newTable);
        }
        win = editor.windowManager.open({
            title: galau_ui_title,
            classes: icon_name + '-panel',
            bodyType: "tabpanel",
            body: [{
                    title: "General",
                    type: 'container',
                    layout: 'flex',
                    spacing: 10,
                    padding: 10,
                    items: [{
                            type: 'container',
                            classes: 'icon-table',
                            html: '<div class="mce-icon-box" id="icon-box">' + displayIcons(icon_list, false) + '</div>',
                            spacing: 10,
                            minHeight: 300,
                            minWidth: 400,
                            onclick: function (e) {
                                var td = getParentTd(e.target);
                                if (typeof callback === 'string') {
                                    editor.settings[callback](td.getAttribute('data-icon'));
                                    win.close();
                                } else {
                                    var icon_markup = '<span class="icon ' + td.getAttribute('data-icon') + '"></span> <span data-mce-bogus="1"/>';
                                    editor.execCommand('mceInsertContent', false, icon_markup);
                                    if (!e.ctrlKey) {
                                        win.close();
                                    }
                                }
                            },
                            onmouseover: function (e) {
                                var td = getParentTd(e.target);
                                var preview = document.getElementById('icon_preview');
                                if (td && td.firstChild) {
                                    preview.setAttribute('class', td.getAttribute('data-icon'));
                                    win.find('#icon_title_preview').text(td.title);
                                } else {
                                    preview.setAttribute('class', ' ');
                                    win.find('#icon_title_preview').text(' ');
                                }
                            }
                        },
                        {
                            type: 'container',
                            layout: 'flex',
                            direction: 'column',
                            align: 'center',
                            spacing: 5,
                            minWidth: 160,
                            minHeight: 40,
                            items: [{
                                    type: 'panel',
                                    name: 'preview',
                                    html: '<span style="margin:10px;font-size:60px;width:60px;height:60px;text-align: center" id="icon_preview"></span>',
                                    style: 'text-align:center;background:#fff;',
                                    border: 1,
                                    width: 80,
                                    minHeight: 80
                                },
                                {
                                    type: 'label',
                                    name: 'icon_title_preview',
                                    text: ' ',
                                    style: 'text-align: center',
                                    border: 1,
                                    minWidth: 140,
                                    minHeight: 36
                                }]
                        }]
                },
                {
                    title: "About",
                    type: "form",
                    layout: "grid",
                    items: [{
                            type: "panel",
                            classes: 'about-us',
                            html: "<h2>" + galau_ui_title + "</h2><h4>Created by <a href='http://ihsana.com/jasman/'>Jasman</a></h4><p>" + galau_ui_desc + "</p>",
                            style: "background-color:transparent"
                        }]
                }],
            buttons: [{
                    text: "Close",
                    onclick: function () {
                        win.close();
                    }
                }]
        });
        var selectedElm = selection.getNode();
        var spanElm = dom.getParent(selectedElm, 'span[class]');
        if ((value = dom.getAttrib(spanElm, 'class'))) {
            var preview = document.querySelector('#icon_preview');
            preview.setAttribute('class', value);
        }
        var footPanel = document.querySelector('.mce-' + icon_name + '-panel .mce-foot .mce-container-body');
        var search_icon = tinymce.ui.Factory.create({
            type: 'container',
            classes: 'icon-search-container',
            items: [{
                    type: 'textbox',
                    onkeyup: function (e) {
                        onSearch(e.target.value);
                    },
                    label: 'Search',
                    size: 24
                }]
        }).renderTo(footPanel).reflow();
    }
    // inline menu icon
    editor.addButton(icon_name + '_remove', {
        icon: 'remove',
        onclick: function () {
            var $_ = tinymce.dom.DomQuery;
            var spanElm = editor.dom.getParent(editor.selection.getStart(), icon_selector);
            if (spanElm) {
                editor.undoManager.transact(function () {
                    $_(spanElm).replaceWith('');
                });
            }
        }
    });
    editor.on('init', function () {
        editor.addContextToolbar(icon_selector, icon_name + ' undo redo | ' + icon_name + '_remove');
    });
    // Include CSS
    if (typeof editor.settings.content_css !== 'undefined') {
        if (typeof editor.settings.content_css.push === "function") {
            for (var i = 0; i < css_list.length; i++) {
                editor.settings.content_css.push(css_list[i]);
            }
            ;
        } else if (typeof editor.settings.content_css === "string") {
            editor.settings.content_css = [editor.settings.content_css];
            for (var i = 0; i < css_list.length; i++) {
                editor.settings.content_css.push(css_list[i]);
            }
            ;
        } else {
            editor.settings.content_css = css_list;
        }
    } else {
        editor.settings.content_css = css_list;
    }
    // Allow elements
    if (typeof editor.settings.extended_valid_elements === 'undefined') {
        editor.settings.extended_valid_elements = '*[*]';
    }
    if (typeof editor.settings.valid_elements === 'undefined') {
        editor.settings.valid_elements = '*[*]';
    }
    if (window.galau_ui_debug === true) {
        console.log('ionicons => valid: ', editor.settings.valid_elements);
        console.log('ionicons => extended_valid: ', editor.settings.extended_valid_elements);
    }
    // Include CSS
    editor.on('init', function () {
        if (document.createStyleSheet) {
            for (var i = 0; i < css_list.length; i++) {
                document.createStyleSheet(css_list[i]);
            }
        } else {
            for (var i = 0; i < css_list.length; i++) {
                cssLink = editor.dom.create('link', {
                    rel: 'stylesheet',
                    href: css_list[i]
                });
                document.getElementsByTagName('head')[0].appendChild(cssLink);
            }
        }
    });
    var toolbar_text = '';
    if (display_toolbar_text) {
        toolbar_text = icon_text;
    }
    editor.addCommand(icon_command, showDialog);
    // Add to button
    editor.addButton(icon_name, {
        icon: icon_class,
        text: toolbar_text,
        tooltip: icon_text,
        cmd: icon_command,
        stateSelector: icon_selector
    });
    if (display_menu === true) {
        // Add to menu
        editor.addMenuItem(icon_name, {
            icon: icon_class,
            text: icon_text,
            cmd: icon_command,
            stateSelector: icon_selector,
            context: 'insert'
        });
    }
    //callback
    if (!editor.settings[icon_command]) {
        editor.settings[icon_command] = showDialog;
    }
    var iconPicker = [{
            value: 'none',
            text: 'None'
        }];
    //register to iconPicker
    if (typeof editor.settings.gui_icon_picker === 'object') {
        iconPicker = editor.settings.gui_icon_picker;
    }
    iconPicker.push({
        value: icon_command,
        text: icon_text
    });
    editor.settings.gui_icon_picker = iconPicker;
});