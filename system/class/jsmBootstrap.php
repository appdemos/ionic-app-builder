<?php

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2016
 * @license Commercial License
 */

if (!defined('JSM_EXEC'))
{
    die(':)');
}

class jsmBootstrap
{
    var $enter = "\r\n";
    var $tab = "\t";
    /**
     * jsmBootstrap::Button()
     *
     * @param string $id = 'button-id'
     * @param string $tag = 'anchor' || 'submit' || 'button'
     * @param string $text = 'label'
     * @param string $color = 'default' || 'primary' || 'warning' || 'danger'
     * @param string $Icons = 'home' || Bootstrap::showOption('icon')
     * @param string $link = 'http://google.com/'
     * @param string $size = 'lg' || 'sm' || 'md' || 'xs'
     * @param string $attr = 'disabled'
     * @return string
     */
    public function Button($id = "button-id", $tag = 'anchor', $text = 'Text', $color = 'default', $Icons = null, $link = null, $size = "md", $attr = null, $class = null, $title = null, $helper = null, $helper_placement = 'top', $helper_content = null, $font = 'glyphicon')
    {
        $_helper = null;
        if ($helper == 'tooltip')
        {
            $_helper = 'data-toggle="tooltip" data-placement="' . $helper_placement . '"';
        }
        if ($helper == 'popover')
        {
            $_helper = 'data-container="body" data-toggle="popover" data-placement="' . $helper_placement . '" data-content="' . $helper_content . '"';
        }
        $output = null;
        $icon = null;
        if ($Icons != null)
        {
            $icon = $this->Icon(null, $Icons, null, null, null, $font);
        }
        $_name = null;
        $_id = null;
        if ($id != null)
        {
            $_id = 'id="' . $id . '"';
            $_name = 'name="' . $id . '"';
        }
        $_title = null;
        if ($title != null)
        {
            $_title = 'title="' . $title . '"';
        }
        $_href = null;
        if ($link != null)
        {
            $_href = 'href="' . $link . '"';
        }
        $output = null;
        switch ($tag)
        {
            case 'anchor':
                $output .= '<a ' . $_href . ' ' . $_id . ' class="' . $class . ' btn btn-' . $color . ' btn-' . $size . '" ' . $attr . ' ' . $_title . ' ' . $_helper . '>' . $icon . '' . $text . '</a>' . $this->enter;
                break;
            case 'submit':
                $output .= '<input type="submit" ' . $_name . ' ' . $_id . ' class="' . $class . ' btn btn-' . $color . ' btn-' . $size . '" value="' . $text . '" ' . $attr . ' ' . $_title . ' ' . $_helper . '/>' . $this->enter;
                break;
            case 'button':
                $output .= '<button type="button" ' . $_id . ' ' . $_href . ' class="' . $class . ' btn btn-' . $color . ' btn-' . $size . '" ' . $attr . ' ' . $_title . ' ' . $_helper . '>' . $icon . '' . $text . '</button>' . $this->enter;
                break;
            case 'reset':
                $output .= '<input type="reset" ' . $_id . '  class="' . $class . ' btn btn-' . $color . ' btn-' . $size . '" value="' . $text . '" ' . $attr . ' ' . $_title . ' ' . $_helper . '/>' . $this->enter;
                break;
        }
        return $output;
    }
    /**
     * jsmBootstrap::Icon()
     *
     * @param string $Icon = Bootstrap::showOption('Icon')
     * @param string $text = null;
     * @param string $class = null;
     * @param string $attr = null;
     * @return string
     */
    public function Icon($id = 'icon-id', $Icon = 'asterisk', $text = null, $class = null, $attr = null, $font = "glyphicon")
    {
        $_id = null;
        if ($id != null)
        {
            $_id = 'id="' . $id . '"';
        }
        return '<span ' . $_id . ' class="' . $font . ' ' . $font . '-' . $Icon . ' ' . $class . '" ' . $attr . '>' . $text . '</span>' . $this->enter;
    }
    /**
     * jsmBootstrap::showOption()
     *
     * @param string $option = 'Icon'
     * @return
     */
    public function showOption($option)
    {
        $output = '<div class="row">' . $this->enter;
        switch ($option)
        {
            case 'glyphicon':
                $icon_list = array(
                    'asterisk',
                    'plus',
                    'euro',
                    'minus',
                    'cloud',
                    'envelope',
                    'pencil',
                    'glass',
                    'music',
                    'search',
                    'heart',
                    'star',
                    'star-empty',
                    'user',
                    'film',
                    'th-large',
                    'th',
                    'th-list',
                    'ok',
                    'remove',
                    'zoom-in',
                    'zoom-out',
                    'off',
                    'signal',
                    'cog',
                    'trash',
                    'home',
                    'file',
                    'time',
                    'road',
                    'download-alt',
                    'download',
                    'upload',
                    'inbox',
                    'play-circle',
                    'repeat',
                    'refresh',
                    'list-alt',
                    'lock',
                    'flag',
                    'headphones',
                    'volume-off',
                    'volume-down',
                    'volume-up',
                    'qrcode',
                    'barcode',
                    'tag',
                    'tags',
                    'book',
                    'bookmark',
                    'print',
                    'camera',
                    'font',
                    'bold',
                    'italic',
                    'text-height',
                    'text-width',
                    'align-left',
                    'align-center',
                    'align-right',
                    'align-justify',
                    'list',
                    'indent-left',
                    'indent-right',
                    'facetime-video',
                    'picture',
                    'map-marker',
                    'adjust',
                    'tint',
                    'edit',
                    'share',
                    'check',
                    'move',
                    'step-backward',
                    'fast-backward',
                    'backward',
                    'play',
                    'pause',
                    'stop',
                    'forward',
                    'fast-forward',
                    'step-forward',
                    'eject',
                    'chevron-left',
                    'chevron-right',
                    'plus-sign',
                    'minus-sign',
                    'remove-sign',
                    'ok-sign',
                    'question-sign',
                    'info-sign',
                    'screenshot',
                    'remove-circle',
                    'ok-circle',
                    'ban-circle',
                    'arrow-left',
                    'arrow-right',
                    'arrow-up',
                    'arrow-down',
                    'share-alt',
                    'resize-full',
                    'resize-small',
                    'exclamation-sign',
                    'gift',
                    'leaf',
                    'fire',
                    'eye-open',
                    'eye-close',
                    'warning-sign',
                    'plane',
                    'calendar',
                    'random',
                    'comment',
                    'magnet',
                    'chevron-up',
                    'chevron-down',
                    'retweet',
                    'shopping-cart',
                    'folder-close',
                    'folder-open',
                    'resize-vertical',
                    'resize-horizontal',
                    'hdd',
                    'bullhorn',
                    'bell',
                    'certificate',
                    'thumbs-up',
                    'thumbs-down',
                    'hand-right',
                    'hand-left',
                    'hand-up',
                    'hand-down',
                    'circle-arrow-right',
                    'circle-arrow-left',
                    'circle-arrow-up',
                    'circle-arrow-down',
                    'globe',
                    'wrench',
                    'tasks',
                    'filter',
                    'briefcase',
                    'fullscreen',
                    'dashboard',
                    'paperclip',
                    'heart-empty',
                    'link',
                    'phone',
                    'pushpin',
                    'usd',
                    'gbp',
                    'sort',
                    'sort-by-alphabet',
                    'sort-by-alphabet-alt',
                    'sort-by-order',
                    'sort-by-order-alt',
                    'sort-by-attributes',
                    'sort-by-attributes-alt',
                    'unchecked',
                    'expand',
                    'collapse-down',
                    'collapse-up',
                    'log-in',
                    'flash',
                    'log-out',
                    'new-window',
                    'record',
                    'save',
                    'open',
                    'saved',
                    'import',
                    'export',
                    'send',
                    'floppy-disk',
                    'floppy-saved',
                    'floppy-remove',
                    'floppy-save',
                    'floppy-open',
                    'credit-card',
                    'transfer',
                    'cutlery',
                    'header',
                    'compressed',
                    'earphone',
                    'phone-alt',
                    'tower',
                    'stats',
                    'sd-video',
                    'hd-video',
                    'subtitles',
                    'sound-stereo',
                    'sound-dolby',
                    'sound-5-1',
                    'sound-6-1',
                    'sound-7-1',
                    'copyright-mark',
                    'registration-mark',
                    'cloud-download',
                    'cloud-upload',
                    'tree-conifer',
                    'tree-deciduous');
                foreach ($icon_list as $icon)
                {
                    $output .= '<div class="col-md-2"><h1>' . $this->Icon(null, $icon, null, null, null) . '</h1><small>' . $icon . '</small></div>' . $this->enter;
                }
                break;
        }
        $output .= '</div>' . $this->enter;
        return $output;
    }
    /**
     * jsmBootstrap::Image()
     *
     * @param string $id = 'image-id'
     * @param string $src = null
     * @param string $class = 'thumbnail' || 'rounded' || 'circle'
     * @param string $alt = null
     * @return string
     */
    public function Image($id = 'image-id', $src = null, $class = 'thumbnail', $width = '100%', $height = '100%', $alt = null, $title = null, $attr = null)
    {
        $_id = null;
        if ($id != null)
        {
            $_id = 'id="' . $id . '"';
        }
        $_holder = null;
        $_src = null;
        if ($src != null)
        {
            $_src = 'src="' . $src . '"';
        } else
        {
            $holder = str_replace("px", "", "holder.js/" . $width . "x" . $height);
            $_holder = 'data-src="' . $holder . '"';
        }
        $_class = null;
        if ($class != null)
        {
            $_class = 'class="img-' . $class . '"';
        }
        $_alt = null;
        if ($alt != null)
        {
            $_alt = 'alt="' . $alt . '"';
        }
        $_title = null;
        if ($title != null)
        {
            $_title = 'title="' . $title . '"';
        }
        $_width = null;
        if ($width != null)
        {
            $_width = str_replace("px", "", $_width = 'width="' . $width . '"');
        }
        $_height = null;
        if ($height != null)
        {
            $_height = str_replace("px", "", 'height="' . $height . '"');
        }
        return '<img ' . $_id . ' ' . $_holder . ' ' . $_src . ' ' . $_class . ' ' . $_alt . ' ' . $_title . ' ' . $_width . ' ' . $_height . '  ' . $attr . '/>' . $this->enter;
    }
    /**
     * jsmBootstrap::Dropdowns()
     *
     * @param string $id = 'dropdowns-id'
     * @param mixed $menus = array(array('label' => 'menu-1', 'link' => '#','icon'=>'phone'), array('label' => 'menu-2', 'link' => '#'))
     * @param string $text = 'label'
     * @param string $color = 'default' || 'primary' || 'warning' || 'danger'
     * @param string $Icons = 'home' || Bootstrap::showOption('icon')
     * @param string $size = 'lg' || 'sm' || 'md' || 'xs'
     * @param string $attr = 'disabled'
     * @return string
     */
    public function Dropdowns($id = 'dropdowns-id', $menus = array(), $text = 'Text', $color = 'default', $Icons = null, $size = "md", $attr = null, $embed = false, $font = 'glyphicon')
    {
        $_id = null;
        if ($id != null)
        {
            $_id = 'id="' . $id . '"';
        }
        $output = null;
        $icon = $this->Icon($Icons);
        if ($embed == false)
        {
            $output .= '<div ' . $_id . ' class="dropdown">' . $this->enter;
        } else
        {
            $output .= '<span ' . $_id . ' class="dropdown">' . $this->enter;
        }
        $caret = '&nbsp;<span class="caret"></span>';
        $output .= $this->Button(null, 'button', $text, $color, $Icons, '#', $size, 'data-toggle="dropdown"', $attr, null, null, null, null, $font);
        $output .= '<ul class="dropdown-menu" >' . $this->enter;
        $output .= $this->MenuList($menus);
        $output .= '</ul>' . $this->enter;
        if ($embed == false)
        {
            $output .= '</div>' . $this->enter;
        } else
        {
            $output .= '</span>' . $this->enter;
        }
        return $output;
    }
    /**
     * jsmBootstrap::ButtonGroups()
     *
     * @param string $id
     * @param mixed $menus = array(array('label' => 'menu-1', 'link' => '#','icon'=>'phone'), array('label' => 'menu-2', 'link' => '#'))
     * @param string $tag = 'anchor' || 'submit' || 'button'
     * @param string $color = 'default' || 'primary' || 'warning' || 'danger'
     * @param string $size = 'lg' || 'sm' || 'md' || 'xs'
     * @return
     */
    public function ButtonGroups($id = 'buttongroups-id', $menus = array(), $tag = 'button', $color = 'danger', $size = "md", $attr = null, $class = null)
    {
        $_id = null;
        if ($id != null)
        {
            $_id = 'id="' . $id . '"';
        }
        $_class = null;
        if ($class != null)
        {
            $_class = 'btn-group-' . $class;
        }
        $output = '<div ' . $_id . ' class="btn-group btn-group-' . $size . ' ' . $_class . '">' . $this->enter;
        foreach ($menus as $menu)
        {
            $_icon = null;
            if (isset($menu['icon']))
            {
                $_icon = $menu['icon'];
            }
            $_tag = $tag;
            if (isset($menu['tag']))
            {
                $_tag = $menu['tag'];
            }
            $_color = $color;
            if (isset($menu['color']))
            {
                $_color = $menu['color'];
            }
            $_link = null;
            if (isset($menu['link']))
            {
                $_link = $menu['link'];
            }
            $_name = null;
            if (isset($menu['name']))
            {
                $_name = $menu['name'];
            }
            $_attr = $attr;
            if (isset($menu['attr']))
            {
                $_attr = $menu['attr'];
            }
            $_class = null;
            if (isset($menu['class']))
            {
                $_class = $menu['class'];
            }
            $_font = null;
            if (isset($menu['font']))
            {
                $_font = $menu['font'];
            }
            $output .= $this->Button($_name, $_tag, $menu['label'], $_color, $_icon, $_link, $size, $_attr, $_class, null, null, null, null, $_font);
        }
        $output .= '</div>' . $this->enter;
        return $output;
    }
    /**
     * jsmBootstrap::ButtonDropdowns()
     *
     * @param string $id = 'buttondropdowns-id'
     * @param string $split = 1
     * @param mixed $menus = array(array('label' => 'menu-1', 'link' => '#','icon'=>'phone'), array('label' => 'menu-2', 'link' => '#'))
     * @param string $text = 'label'
     * @param string $color = 'default' || 'primary' || 'warning' || 'danger'
     * @param string $Icons = 'home' || Bootstrap::showOption('icon')
     * @param string $size = 'lg' || 'sm' || 'md' || 'xs'
     * @param string $attr = 'disabled'
     * @return string
     */
    public function ButtonDropdowns($id = 'buttondropdowns-id', $split = 0, $menus = array(), $text = 'Text', $color = 'default', $Icons = null, $size = "md", $attr = null, $class = null, $font = 'glyphicon')
    {
        $_id = null;
        if ($id != null)
        {
            $_id = 'id="' . $id . '"';
        }
        $_class = null;
        if ($class != null)
        {
            $_class = 'btn-group-' . $class;
        }
        $output = null;
        $icon = $this->Icon($Icons);
        $output .= '<!-- Button Dropdowns -->' . $this->enter;
        $output .= '<div class="btn-group ' . $_class . '">' . $this->enter;
        for ($i = 0; $i < $split; $i++)
        {
            $icon = "";
            if (isset($menus[$i]['icon']) && ($menus[$i]['icon'] != ""))
            {
                $icon = $menus[$i]['icon'];
            }
            $_class = null;
            if (isset($menus[$i]['class']))
            {
                $_class = $menus[$i]['class'];
            }
            $_attr = null;
            if (isset($menus[$i]['attr']))
            {
                $_attr = $menus[$i]['attr'];
            }
            $_font = null;
            if (isset($menus[$i]['font']))
            {
                $_font = $menus[$i]['font'];
            }
            $output .= $this->Button(null, 'anchor', $menus[$i]['label'], $color, $icon, $menus[$i]['link'], $size, $_attr, $_class, null, null, null, null, $_font) . $this->enter;
            unset($menus[$i]);
        }
        $output .= '<div class="btn-group">' . $this->enter;
        $caret = '<span class="caret" ></span>' . $this->enter;
        $output .= $this->Button(null, 'anchor', $caret, $color, null, '#', $size, 'data-toggle="dropdown" ' . $attr, 'dropdown-toggle', null, null, null, null, null) . $this->enter;
        $output .= '<ul class="dropdown-menu" role="menu">' . $this->enter;
        $output .= $this->MenuList($menus) . $this->enter;
        $output .= '</ul>' . $this->enter;
        $output .= '</div>' . $this->enter;
        $output .= '</div>' . $this->enter;
        $output .= '<!-- //Button Dropdowns -->' . $this->enter;
        return $output;
    }
    /**
     * jsmBootstrap::Navs()
     * @param string $id = 'navs-id'
     * @param string $type = 'tabs' || 'pills' || 'stacked'
     * @param mixed $menus = array(array('label' => 'menu-1', 'link' => '#','icon'=>'phone'), array('label' => 'menu-2', 'link' => '#'))
     * @return
     */
    public function Navs($id = 'navs-id', $type = 'tabs', $menus)
    {
        $_id = null;
        if ($id != null)
        {
            $_id = 'id="' . $id . '"';
        }
        if ($type == 'stacked')
        {
            $type = 'pills nav-stacked';
        }
        $output = null;
        $output .= '<ul class="nav nav-' . $type . '" ' . $_id . '>' . $this->enter;
        $output .= $this->MenuList($menus) . $this->enter;
        $output .= '</ul>' . $this->enter;
        return $output;
    }
    /**
     * jsmBootstrap::Navbar()
     * @param string $id = 'navbar-id'
     * @param mixed $menu_left = array(array('label' => 'menu-1', 'link' => '#','icon'=>'phone'), array('label' => 'menu-2', 'link' => '#'))
     * @param mixed $menu_right = array(array('label' => 'menu-1', 'link' => '#','icon'=>'phone'), array('label' => 'menu-2', 'link' => '#'))
     * @param string $position = 'fixed-top' || 'fixed-bottom' || 'static-top'
     * @param string $text ='Label'
     * @param string $style = 'default' || 'inverse'
     * @return string
     */
    public function Navbar($id = 'navbar-id', $menu_left = array(), $menu_right = null, $position = 'static-top', $text = null, $style = 'default')
    {
        $_id = null;
        if ($id != null)
        {
            $_id = 'id="' . $id . '"';
        }
        $output = null;
        $output .= '<!-- navbar -->';
        $output .= '<div ' . $_id . ' class="navbar navbar-' . $style . ' navbar-' . $position . '" role="navigation">';
        $output .= '<div class="container">';
        $output .= '<div class="navbar-header">';
        $output .= '<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-toggle-' . $id . '">';
        $output .= '<span class="sr-only">Toggle</span>';
        $output .= '<span class="icon-bar"></span>';
        $output .= '<span class="icon-bar"></span>';
        $output .= '<span class="icon-bar"></span>';
        $output .= '</button>';
        if (isset($text))
        {
            $output .= $text;
        }
        $output .= '</div>';
        $output .= '<div class="navbar-collapse collapse" id="navbar-toggle-' . $id . '">';
        $output .= '<ul class="nav navbar-nav">' . $this->enter;
        $output .= $this->MenuList($menu_left) . $this->enter;
        $output .= '</ul>' . $this->enter;
        if ($menu_right != null)
        {
            $output .= '<ul class="nav navbar-nav navbar-right">' . $this->enter;
            $output .= $this->MenuList($menu_right) . $this->enter;
            $output .= '</ul>' . $this->enter;
        }
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        if ($position == 'fixed-top')
        {
            //$output .= '<br/><br/>';
        }
        $output .= '<!-- //navbar -->';
        return $output;
    }
    /**
     * jsmBootstrap::Breadcrumbs()
     * @param string $id = 'breadcrumbs-id'
     * @param mixed $menus = array(array('label' => 'menu-1', 'link' => '#','icon'=>'phone'), array('label' => 'menu-2', 'link' => '#'))
     * @return
     */
    public function Breadcrumbs($id = 'breadcrumbs-id', $menus)
    {
        $_id = null;
        if ($id != null)
        {
            $_id = 'id="' . $id . '"';
        }
        $output = null;
        $output .= '<ol class="breadcrumb" ' . $_id . '>' . $this->enter;
        $output .= $this->MenuList($menus) . $this->enter;
        $output .= '</ol>' . $this->enter;
        return $output;
    }
    /**
     * jsmBootstrap::Pagination()
     * @param string $id = 'pagination-id'
     * @param mixed $menus = array(array('label' => 'menu-1', 'link' => '#','icon'=>'phone'), array('label' => 'menu-2', 'link' => '#'))
     * @return
     */
    public function Pagination($id = 'pagination-id', $menus, $size = "md", $class = null)
    {
        $_id = null;
        if ($id != null)
        {
            $_id = 'id="' . $id . '"';
        }
        $output = null;
        $output .= '<ul class="pagination pagination-' . $size . ' ' . $class . '" ' . $_id . '>' . $this->enter;
        $output .= $this->MenuList($menus) . $this->enter;
        $output .= '</ul>' . $this->enter;
        return $output;
    }
    /**
     * jsmBootstrap::Labels()
     *
     * @param string $id = 'label-id'
     * @param string $text = 'label'
     * @param string $link = 'http://google.com/'
     * @param string $color = 'primary' || 'warning' || 'danger'
     * @return
     */
    public function Labels($id = 'label-id', $text = 'Text', $link = "#", $color = 'danger', $title = null, $attr = null)
    {
        $_id = null;
        $_title = null;
        $_attr = null;
        if ($id != null)
        {
            $_id = 'id="' . $id . '"';
        }
        if ($title != null)
        {
            $_title = 'title="' . $title . '"';
        }
        if ($attr != null)
        {
            $_attr = $attr;
        }
        if ($color == null)
        {
            return '<a href="' . $link . '" ' . $_id . ' ' . $_title . ' ' . $_attr . '><span>' . $text . '</span></a>' . $this->enter;
        } else
        {
            return '<a href="' . $link . '" ' . $_id . ' ' . $_title . ' ' . $_attr . '><span class="label label-' . $color . '">' . $text . '</span></a>' . $this->enter;
        }
    }
    /**
     * jsmBootstrap::Badges()
     *
     * @param string $id = 'badge-id'
     * @param string $text = 'label'
     * @return
     */
    public function Badges($id = 'badge-id', $text = 'Text')
    {
        $_id = null;
        if ($id != null)
        {
            $_id = 'id="' . $id . '"';
        }
        return '<span class="badge pull-right" ' . $_id . '>' . $text . '</span>' . $this->enter;
    }
    /**
     * jsmBootstrap::Jumbotron()
     *
     * @param string $id = 'jumbotron-id'
     * @param string $text = 'label'
     * @return
     */
    public function Jumbotron($id = 'jumbotron-id', $text = 'Text')
    {
        $_id = null;
        if ($id != null)
        {
            $_id = 'id="' . $id . '"';
        }
        return '<div class="jumbotron" ' . $_id . '><div class="jumbotron-body">' . $text . '</div></div>' . $this->enter;
    }
    /**
     * jsmBootstrap::Alerts()
     *
     * @param string $id
     * @param string $text
     * @param string $color = 'primary' || 'warning' || 'danger' || 'success' || info
     * @param bool $dismissable = true || false
     * @return
     */
    public function Alerts($id = 'alert-id', $text = 'Text', $color = 'danger', $dismissable = false, $icon = 'info-sign', $font = 'glyphicon', $attr = null)
    {
        $_id = null;
        if ($id != null)
        {
            $_id = 'id="' . $id . '"';
        }
        $buttton = null;
        $dismissable_class = null;
        if ($dismissable == true)
        {
            $buttton = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            $dismissable_class = 'alert-dismissable';
        }
        $_icon = null;
        if ($icon != null)
        {
            $_icon = $this->Icon(null, $icon, null, null, null, $font);
        }
        return '<div ' . $attr . ' class="alert alert-' . $color . ' ' . $dismissable_class . '">' . $_icon . $buttton . $text . '</div>' . $this->enter;
    }
    /**
     * jsmBootstrap::Thumbnails()
     *
     * @param string $id
     * @param string $src
     * @param string $size
     * @param mixed $alt
     * @param mixed $label
     * @return
     */
    public function Thumbnails($id = 'thumbnail-id', $src = "#", $width = "100%", $height = "100%", $alt = null, $label = null, $class = null)
    {
        $_id = null;
        if ($id != null)
        {
            $_id = 'id="' . $id . '"';
        }
        $output = $this->enter;
        $output .= $this->tab . '<!-- thumbnail -->' . $this->enter;
        $output .= $this->tab . '<div class="thumbnail ' . $class . '" ' . $_id . ' >' . $this->enter;
        $output .= $this->tab . $this->tab . $this->Image(null, $src, null, $width, $height, $alt);
        if ($label != null)
        {
            $output .= $this->tab . $this->tab . '<div class="caption">' . $this->enter;
            $output .= $this->tab . $this->tab . $label;
            $output .= $this->tab . $this->tab . '</div>' . $this->enter;
        }
        $output .= $this->tab . '</div>' . $this->enter;
        $output .= $this->tab . '<!-- //thumbnail -->' . $this->enter;
        return $output;
    }
    /**
     * jsmBootstrap::ProgressBars()
     *
     * @return
     */
    public function ProgressBars($id = 'progressbars-id', $text = 'Text', $color = 'danger', $striped = true, $valuenow = 50, $valuemin = 0, $valuemax = 100, $active = false)
    {
        $_striped = null;
        if ($striped == true)
        {
            $_striped = 'progress-striped';
        }
        $_active = null;
        if ($active == true)
        {
            $_active = 'active';
        }
        $_id = null;
        if ($id != null)
        {
            $_id = 'id="' . $id . '"';
        }
        $parsen = (int)(($valuenow / ($valuemax - $valuemin)) * 100);
        $output = null;
        $output .= '<div class="progress ' . $_striped . ' ' . $_active . '" ' . $_id . '>' . $this->enter;
        $output .= '<div class="progress-bar progress-bar-' . $color . '" role="progressbar" aria-valuenow="' . $valuenow . '" aria-valuemin="' . $valuemin . '" aria-valuemax="' . $valuemax . '" style="width: ' . $parsen . '%">' . $this->enter;
        $output .= '<span class="sr-only">' . $text . '</span>' . $this->enter;
        $output .= '</div>' . $this->enter;
        $output .= '</div>' . $this->enter;
        return $output;
    }
    /**
     * jsmBootstrap::InputGroups()
     *
     * @param string $id
     * @param string $addon_type
     * @param string $addon_left
     * @param string $addon_right
     * @param mixed $place_holder
     * @return
     */
    public function InputGroups($id = 'input-group', $addon_left = 'Username', $addon_right = '@', $place_holder = null, $size = 'md', $attr = null, $value = "")
    {
        $output = null;
        $_id = null;
        if ($id != null)
        {
            $_id = $id;
        }
        $output .= '<div class="input-group input-group-' . $size . '">' . $this->enter;
        if ($addon_left != null)
        {
            if ($addon_left == strip_tags($addon_left))
            {
                $output .= '<span class="input-group-addon">' . $addon_left . '</span>' . $this->enter;
            } else
            {
                $output .= '<div class="input-group-btn">' . $addon_left . '</div>' . $this->enter;
            }
        }
        $output .= $this->formElement($_id, 'text', $place_holder, $attr, $value);
        if ($addon_right != null)
        {
            if ($addon_right == strip_tags($addon_right))
            {
                $output .= '<span class="input-group-addon">' . $addon_right . '</span>' . $this->enter;
            } else
            {
                $output .= '<div class="input-group-btn">' . $addon_right . '</div>' . $this->enter;
            }
        }
        $output .= '</div>' . $this->enter;
        return $output;
    }
    public function Forms($id, $action = "", $method = "post", $style = "default", $form_input = 'html', $class = null)
    {
        $encoding = null;
        if ($method == 'post')
        {
            $encoding = 'enctype="multipart/form-data"';
        }
        $hash = md5(rand(0, 99999));
        $output = null;
        $output .= '<!-- start:' . $id . ' -->' . $this->enter;
        switch ($style)
        {
            case "default":
                $this->formType = "form-default";
                break;
            case "horizontal":
                $this->formType = "form-horizontal";
                break;
            case "inline":
                $this->formType = "form-inline";
                break;
        }
        $output .= '<form id="' . $id . '" role="form" action="' . $action . '" method="' . $method . '" class="form-' . $style . ' ' . $class . '" ' . $encoding . '>' . $this->enter;
        $output .= '<input type="hidden" name="bs-form-hash" value="' . $hash . '" />' . $this->enter;
        $output .= $form_input;
        $output .= '</form>';
        $output .= '<!-- end:' . $id . ' -->' . $this->enter;
        $_SESSION['bs-form-hash'] = $hash;
        return $output;
    }
    /**
     * jsmBootstrap::FormGroup()
     *
     * @param string $id = 'formgroup-id'
     * @param string $style = 'default' || horizontal || inline
     * @param string $type = 'text' || 'password' || 'datetime' || 'datetime-local' || 'date' || 'month' || 'time' || 'week' || 'number' || 'email' || 'url' || 'search' || 'tel' || 'color'
     * @param string $label = 'Label' || button || checkbox
     * @param string $place_holder = 'Enter your place holder'
     * @param string $helper = 'Infor'
     * @return
     */
    public function FormGroup($id = 'formgroup-id', $style = 'default', $type = "text", $label = 'Username', $place_holder = null, $helper = null, $attr = null, $size = 8, $value = null, $class = null)
    {
        $_id = null;
        $type = strtolower(trim($type));
        if ($style == null)
        {
            $style = 'null';
        }
        $support = array(
            'test',
            'text',
            'password',
            'datetime',
            'datetime-local',
            'date',
            'month',
            'time',
            'week',
            'number',
            'email',
            'url',
            'search',
            'tel',
            'color');
        $elms_need_form_group = array(
            'test',
            'html',
            'file',
            'textarea',
            'select',
            'radio',
            'checkbox');
        $output = null;
        $output .= '<!-- start:' . $id . ' -->' . $this->enter;


        if ($id != null)
        {
            $_id = $id;
        }

        $group_id = 'group_' . str_replace(array('[', ']'), '_', $_id);

        if (array_search($type, $support))
        {
            switch ($style)
            {
                case 'default':
                    $output .= '<div id="' . $group_id . '" class="form-group">' . $this->enter;
                    if ($label != null)
                    {
                        $output .= $this->tab . '<label for="' . $_id . '">' . $label . '</label>' . $this->enter;
                    }
                    $output .= $this->formElement($_id, $type, $place_holder, $attr, $value, $size, $class);
                    if ($helper != null)
                    {
                        $output .= $this->tab . '<small class="help-block">' . $helper . '</small>' . $this->enter;
                    }
                    $output .= '</div>' . $this->enter;
                    break;
                case 'horizontal':
                    $offset = 'col-sm-offset-4';
                    $output .= '<div id="' . $group_id . '" class="form-group">' . $this->enter;
                    if ($label != null)
                    {
                        $offset = null;
                        $output .= $this->tab . '<label class="col-sm-4 control-label" for="' . $_id . '">' . $label . '</label>' . $this->enter;
                    }
                    $output .= $this->tab . '<div class="' . $offset . ' col-sm-' . $size . '">' . $this->enter;
                    $output .= $this->formElement($_id, $type, $place_holder, $attr, $value, null, $class);
                    if ($helper != null)
                    {
                        $output .= $this->tab . '<small class="help-block">' . $helper . '</small>' . $this->enter;
                    }
                    $output .= $this->tab . '</div>' . $this->enter;
                    $output .= '</div>' . $this->enter;
                    break;
                case 'inline':
                    $output .= '<div id="' . $group_id . '" class="form-group">' . $this->enter;
                    if ($label != null)
                    {
                        $output .= $this->tab . '<label class="sr-only" for="' . $_id . '">' . $label . '</label>' . $this->enter;
                    }
                    $output .= $this->formElement($_id, $type, $place_holder, $attr, $value, $size, $size, $class);
                    if ($helper != null)
                    {
                        $output .= $this->tab . '<small class="help-block">' . $helper . '</small>' . $this->enter;
                    }
                    $output .= '</div>' . $this->enter;
                    break;
                case 'null':
                    $output .= $label . $this->enter;
                    break;
            }
        } else
        {
            if (array_search($type, $elms_need_form_group))
            {
                switch ($style)
                {
                    case 'default':
                        $output .= '<div class="form-group" id="' . $group_id . '">' . $this->enter;
                        if ($label != null)
                        {
                            $output .= $this->tab . '<label for="' . $_id . '">' . $label . '</label>' . $this->enter;
                        }
                        $output .= $this->formElement($_id, $type, $place_holder, $attr, $value, '', $class);
                        if ($helper != null)
                        {
                            $output .= $this->tab . '<small class="help-block">' . $helper . '</small>' . $this->enter;
                        }
                        $output .= '</div>' . $this->enter;
                        break;
                    case 'horizontal':
                        $output .= '<div class="form-group" id="' . $group_id . '">' . $this->enter;
                        $offset = 'col-sm-offset-4';
                        if ($label != null)
                        {
                            $offset = null;
                            $output .= $this->tab . '<label class="col-sm-4 control-label"  for="' . $_id . '">' . $label . '</label>' . $this->enter;
                        }
                        $output .= $this->tab . '<div class="' . $offset . ' col-sm-' . $size . '">' . $this->enter;
                        $output .= $this->formElement($_id, $type, $place_holder, $attr, $value);
                        if ($helper != null)
                        {
                            $output .= $this->tab . '<small class="help-block">' . $helper . '</small>' . $this->enter;
                        }
                        $output .= $this->tab . '</div>' . $this->enter;
                        $output .= '</div>' . $this->enter;
                        break;
                    case 'inline':
                        if ($type != 'checkbox')
                        {
                            $output .= '<div class="form-group" id="' . $group_id . '">' . $this->enter;
                            if ($label != null)
                            {
                                $output .= $this->tab . '<label class="sr-only" for="' . $_id . '">' . $label . '</label>' . $this->enter;
                            }
                            $output .= $this->formElement($_id, $type, $place_holder, $attr, $value);
                            if ($helper != null)
                            {
                                $output .= $this->tab . '<small class="help-block">' . $helper . '</small>' . $this->enter;
                            }
                            $output .= '</div>' . $this->enter;
                        }
                        if ($type == 'checkbox')
                        {
                            $output .= $this->formElement($_id, $type, $label, $attr, $value, $size);
                        }
                        break;
                    case 'null':
                        $output .= $label . $this->enter;
                        break;
                }
            } else
            {
                switch ($style)
                {
                    case 'default':
                        if ($label != null)
                        {
                            $output .= $this->tab . '<label for="' . $_id . '">' . $label . '</label>' . $this->enter;
                        }
                        $output .= $this->formElement($_id, $type, $place_holder, $attr, $value);
                        if ($helper != null)
                        {
                            $output .= $this->tab . '<small class="help-block">' . $helper . '</small>' . $this->enter;
                        }
                        break;
                    case 'horizontal':
                        $offset = 'col-sm-offset-4';
                        if ($label != null)
                        {
                            $offset = null;
                            $output .= $this->tab . '<label class="col-sm-4 control-label" for="' . $_id . '">' . $label . '</label>' . $this->enter;
                        }
                        $output .= $this->tab . '<div class="' . $offset . ' col-sm-' . $size . '">' . $this->enter;
                        $output .= $this->formElement($_id, $type, $place_holder, $attr, $value);
                        if ($helper != null)
                        {
                            $output .= $this->tab . '<small class="help-block">' . $helper . '</small>' . $this->enter;
                        }
                        $output .= $this->tab . '</div>' . $this->enter;
                        break;
                    case 'null':
                        $output .= $label . $this->enter;
                        break;
                }
            }
        }
        $output .= '<!-- end:' . $id . ' -->' . $this->enter . $this->enter;
        return $output;
    }
    /**
     * jsmBootstrap::Input()
     *
     * @param string $id
     * @param string $type = 'text' || 'email'
     * @param mixed $place_holder
     * @return
     */
    public function formElement($id = 'input-id', $type = "text", $place_holder = null, $attr = null, $value = null, $size = null, $class = null)
    {
        $_id = null;
        $_name = null;
        if ($id != null)
        {
            $_id = 'id="' . str_replace(array('[', ']'), '_', $id) . '"';
            $_name = 'name="' . $id . '"';
        }
        $_place_holder = null;
        if (($place_holder != null) && !is_array($place_holder))
        {
            $_place_holder = 'placeholder="' . $place_holder . '"';
        }
        $_value = null;
        if (($value != null) && !is_array($value))
        {
            $_value = 'value="' . $value . '"';
        }
        $_attr = null;
        if ($attr != null)
        {
            $_attr = $attr;
        }
        $_size = null;
        if ($size != null)
        {
            $_size = 'col-md-' . $size . '';
        }
        $_class = null;
        if ($class != null)
        {
            $_class = $class;
        }
        $output = null;
        $similar_tag = $type;
        if (($type == 'email') || ($type == 'password'))
        {
            $similar_tag = 'text';
        }
        switch ($similar_tag)
        {
            case 'hidden':
                $output .= $this->tab . '<input ' . $_id . ' ' . $_value . ' ' . $_name . ' type="' . $type . '" ' . $_place_holder . ' ' . $_attr . '/>' . $this->enter;
                break;
            case 'number':
                $output .= $this->tab . '<input ' . $_id . ' ' . $_value . ' ' . $_name . ' type="number" class="form-control ' . $_size . ' ' . $class . '" ' . $_place_holder . ' ' . $_attr . '/>' . $this->enter;
                break;
            case 'text':
                $output .= $this->tab . '<input ' . $_id . ' ' . $_value . ' ' . $_name . ' type="' . $type . '" class="form-control ' . $_size . ' ' . $class . '" ' . $_place_holder . ' ' . $_attr . '/>' . $this->enter;
                break;
            case 'url':
                $output .= $this->tab . '<input ' . $_id . ' ' . $_value . ' ' . $_name . ' type="url" class="form-control ' . $_size . ' ' . $class . '" ' . $_place_holder . ' ' . $_attr . '/>' . $this->enter;
                break;
            case 'file':
                $output .= $this->tab . '<input ' . $_id . ' class="form-control input-file-sm " ' . $_name . ' type="' . $type . '" ' . $_place_holder . '  ' . $_attr . '/>' . $this->enter;
                break;
            case 'checkbox':
                $output .= $this->tab . '<div class="checkbox ' . $_size . '">' . $this->enter;
                $output .= $this->tab . $this->tab . '<label>' . $this->enter;
                $output .= $this->tab . $this->tab . $this->tab . '<input type="checkbox" class="' . $class . '" ' . $_id . ' ' . $_value . ' ' . $_name . ' ' . $_attr . ' />&nbsp;' . $this->enter;
                $output .= $this->tab . $this->tab . $this->tab . $place_holder . $this->enter;
                $output .= $this->tab . $this->tab . '</label>' . $this->enter;
                $output .= $this->tab . '</div>' . $this->enter;
                break;
            case 'radio':
                $z = 1;
                foreach ($place_holder as $holder)
                {
                    $attr = null;
                    if (isset($holder['active']))
                    {
                        $attr = 'checked="checked"';
                    }
                    $output .= $this->tab . '<div class="radio"><label><input ' . $_name . ' id="' . $id . '-' . $z . '" type="radio" value="' . $holder['value'] . '" ' . $attr . '/>' . $holder['label'] . '</label></div>' . $this->enter;
                    $z++;
                }
                break;
            case 'textarea':
                $output .= $this->tab . '<textarea class="form-control"  ' . $_id . ' ' . $_name . ' ' . $_attr . '>' . $value . '</textarea>' . $this->enter;
                break;
            case 'select':
                $output .= $this->tab . '<select class="form-control ' . $_class . ' ' . $_size . '" ' . $_id . ' ' . $_name . ' ' . $_attr . '>' . $this->enter;
                foreach ($place_holder as $holder)
                {
                    if ($value == $holder['value'])
                    {
                        $holder['active'] = true;
                    }
                    $attr = null;
                    if (isset($holder['active']))
                    {
                        $attr = 'selected="selected"';
                    }
                    $output .= $this->tab . $this->tab . '<option value="' . $holder['value'] . '" ' . $attr . ' >' . htmlentities($holder['label']) . '</option>' . $this->enter;
                }
                $output .= $this->tab . '</select>' . $this->enter;
                break;
            case 'html':
                $output .= $this->tab . $place_holder . $this->enter;
                break;
        }
        return $output;
    }
    /**
     * jsmBootstrap::ListGroup()
     * @param string $id = 'listgroup-id'
     * @param mixed $menus = array( array('label' => 'menu-1', 'link' => '#','content'=>'phone', 'active'=> true,'badges'=>'0','color'=>'danger'), array('label' => 'menu-2', 'link' => '#'))
     * @return
     */
    public function ListGroup($id = 'listgroup-id', $menus = array())
    {
        $_id = null;
        if ($id != null)
        {
            $_id = 'id="' . $id . '"';
        }
        $output = null;
        $output .= '<div class="list-group">' . $this->enter;
        foreach ($menus as $menu)
        {
            $badges = "";
            if (isset($menu['badges']) && ($menu['badges'] != ""))
            {
                $badges = $this->Badges(null, $menu['badges']);
            }
            $colors = "";
            if (isset($menu['color']) && ($menu['color'] != ""))
            {
                $colors = 'list-group-item-' . $menu['color'];
            }
            $_active = null;
            if ((isset($menu['active'])) && ($menu['active'] == true))
            {
                $_active = 'active';
            }
            $output .= '<a href="' . $menu['link'] . '" class="list-group-item ' . $colors . ' ' . $_active . '">' . $this->enter;
            if (isset($menu['content']))
            {
                $output .= '<h4 class="list-group-item-heading">' . $menu['label'] . '</h4>' . $this->enter;
                $output .= '<p class="list-group-item-text">' . $menu['content'] . '</p>' . $this->enter;
            } else
            {
                $output .= $menu['label'] . $badges . $this->enter;
            }
            $output .= '</a>' . $this->enter;
        }
        $output .= '</div>' . $this->enter;
        return $output;
    }
    /**
     * jsmBootstrap::Panels()
     *
     * @param string $id = 'panel-id'
     * @param string $color = 'primary' || 'warning' || 'danger' || 'success' || info
     * @param string $title = 'Content'
     * @param string $body = 'Content'
     * @param mixed $footer = 'Content'
     * @return
     */
    public function Panels($id = 'panel-id', $color = 'default', $title = 'Title', $body = 'Body', $footer = null)
    {
        $output = null;
        $_id = null;
        if ($id != null)
        {
            $_id = 'id="' . $id . '"';
        }
        $output .= '<div class="panel panel-' . $color . '" ' . $_id . '>' . $this->enter;
        $output .= '<div class="panel-heading">' . $this->enter;
        $output .= '<h3 class="panel-title">' . $title . '</h3>' . $this->enter;
        $output .= '</div>' . $this->enter;
        $output .= '<div class="panel-body">' . $this->enter;
        $output .= $body;
        $output .= '</div>' . $this->enter;
        if ($footer != null)
        {
            $output .= '<div class="panel-footer">' . $this->enter;
            $output .= $footer;
            $output .= '</div>' . $this->enter;
        }
        $output .= '</div>' . $this->enter;
        return $output;
    }
    /**
     * jsmBootstrap::Wells()
     *
     * @param string $id = 'well-id'
     * @param string $size = 'lg' || 'sm'
     * @param string $text = 'Content'
     * @return
     */
    public function Wells($id = 'well-id', $size = 'lg', $text = 'Content')
    {
        if ($size != 'lg')
        {
            $size = 'sm';
        }
        $output = null;
        $_id = null;
        if ($id != null)
        {
            $_id = 'id="' . $id . '"';
        }
        return '<div class="well well-' . $size . '" ' . $_id . '>' . $text . '</div>';
    }
    /**
     * jsmBootstrap::Modal()
     *
     * @param string $id
     * @param string $title
     * @param string $body
     * @param mixed $button
     * @return
     */
    public function Modal($id = 'modal-id', $title = 'Title', $body = 'Body', $size = 'lg', $btn_save = 'Save changes', $btn_dismiss = 'Close', $show_no_js = false, $footer = true)
    {
        $output = null;
        $_id = null;
        if ($id != null)
        {
            $_id = 'id="' . $id . '"';
        }
        if ($show_no_js == false)
        {
            $class = 'modal fade';
        } else
        {
            $class = 'x-modal x-fade';
        }
        $output .= '<div class="' . $class . '" ' . $_id . ' role="dialog" tabindex="-1" aria-labelledby="modal-label-' . $id . '" aria-hidden="true">' . $this->enter;
        $output .= '<div class="modal-dialog modal-' . $size . '">' . $this->enter;
        $output .= '<div class="modal-content">' . $this->enter;
        $output .= '<div class="modal-header">' . $this->enter;
        $output .= '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>' . $this->enter;
        $output .= '<h4 class="modal-title" id="modal-label-' . $id . '">' . $title . '</h4>' . $this->enter;
        $output .= '</div>' . $this->enter;
        $output .= '<div class="modal-body">' . $this->enter;
        $output .= $body . $this->enter;
        $output .= '</div>' . $this->enter;
        if ($footer == true)
        {
            $output .= '<div class="modal-footer">' . $this->enter;
            if ($btn_save != null)
            {
                $output .= $this->Button('modal-' . $id . '-button-save', 'button', $btn_save, 'primary', null, null, 'md');
            }
            $output .= $this->Button('modal-' . $id . '-button-dismiss', 'button', $btn_dismiss, 'default', null, null, 'md', 'data-dismiss="modal"');
            $output .= '</div>' . $this->enter;
        }
        $output .= '</div>' . $this->enter;
        $output .= '</div>' . $this->enter;
        $output .= '</div>';
        return $output;
    }
    function Carousel($id = 'slider-id', $images = array(), $width = "800px", $height = "600px", $img_link = false, $data_slide_prev = null, $data_slide_next = null)
    {
        $output = $this->enter;
        $output .= '<!-- carousel -->' . $this->enter;
        $output .= '<div id="' . $id . '" class="carousel slide" data-ride="carousel" data-toggle="carousel" style="width:' . $width . ';height:' . $height . ';">' . $this->enter;
        $output .= $this->tab . '<!-- indicators -->' . $this->enter;
        $output .= $this->tab . '<ol class="carousel-indicators">' . $this->enter;
        $z = 0;
        foreach ($images as $image)
        {
            $active = null;
            if ($z == 0)
            {
                $active = 'class="active"';
            }
            $output .= $this->tab . $this->tab . '<li data-target="#' . $id . '" data-slide-to="' . $z . '" ' . $active . ' ></li>' . $this->enter;
            $z++;
        }
        $output .= $this->tab . '</ol>' . $this->enter;
        $output .= $this->tab . '<!-- //indicators -->' . $this->enter;
        $output .= $this->tab . '<!-- slides -->' . $this->enter;
        $output .= $this->tab . '<div class="carousel-inner">' . $this->enter;
        $z = 0;
        foreach ($images as $image)
        {
            $active = null;
            if ($z == 0)
            {
                $active = 'active';
            }
            if (!isset($image['alt']))
            {
                $image['alt'] = null;
            }
            if (!isset($image['title']))
            {
                $image['title'] = null;
            }
            $output .= $this->tab . $this->tab . '<!-- item -->' . $this->enter;
            $output .= $this->tab . $this->tab . '<div class="item ' . $active . '" style="width:' . $width . ';height:' . $height . ';">' . $this->enter;
            if ($img_link == false)
            {
                $output .= $this->tab . $this->tab . $this->tab . $this->Image(null, $image['image'], null, $width, $height, $image['alt'], $image['title']);
            } else
            {
                $attr = null;
                if (isset($image['attr']))
                {
                    $attr = $image['attr'];
                }
                $class = null;
                if (isset($image['class']))
                {
                    $class = ' class="' . $image['class'] . '"';
                }
                $output .= '<a href="' . $image['link'] . '" ' . $attr . ' ' . $class . ' title="' . $image['title'] . '">';
                $output .= $this->tab . $this->tab . $this->tab . $this->Image(null, $image['image'], null, $width, $height, $image['alt'], $image['title'], $attr);
                $output .= '</a>';
            }
            if (isset($image['caption']))
            {
                $output .= $this->tab . $this->tab . $this->tab . '<div class="carousel-caption">' . $this->enter;
                $output .= $this->tab . $this->tab . $this->tab . $image['caption'] . $this->enter;
                $output .= $this->tab . $this->tab . $this->tab . '</div>' . $this->enter;
            }
            $output .= $this->tab . $this->tab . '</div>' . $this->enter;
            $output .= $this->tab . $this->tab . '<!-- //item -->' . $this->enter;
            $z++;
        }
        $output .= $this->tab . '</div>' . $this->enter;
        $output .= $this->tab . '<!-- //slides -->' . $this->enter;
        $output .= $this->tab . '<!-- controls -->' . $this->enter;
        if ($data_slide_prev == null)
        {
            $output .= $this->tab . '<a class="left carousel-control" href="#' . $id . '" data-slide="prev">' . $this->enter;
            $output .= $this->tab . $this->tab . $this->Icon(null, 'chevron-left') . $this->enter;
            $output .= $this->tab . '</a>' . $this->enter;
        } else
        {
            $output .= $data_slide_prev;
        }
        if ($data_slide_next == null)
        {
            $output .= $this->tab . '<a class="right carousel-control" href="#' . $id . '" data-slide="next">' . $this->enter;
            $output .= $this->tab . $this->tab . $this->Icon(null, 'chevron-right') . $this->enter;
            $output .= $this->tab . '</a>' . $this->enter;
        } else
        {
            $output .= $data_slide_next;
        }
        $output .= $this->tab . '<!-- //controls -->' . $this->enter;
        $output .= '</div>' . $this->enter;
        $output .= '<!-- //carousel -->' . $this->enter;
        return $output;
    }
    /**
     * jsmBootstrap::MenuList()
     *
     * @param mixed $menus
     * @return
     */
    private function MenuList($menus, $MenuSub = false)
    {
        $menu_list = null;
        if (is_array($menus))
        {
            foreach ($menus as $menu)
            {
                $font = "glyphicon";
                if (isset($menu['font']) && ($menu['font'] != ""))
                {
                    $font = $menu['font'];
                }
                $icon = "";
                if (isset($menu['icon']) && ($menu['icon'] != ""))
                {
                    $icon = $this->Icon(null, $menu['icon'], null, null, null, $font);
                }
                $active = "";
                if (isset($menu['active']) && ($menu['active'] != ""))
                {
                    $active = 'class="active"';
                }
                $class = "";
                if (isset($menu['class']) && ($menu['class'] != ""))
                {
                    $class = 'class="' . $menu['class'] . '"';
                }
                $disabled = "";
                if (isset($menu['disabled']) && ($menu['disabled'] != ""))
                {
                    $disabled = 'class="disabled"';
                }
                $badges = "";
                if (isset($menu['badges']) && ($menu['badges'] != ""))
                {
                    $badges = $this->Badges(null, $menu['badges']);
                }
                $attrs = "";
                if (isset($menu['attr']) && ($menu['attr'] != ""))
                {
                    $attrs = $menu['attr'];
                }
                if (!isset($last_level))
                {
                    $last_level = -1;
                }
                if (isset($menu['children']))
                {
                    $MenuLevel = "";
                    if ($MenuSub == true)
                    {
                        $menu_list .= '<li class="dropdown-submenu" ' . $disabled . $active . '><a class="dropdown-toggle" data-toggle="dropdown" href="' . $menu['link'] . '"  ' . $class . '  ' . $attrs . '>' . $icon . $menu['label'] . $badges . '</a>' . $this->enter;
                    } else
                    {
                        $menu_list .= '<li ' . $disabled . $active . '><a class="dropdown-toggle" data-toggle="dropdown" href="' . $menu['link'] . '"  ' . $class . '  ' . $attrs . '>' . $icon . $menu['label'] . $badges . ' <b class="caret"></b></a>' . $this->enter;
                    }
                    $MenuListLevel = 0;
                    $menu_list .= '<ul class="dropdown-menu">' . $this->enter;
                    $menu_list .= $this->MenuList($menu['children'], true);
                    $menu_list .= '</ul>' . $this->enter;
                    $menu_list .= '</li>' . $this->enter;
                } else
                {
                    if (isset($menu['link']))
                    {
                        $menu_list .= '<li ' . $disabled . $active . '><a href="' . $menu['link'] . '" ' . $class . ' ' . $attrs . '>' . $icon . $menu['label'] . $badges . '</a></li>' . $this->enter;
                    } else
                    {
                        $menu_list .= '<li ' . $disabled . $active . $class . '>' . $icon . $menu['label'] . $badges . '</li>' . $this->enter;
                    }
                }
            }
        }
        return $menu_list;
    }
    function __destruct()
    {
        // unset($this);
    }
}



?>