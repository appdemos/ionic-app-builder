/* global tinymce */

tinymce.PluginManager.add('gui_ion_quicktags', function (editor, url) {
    var each = tinymce.util.Tools.each;
	        var css_list = [url + '/assets/css/plugin.min.css'];
        var config = '';
        if (typeof editor.settings['gui_ion_quicktags'] === 'object') {
            var config = editor.settings['gui_ion_quicktags'];
        }
        var display_toolbar_text = true;
        if (typeof config === 'object') {
            if (typeof config.css !== 'undefined') {
                if (!config.css.exist) {
                    if (!config.css.external) {
                        css_list.push(url + '/assets/css/ionic.min.css');
                        if (window.galau_ui_debug === true) {
                            console.log('buttons => css: internal');
                        }
                    } else {
                        css_list.push(config.css.external);
                        if (window.galau_ui_debug === true) {
                            console.log('buttons => css: external');
                        }
                    }
                } else {
                    if (window.galau_ui_debug === true) {
                        console.log('buttons => css: exist');
                    }
                }
            } else {
                css_list.push(url + '/assets/css/ionic.min.css');
                if (window.galau_ui_debug === true) {
                    console.log('buttons => css: internal');
                }
            }
            if (config.toolbar_text) {
                display_toolbar_text = true;
            } else {
                display_toolbar_text = false;
            }
        } else {
            css_list.push(url + '/assets/css/ionic.min.css');
            if (window.galau_ui_debug === true) {
                console.log('buttons => css: internal');
            }
        }

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
		
    function applyItemFormat(format) {
        editor.undoManager.transact(function () {
            each('itemDivider itemTextWrap itemIconLeft itemIconRight itemIconRightLeft itemButtonLeft itemButtonRight itemButtonRightLeft itemAvatar itemThumbnailLeft itemThumbnailRight'.split(' '), function (name) {
                editor.formatter.remove(name);
            });
            editor.formatter.apply(format);
        });
    }
    
    function applyListFormat(format) {
        editor.undoManager.transact(function () {
            each('listNew listDefault listInset listCard'.split(' '), function (name) {
                editor.formatter.remove(name);
            });
            editor.formatter.apply(format);
        });
    }
    
    editor.on('init', function () {
        editor.addContextToolbar('.list .item', 'gui_ion_item remove');

        editor.formatter.register({
            itemNew: [{
                    inline: 'p',
                    classes: 'item',
                    ceFalseOverride: true
                }],
            itemDivider: [{
                    selector: '.list .item',
                    classes: 'item-divider'
                }],
            itemTextWrap: [{
                    selector: '.list .item',
                    classes: 'item-text-wrap'
                }],
            itemIconLeft: [{
                    selector: '.list .item',
                    classes: 'item-icon-left'
                }],
            itemIconRight: [{
                    selector: '.list .item',
                    classes: 'item-icon-right'
                }],
            itemIconRightLeft: [{
                    selector: '.list .item',
                    classes: ['item-icon-right', 'item-icon-left']
                }],
            itemButtonLeft: [{
                    selector: '.list .item',
                    classes: 'item-button-left'
                }],
            itemButtonRight: [{
                    selector: '.list .item',
                    classes: 'item-button-right'
                }],
            itemButtonRightLeft: [{
                    selector: '.list .item',
                    classes: ['item-button-right', 'item-button-left']
                }],
            itemAvatar: [{
                    selector: '.list .item',
                    classes: 'item-avatar'
                }],
            itemThumbnailLeft: [{
                    selector: '.list .item',
                    classes: 'item-thumbnail-left'
                }],
            itemThumbnailRight: [{
                    selector: '.list .item',
                    classes: 'item-thumbnail-right'
                }]
        });

        editor.settings.target_list = [{
                text: 'None',
                value: ''
            },
            {
                text: 'New window',
                value: '_blank'
            },
            {
                text: 'Top window',
                value: '_top'
            },
            {
                text: 'Self window',
                value: '_self'
            }];
            
        //LINK
        editor.settings.link_class_list = [{
                text: "None",
                value: " "
            },
            {
                text: "Button Default",
                value: "btn btn-default",
            },
            {
                text: "Button Primary",
                value: "btn btn-primary"
            },
            {
                text: "Button Info",
                value: "btn btn-info"
            },
            {
                text: "Button Warning",
                value: "btn btn-warning"
            },
            {
                text: "Button Danger",
                value: "btn btn-danger"
            },
            {
                text: "Button Link",
                value: "btn btn-link"
            }, ];

        editor.addContextToolbar('.list', 'gui_ion_list gui_ion_item_new remove');
        editor.formatter.register(
                {
                    listNew: [{
                            block: 'div',
                            classes: 'list'
                        }],
                    listDefault: [{
                            selector: '.list',
                            classes: '.list'
                        }],
                    listInset: [{
                            selector: '.list',
                            classes: '.list-inset'
                        }],
                    listCard: [{
                            selector: '.list',
                            classes: '.card'
                        }]
                });

    });

    editor.addButton('gui_ion_item_new', {
        text: 'new item',
        stateSelector: '.list',
        onclick:function(){
            applyItemFormat('itemNew');
        }
    });
    
    editor.addButton('gui_ion_item', {
        icon: 'guicon guicon guicon-ionicons',
        text: 'Ion Item',
        type: 'splitbutton',
        stateSelector: '.list .item',
        menu: [
            {
                text: 'Divider',
                onclick: function () {
                    applyItemFormat('itemDivider');
                }
            },
            {
                text: 'Text',
                onclick: function () {
                    applyItemFormat('itemTextWrap');
                }
            },
            {
                text: 'Icon',
                menu: [
                    {
                        text: 'Left',
                        onclick: function () {
                            applyItemFormat('itemIconLeft');
                        }
                    },
                    {
                        text: 'Right',
                        onclick: function () {
                            applyItemFormat('itemIconRight');
                        }
                    },
                    {
                        text: 'Left + Right',
                        onclick: function () {
                            applyItemFormat('itemIconRightLeft');
                        }
                    }
                ]
            },
            {
                text: 'Button',
                menu: [{
                        text: 'Left',
                        onclick: function () {
                            applyItemFormat('itemButtonLeft');
                        }
                    },
                    {
                        text: 'Right',
                        onclick: function () {
                            applyItemFormat('itemButtonRight');
                        }
                    },
                    {
                        text: 'Left + Right',
                        onclick: function () {
                            applyItemFormat('itemButtonLeftRight');
                        }
                    }
                ]
            },
            {
                text: 'Avatar',
                onclick: function () {
                    applyItemFormat('itemAvatar');
                }
            },
            {
                text: 'Thumbnail',
                menu: [
                    {
                        text: 'Left',
                        onclick: function () {
                            applyItemFormat('itemThumbnailLeft');
                        }
                    },
                    {
                        text: 'Right',
                        onclick: function () {
                            applyItemFormat('itemThumbnailRight');
                        }
                    }
                ]
            }
        ]
    });
    
    editor.addButton('gui_ion_list', {
        icon: 'guicon guicon guicon-ionicons',
        text: 'Ionic List',
        type: 'splitbutton',
        stateSelector: '.list',
        menu: [
            {
                text: 'New List',
                onclick: function () {
                    applyListFormat('listNew');
                }
            },
            {
                text: 'Default',
                onclick: function () {
                    applyListFormat('listDefault');
                }
            },
            {
                text: 'Inset',
                onclick: function () {
                    applyListFormat('listInset');
                }
            },
            {
                text: 'Card',
                onclick: function () {
                    applyListFormat('listCard');
                }
            }
        ]
    });
});