<?php

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2016
 * @license Commercial License
 * 
 * @package Ionic App Builder
 */
if(!defined('JSM_EXEC')) {
    die(':)');
}
function str2SQL($string)
{
    $char = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_12345678900.';
    $Allow = null;
    $string = str_replace(array(
        ' ',
        '-',
        '__'),'_',($string));

    $string = str_replace(array('___','__'),'_',($string));
    for($i = 0; $i < strlen($string); $i++) {
        if(strstr($char,$string[$i]) != false) {
            $Allow .= $string[$i];
        }
    }
    return $Allow;
}
$file_name = 'test';
$bs = new jsmBootstrap();
$form_input = $html = null;
if(isset($_SESSION['FILE_NAME'])) {
    $file_name = $_SESSION['FILE_NAME'];
} else {
    header('Location: ./?page=dashboard&err=project');
    die();
}
if(!isset($_SESSION["PROJECT"]['menu'])) {
    header('Location: ./?page=menu&err=new');
    die();
}
$out_path = 'output/'.$file_name;
$content = null;
$filezip = 'output/'.$file_name.'/backend/wp-plugin/app-'.str_replace('_','-',$_SESSION['PROJECT']['app']['prefix']).'.zip';
if(!is_dir('output/'.$file_name.'/backend/wp-plugin/')) {
    mkdir('output/'.$file_name.'/backend/wp-plugin/',0777,true);
}


$config = $_SESSION["PROJECT"];
foreach(array_keys($config['tables']) as $table_key) {
    foreach($config['tables'][$table_key]['cols'] as $col) {
        $var = $col['title'];
        $_config['tables'][$table_key]['cols'][$var] = $col;
    }
    $config['tables'][$table_key]['cols'] = $_config['tables'][$table_key]['cols'];
}


$wp_plugin_generator_path = 'projects/'.$file_name.'/wp_plugin_generator.json';
// TODO: --|-- SAVE -- JSON FILE
if(isset($_POST['wp_plugin_save'])) {
    $data = null;
    if(!isset($_POST['wp_plugin_generator']['url'])) {
        $_POST['wp_plugin_generator']['url'] = 'http://wordpress.co.id/';
    }
    $data['wp_plugin_generator']['table'] = @$_POST['wp_plugin_generator']['table'];
    $data['wp_plugin_generator']['url'] = @$_POST['wp_plugin_generator']['url'];
    file_put_contents($wp_plugin_generator_path,json_encode($data));
    buildIonic($file_name);
    header('Location: ./?page=z-wordpress-plugin-generator&err=null&notice=save');
    die();
}
$raw_plugin_generator = array();
if(file_exists($wp_plugin_generator_path)) {
    $raw_plugin_generator = json_decode(file_get_contents($wp_plugin_generator_path),true);
}

// TODO: --|-- CLASS - WpPluginGenerator
class WpPluginGenerator
{
    private $config;
    private $js = null;
    /**
     * WpPluginGenerator::__construct()
     * 
     * @param mixed $config
     * @return
     */
    function __construct($config)
    {
        $this->config = $config;
    }

    // TODO: WpPluginGenerator::codeHeader()
    /**
     * WpPluginGenerator::codeHeader()
     * 
     * @return
     */
    function codeHeader()
    {
        if(!isset($this->config['app']['tb_version'])) {
            $this->config['app']['tb_version'] = '';
        }
        $code = null;
        $code .= '/**'."\r\n";
        $code .= ''."\r\n";
        $code .= 'Plugin Name: '.$this->config['app']['name'].' - REST API'."\r\n";
        $code .= 'Plugin URI: '.$this->config['app']['author_url'].' '."\r\n";
        $code .= 'Description: '.$this->config['app']['description'].''."\r\n";
        $code .= 'Version: '.$this->config['app']['version']."\r\n";
        $code .= 'Author: '.$this->config['app']['author_name'].' '."\r\n";
        $code .= 'Author URI: '.$this->config['app']['author_url'].' '."\r\n";
        $code .= ''."\r\n";
        $code .= ''.$this->config['app']['description']."\r\n";
        // $code .= 'Generate by Plugin Maker Free' . "\r\n";
        // $code .= 'Interested more features buy full version http://codecanyon.net/item/wordpress-plugin-maker-freelancer-version/13581496' . "\r\n";
        $code .= ''."\r\n";
        $code .= '**/'."\r\n";
        $code .= ''."\r\n";
        $code .= ''."\r\n";
        $code .= '/**'."\r\n";
        $code .= ' * Plugin Base File'."\r\n";
        $code .= ' **/'."\r\n";
        $code .= 'define("'.strtoupper($this->config['app']['prefix']).'_PATH",dirname(__FILE__));'."\r\n";
        $code .= '/**'."\r\n";
        $code .= ' * Plugin Base Directory'."\r\n";
        $code .= ' **/'."\r\n";
        $code .= 'define("'.strtoupper($this->config['app']['prefix']).'_DIR",basename('.strtoupper($this->config['app']['prefix']).'_PATH));'."\r\n";
        $code .= ''."\r\n";
        $code .= '/**'."\r\n";
        $code .= ' * You can disable RESTAPI2 only for old WP'."\r\n";
        $code .= ' **/'."\r\n";
        $code .= 'define("'.strtoupper($this->config['app']['prefix']).'_RESTAPI2",true);'."\r\n";
        $code .= ''."\r\n";
        return $code;
    }

    // TODO: WpPluginGenerator::codeIonicon()
    /**
     * WpPluginGenerator::codeIonicon()
     * 
     * @return
     */
    function codeIonicon()
    {
        $code = null;
        $code .= '	/** register css/js '.$this->config['app']['name'].' **/'."\r\n";
        $code .= '	public function '.$this->config['app']['prefix'].'_enqueue()'."\r\n";
        $code .= '	{'."\r\n";
        $code .= '	  	 wp_enqueue_script("jquery-ui-datepicker");'."\r\n";
        $code .= '	  	 wp_enqueue_media();'."\r\n";
        $code .= '	     wp_register_style("ionicon", "//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css",array(),"1.2.4" );'."\r\n";
        $code .= '	     wp_enqueue_style("ionicon");'."\r\n";
        $code .= '	     wp_register_style("jquery-ui", "//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css");'."\r\n";
        $code .= '	     wp_enqueue_style("jquery-ui");'."\r\n";

        $code .= '	     wp_enqueue_script("app_'.$this->config['app']['prefix'].'", plugins_url("/",__FILE__) . "/js/admin.js", array("jquery","thickbox"),"1",true );'."\r\n";


        $code .= '	}'."\r\n";
        $code .= '	'."\r\n";
        $code .= '	public function ionicon_list(){'."\r\n";
        $icons = new jsmIonicon();
        foreach($icons->iconList() as $ionicon) {
            $icon_list[] = $ionicon['var'];
        }
        $code .= '		$icons = "'.implode(',',$icon_list).'";'."\r\n";
        $code .= '		print("<div id=\"ionicons\" style=\"display:none;\">");'."\r\n";
        $code .= '		print("<div style=\"width: 100%;height:490px;overflow-x: scroll;\">");'."\r\n";
        $code .= '		foreach(explode(",",$icons) as $icon){'."\r\n";
        $code .= '		    print("<a class=\"app_'.$this->config['app']['prefix'].'_ionicons\" ><i class=\"ion ion-".$icon."\"></i></a>");'."\r\n";
        $code .= '		}'."\r\n";
        $code .= '		print("</div>");'."\r\n";
        $code .= '		print("</div>");'."\r\n";
        $code .= '	}'."\r\n";
        $code .= "\r\n";
        $code .= "\r\n";
        $code .= '	function admin_head_app_'.$this->config['app']['prefix'].'($hooks){'."\r\n";
        $code .= '	     echo "<style type=\"text/css\">";'."\r\n";
        $code .= '	     echo ".app_'.$this->config['app']['prefix'].'_ionicons .ion{cursor:pointer;text-align:center;border:1px solid #eee;font-size:32px;width:32px;height:32px;padding:6px;}";'."\r\n";
        $code .= '	     echo "</style>";'."\r\n";
        $code .= '	}'."\r\n";
        $code .= "\r\n";
        $code .= "\r\n";
        $this->js .= "\t".'$(".type-ionicons").click(function(){'."\r\n";
        $this->js .= "\t\t".'window.ion_picker = "#" + $(this).prop("id");'."\r\n";
        $this->js .= "\t\t".'tb_show("Ionic Icons", "#TB_inline?width=600&height=490&inlineId=ionicons");'."\r\n";
        $this->js .= "\t\t".'$("#TB_ajaxContent").attr("style","height:490px;");'."\r\n";
        $this->js .= "\t".'});'."\r\n";
        $this->js .= "\t".'$(".type-images").click(function(){'."\r\n";
        $this->js .= "\t\t".'window.images_picker = "#" + $(this).prop("id");'."\r\n";
        $this->js .= "\t\t".'if(app_images) {'."\r\n";
        $this->js .= "\t\t\t".'app_images.open();'."\r\n";
        $this->js .= "\t\t\t".'return;'."\r\n";
        $this->js .= "\t\t".'}'."\r\n";
        $this->js .= "\t\t".'var app_images = wp.media({'."\r\n";
        $this->js .= "\t\t\t\t".'title: "Select or Upload Media Of Your Chosen Persuasion",'."\r\n";
        $this->js .= "\t\t\t\t".'button: {'."\r\n";
        $this->js .= "\t\t\t\t\t".'text: "Use this media"'."\r\n";
        $this->js .= "\t\t\t\t".'},'."\r\n";
        $this->js .= "\t\t\t\t".'multiple: false'."\r\n";
        $this->js .= "\t\t".'});'."\r\n";
        $this->js .= "\t\t".'app_images.on("select",function(){'."\r\n";
        $this->js .= "\t\t\t".'var attachment = app_images.state().get("selection").first().toJSON();'."\r\n";
        $this->js .= "\t\t\t".'var url = attachment.url ;'."\r\n";
        $this->js .= "\t\t".'$(window.images_picker).val(url);'."\r\n";
        $this->js .= "\t\t".'});'."\r\n";
        $this->js .= "\t\t".'app_images.open();'."\r\n";
        $this->js .= "\t\t".'return false;'."\r\n";
        $this->js .= "\t".'});'."\r\n";
        $this->js .= "\t".'$(".app_'.$this->config['app']['prefix'].'_ionicons").click(function(){'."\r\n";
        $this->js .= "\t\t".'var ion_class = $(this).find(".ion").attr("class");'."\r\n";
        $this->js .= "\t\t".'$(window.ion_picker).val(ion_class);'."\r\n";
        $this->js .= "\t\t".'tb_remove();'."\r\n";
        $this->js .= "\t".'});'."\r\n";
        return $code;
    }
    // TODO: WpPluginGenerator::codePostType()
    /**
     * WpPluginGenerator::codePostType()
     * 
     * @return
     */
    function codePostType()
    {
        $code = null;
        foreach($this->config['tables'] as $tables) {
            $code .= "\t".'/** register post for table '.strtolower($tables['title']).' **/'."\r\n";
            $code .= "\t".'public function post_type_app_'.$tables['prefix'].'()'."\r\n";
            $code .= "\t".'{'."\r\n";
            $code .= "\t\t".'$labels = array('."\r\n";
            $code .= "\t\t\t".'"name" => _x("'.$this->var2text($tables['title']).'s", "post type general name", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"),'."\r\n";
            $code .= "\t\t\t".'"singular_name" => _x("'.$this->var2text($tables['title']).'", "post type singular name", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"),'."\r\n";
            $code .= "\t\t\t".'"menu_name" => _x("'.$this->var2text($tables['title']).'s", "admin menu", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"),'."\r\n";
            $code .= "\t\t\t".'"name_admin_bar" => _x("'.$this->var2text($tables['title']).'s", "add new on admin bar", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"),'."\r\n";
            $code .= "\t\t\t".'"add_new" => _x("Add new '.$this->var2text($tables['title']).'s", "item", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"),'."\r\n";
            $code .= "\t\t\t".'"add_new_item" => __("Add new '.$this->var2text($tables['title']).'s", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"),'."\r\n";
            $code .= "\t\t\t".'"new_item" => __("new item", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"),'."\r\n";
            $code .= "\t\t\t".'"edit_item" => __("Edit '.$this->var2text($tables['title']).'s", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"),'."\r\n";
            $code .= "\t\t\t".'"view_item" => __("View '.$this->var2text($tables['title']).'s", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"),'."\r\n";
            $code .= "\t\t\t".'"all_items" => __("All '.$this->var2text($tables['title']).'s", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"),'."\r\n";
            $code .= "\t\t\t".'"search_items" => __("Search '.$this->var2text($tables['title']).'s", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"),'."\r\n";
            $code .= "\t\t\t".'"parent_item_colon" => __("parent '.$this->var2text($tables['title']).'s:", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"),'."\r\n";
            $code .= "\t\t\t".'"not_found" => __("not found", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"),'."\r\n";
            $code .= "\t\t\t".'"not_found_in_trash" => __("not found in trash", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"));'."\r\n";
            $code .= "\t".''."\r\n";
            $code .= "\t\t".'$args = array('."\r\n";
            $code .= "\t\t\t".'"labels" => $labels,'."\r\n";
            $code .= "\t\t\t".'"public" => true,'."\r\n";
            $code .= "\t\t\t".'"menu_icon" => "dashicons-tickets",'."\r\n";
            $code .= "\t\t\t".'"publicly_queryable" => false,'."\r\n";
            $code .= "\t\t\t".'"show_ui" => true,'."\r\n";
            $code .= "\t\t\t".'"show_in_menu" => true,'."\r\n";
            $code .= "\t\t\t".'"query_var" => true,'."\r\n";
            $code .= "\t\t\t".'"capability_type" => "page",'."\r\n";
            $code .= "\t\t\t".'"has_archive" => true,'."\r\n";
            $code .= "\t\t\t".'"hierarchical" => true,'."\r\n";
            $code .= "\t\t\t".'"menu_position" => null,'."\r\n";
            $code .= "\t\t\t".'"taxonomies" => array(),  '."\r\n";
            $code .= "\t\t\t".'"supports" => array("title"));'."\r\n";
            $code .= "\t".''."\r\n";
            $code .= "\t\t".'register_post_type("app_'.$tables['prefix'].'", $args);'."\r\n";
            $code .= "\t".'}'."\r\n";
            $code .= "\t".''."\r\n";
        }
        return $code;
    }

    // TODO: WpPluginGenerator::codeMetaBox()
    /**
     * WpPluginGenerator::codeMetaBox()
     * 
     * @return
     */
    function codeMetaBox()
    {
        global $raw_plugin_generator;
        $code = null;
        foreach($this->config['tables'] as $tables) {
            $is_true = false;
            if(isset($raw_plugin_generator['wp_plugin_generator']['table'][$tables['prefix']]['status'])) {
                if($raw_plugin_generator['wp_plugin_generator']['table'][$tables['prefix']]['status'] == 'checked') {
                    $is_true = true;
                }
            }
            if($is_true == true) {
                $code .= "\t".'/** register metabox for '.strtolower($tables['title']).' **/'."\r\n";
                $code .= "\t".'public function metabox_app_'.$tables['prefix'].'($hook)'."\r\n";
                $code .= "\t".'{'."\r\n";
                $code .= "\t\t".'$allowed_hook = array("app_'.$tables['prefix'].'");'."\r\n";
                $code .= "\t\t".'if(in_array($hook, $allowed_hook))'."\r\n";
                $code .= "\t\t".'{'."\r\n";
                $code .= "\t\t\t".'add_meta_box("metabox_app_'.$tables['prefix'].'",'."\r\n";
                $code .= "\t\t\t\t".'__("'.$this->var2text($tables['title']).'s - The REST API","app-'.str_replace('_','-',$this->config['app']['prefix']).'"),'."\r\n";
                $code .= "\t\t\t\t".'array($this,"metabox_app_'.$tables['prefix'].'_callback"),'."\r\n";
                $code .= "\t\t\t\t".'$hook,'."\r\n";
                $code .= "\t\t\t\t".'"normal",'."\r\n";
                $code .= "\t\t\t\t".'"high");'."\r\n";
                $code .= "\t\t".'}'."\r\n";
                $code .= "\t".'}'."\r\n";
                $code .= "\t".''."\r\n";

                $code .= "\t".'/** callback metabox for '.strtolower($tables['title']).' **/'."\r\n";
                $code .= "\t".'public function metabox_app_'.$tables['prefix'].'_callback($post)'."\r\n";
                $code .= "\t".'{'."\r\n";
                $code .= "\t\t".'$this->'.$this->config['app']['prefix'].'_enqueue();'."\r\n";
                $code .= "\t\t".'wp_enqueue_style("thickbox");'."\r\n";
                $code .= "\t\t".'wp_nonce_field("metabox_app_'.$tables['prefix'].'_save","metabox_app_'.$tables['prefix'].'_nonce");'."\r\n";
                //$code .= '		printf("<pre style=\"padding:15px;margin:0;border:1px solid #eee\">");' . "\r\n";
                //$code .= '		printf("GET ".site_url()."/wp-json/' . $_SESSION['PROJECT']['app']['prefix'] . '/v2/app_' . $tables['prefix'] . '?numberposts=3\r\n");' . "\r\n";
                foreach($tables['cols'] as $column) {
                    if($column['type'] != 'id') {
                        $_var_name = $this->str2var($column['title']);
                        //$code .= '		printf("GET ".site_url()."/wp-json/' . $_SESSION['PROJECT']['app']['prefix'] . '/v2/app_' . $tables['prefix'] . '?' . $_var_name . '=[keyword]\r\n");' . "\r\n";
                    }
                }
                //$code .= '		printf("</pre>");' . "\r\n";
                $code .= "\t\t".''."\r\n";
                $code .= "\t\t".'printf("<table class=\"form-table\">");'."\r\n";
                foreach($tables['cols'] as $column) {
                    if($column['type'] != 'id') {
                        switch($column['type']) {
                            case 'heading-1':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><input class=\"widefat\" type=\"text\" id=\"'.$name.'\" name=\"'.$name.'\" value=\"%s\" /></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                break;
                            case 'heading-2':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><input class=\"widefat\" type=\"text\" id=\"'.$name.'\" name=\"'.$name.'\" value=\"%s\" /></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                break;
                            case 'heading-3':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><input class=\"widefat\" type=\"text\" id=\"'.$name.'\" name=\"'.$name.'\" value=\"%s\" /></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                break;
                            case 'heading-4':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><input class=\"widefat\" type=\"text\" id=\"'.$name.'\" name=\"'.$name.'\" value=\"%s\" /></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                break;
                            case 'text':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><textarea class=\"widefat\" type=\"text\" id=\"'.$name.'\" name=\"'.$name.'\" >%s</textarea></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                break;
                            case 'slidebox':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><textarea class=\"widefat\" type=\"text\" id=\"'.$name.'\" name=\"'.$name.'\" >%s</textarea><p>Separator with |, example: slide1|slide2|slide3</p></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                break;
                            case 'images':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><input class=\"widefat type-images\" type=\"text\" id=\"'.$name.'\" name=\"'.$name.'\" value=\"%s\" /></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                break;
                            case 'video':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><input class=\"widefat\" type=\"text\" id=\"'.$name.'\" name=\"'.$name.'\" value=\"%s\" /></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                break;
                            case 'audio':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><input class=\"widefat\" type=\"text\" id=\"'.$name.'\" name=\"'.$name.'\" value=\"%s\" /></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                break;
                            case 'share_link':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><input class=\"widefat\" type=\"text\" id=\"'.$name.'\" name=\"'.$name.'\" value=\"%s\" /></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                break;
                            case 'ytube':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><input class=\"widefat\" type=\"text\" id=\"'.$name.'\" name=\"'.$name.'\" value=\"%s\" placeholder=\"4HkG8z3sa-0\" /><p class=\"description\">Use Youtube ID example: 4HkG8z3sa-0 get from link: https://www.youtube.com/watch?v=<kbd>4HkG8z3sa-0</kbd></p></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                break;
                            case 'gmap':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><input class=\"widefat\" placeholder=\"-6.17149,106.82752\" type=\"text\" id=\"'.$name.'\" name=\"'.$name.'\" value=\"%s\" /><p class=\"description\">Format: <kbd>[Longitude],[Latitude]</kbd>, Example: <kbd>-6.17149,106.82752</kbd></p></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                break;
                            case 'webview':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><input class=\"widefat\" placeholder=\"\" type=\"url\" id=\"'.$name.'\" name=\"'.$name.'\" value=\"%s\" /></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                break;
                            case 'appbrowser':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><input class=\"widefat\" placeholder=\"\" type=\"url\" id=\"'.$name.'\" name=\"'.$name.'\" value=\"%s\" /></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                break;
                            case 'share_link':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><input class=\"widefat\" placeholder=\"\" type=\"url\" id=\"'.$name.'\" name=\"'.$name.'\" value=\"%s\" /></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                break;
                            case 'link':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><input class=\"widefat\" placeholder=\"\" type=\"url\" id=\"'.$name.'\" name=\"'.$name.'\" value=\"%s\" /></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                break;
                            case 'icon':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><input class=\" type-ionicons\" type=\"text\" id=\"'.$name.'\" name=\"'.$name.'\" value=\"%s\" placeholder=\"icon ion-home\" /><p class=\"description\">Format: <kbd>icon ion-[class-name]</kbd> Example: <kbd>icon ion-home</kbd></p></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                break;
                            case 'paragraph':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><textarea class=\"widefat\" type=\"text\" id=\"'.$name.'\" name=\"'.$name.'\" >%s</textarea></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                break;
                            case 'to_trusted':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$settings = array("media_buttons"=>true);'."\r\n";
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                //$code .= '		printf("<tr><th scope=\"row\"><label for=\"' . $name . '\">%s</label></th><td><textarea class=\"widefat\" type=\"text\" id=\"' . $name . '\" name=\"' . $name . '\" >%s</textarea><p class=\"description\">You can use simple HTML5 Tags</p></td></tr>",__("' . ucwords(str_replace('_', ' ', ($column['title']))) . '", "app-' . str_replace('_', '-', $this->config['app']['prefix']) . '"), esc_textarea($value_' . $tables['prefix'] . '_' . $this->str2var($column['title']) . '));' . "\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td>",__("'.ucwords(str_replace('_',' ',($column['title']))).'","app-'.str_replace('_','-',$this->config['app']['prefix']).'"));'."\r\n";
                                $code .= "\t\t".'wp_editor(html_entity_decode($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'),"'.$name.'",$settings);'."\r\n";
                                $code .= "\t\t".'printf("</td></tr>");'."\r\n";
                                break;
                            case 'rating':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><input class=\"\" type=\"number\" id=\"'.$name.'\" name=\"'.$name.'\" value=\"%s\" placeholder=\"5\" min=\"1\" max=\"5\" /><p class=\"description\">Format: <kbd>0-5</kbd></p></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                break;
                            case 'number':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><input class=\"regular-text\" type=\"number\" id=\"'.$name.'\" name=\"'.$name.'\" value=\"%s\" /></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                break;
                            case 'float':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><input class=\"regular-text\" type=\"text\" id=\"'.$name.'\" name=\"'.$name.'\" value=\"%s\" /></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                break;

                            case 'app_email':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><input class=\"regular-text\" type=\"email\" id=\"'.$name.'\" name=\"'.$name.'\" value=\"%s\" placeholder=\"user@domain.com\" /></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                break;

                            case 'app_sms':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><input class=\"regular-text\" type=\"text\" id=\"'.$name.'\" name=\"'.$name.'\" value=\"%s\" placeholder=\"+123456789\" /></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                break;

                            case 'app_call':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><input class=\"regular-text\" type=\"text\" id=\"'.$name.'\" name=\"'.$name.'\" value=\"%s\" placeholder=\"+123456789\" /></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                break;

                            case 'app_geo':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><input class=\"regular-text\" type=\"text\" id=\"'.$name.'\" name=\"'.$name.'\" value=\"%s\" placeholder=\"23,355\" /></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                break;

                            case 'date':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><input class=\"regular-text\" type=\"text\" id=\"'.$name.'\" name=\"'.$name.'\" value=\"%s\" placeholder=\"0000-00-00\" required/></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";

                                $this->js .= "\t".'$("#'.$name.'").datepicker({ dateFormat: "yy-mm-dd" });'."\r\n";

                                break;

                            case 'datetime':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><input class=\"regular-text\" type=\"text\" id=\"'.$name.'\" name=\"'.$name.'\" value=\"%s\" placeholder=\"0000-00-00 00:00:00\" required/></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                //$this->js .= "\t".'$("#'.$name.'").datepicker({ dateFormat: "yy-mm-dd",timeFormat:"hh:mm:ss"});'."\r\n";
                                break;
                            case 'date_php':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><input class=\"regular-text\" type=\"text\" id=\"'.$name.'\" name=\"'.$name.'\" value=\"%s\" placeholder=\"0000-00-00\" required/></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                $this->js .= "\t".'$("#'.$name.'").datepicker({ dateFormat: "yy-mm-dd" });'."\r\n";
                                break;

                            case 'datetime_php':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><input class=\"regular-text\" type=\"text\" id=\"'.$name.'\" name=\"'.$name.'\" value=\"%s\" placeholder=\"0000-00-00 00:00:00\" required/></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                //$this->js .= "\t".'$("#'.$name.'").datepicker({ dateFormat: "yy-mm-dd"});'."\r\n";
                                break;

                            case 'datetime_string':
                                $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                                $code .= "\t\t".'$value_'.$tables['prefix'].'_'.$this->str2var($column['title']).' = get_post_meta($post->ID, "_'.$name.'", true);'."\r\n";
                                $code .= "\t\t".'printf("<tr><th scope=\"row\"><label for=\"'.$name.'\">%s</label></th><td><input class=\"regular-text\" type=\"text\" id=\"'.$name.'\" name=\"'.$name.'\" value=\"%s\" placeholder=\"0000-00-00 00:00:00\" required /></td></tr>",__("'.ucwords(str_replace('_',' ',($column['title']))).'", "app-'.str_replace('_','-',$this->config['app']['prefix']).'"), esc_attr($value_'.$tables['prefix'].'_'.$this->str2var($column['title']).'));'."\r\n";
                                //$this->js .= "\t".'$("#'.$name.'").datepicker({ dateFormat: "yy-mm-dd"});'."\r\n";
                                break;

                        }


                    }

                }
                $this->js .= "\t".''."\r\n";
                $code .= "\t\t".'printf("</table>");'."\r\n";
                $code .= "\t\t".''."\r\n";
                $code .= "\t\t".'$this->ionicon_list();'."\r\n";
                $code .= "\t".'}'."\r\n";

                $code .= "\t\t".''."\r\n";
                $code .= "\t".'/** Save value metabox '.$tables['prefix'].' **/'."\r\n";
                $code .= "\t".'public function metabox_app_'.$tables['prefix'].'_save($post_id)'."\r\n";
                $code .= "\t".'{'."\r\n";
                $code .= "\t\t".''."\r\n";
                $code .= "\t\t".'// Check if our nonce is set.'."\r\n";
                $code .= "\t\t".'if (!isset($_POST["metabox_app_'.$tables['prefix'].'_nonce"]))'."\r\n";
                $code .= "\t\t\t".'return $post_id;'."\r\n";
                $code .= "\t\t".''."\r\n";
                $code .= "\t\t".'$nonce = $_POST["metabox_app_'.$tables['prefix'].'_nonce"];'."\r\n";
                $code .= "\t\t".'// Verify that the nonce is valid.'."\r\n";
                $code .= "\t\t".''."\r\n";
                $code .= "\t\t".'if(!wp_verify_nonce($nonce, "metabox_app_'.$tables['prefix'].'_save"))'."\r\n";
                $code .= "\t\t\t".'return $post_id;'."\r\n";
                $code .= "\t\t".''."\r\n";

                $code .= "\t\t".'// If this is an autosave, our form has not been submitted,'."\r\n";
                $code .= "\t\t".'// so we don\'t want to do anything.'."\r\n";
                $code .= "\t\t".'if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)'."\r\n";
                $code .= "\t\t\t".'return $post_id;'."\r\n";
                $code .= "\t\t".''."\r\n";

                $code .= "\t\t".'// Check the user\'s permissions.'."\r\n";
                $code .= "\t\t".'if ("page" == $_POST["post_type"])'."\r\n";
                $code .= "\t\t".'{'."\r\n";
                $code .= "\t\t\t".'if (!current_user_can("edit_page", $post_id))'."\r\n";
                $code .= "\t\t\t\t".'return $post_id;'."\r\n";
                $code .= "\t\t".'}else'."\r\n";
                $code .= "\t\t".'{'."\r\n";
                $code .= "\t\t\t".'if (!current_user_can("edit_post", $post_id))'."\r\n";
                $code .= "\t\t\t\t".'return $post_id;'."\r\n";
                $code .= "\t\t".''."\r\n";

                $code .= "\t\t".'}'."\r\n";
                foreach($tables['cols'] as $column) {
                    if($column['type'] != 'id') {
                        $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                        if($column['type'] == 'to_trusted') {
                            $code .= "\t\t".'// Sanitize the user input.'."\r\n";
                            $code .= "\t\t".'$post_'.$name.' = esc_html($_POST["'.$name.'"] );'."\r\n";
                        } else {
                            $code .= "\t\t".'// Sanitize the user input.'."\r\n";
                            $code .= "\t\t".'$post_'.$name.' = sanitize_text_field($_POST["'.$name.'"] );'."\r\n";
                        }
                        $code .= "\t\t".'// Update the meta field.'."\r\n";
                        $code .= "\t\t".'update_post_meta($post_id, "_'.$name.'", $post_'.$name.');'."\r\n";
                    }
                }
                $code .= "\t".'}'."\r\n";
                $code .= "\t".''."\r\n";
            }
        }
        $code .= "\t".''."\r\n";
        return $code;
    }

    // TODO: WpPluginGenerator::codeConstruct()
    /**
     * WpPluginGenerator::codeConstruct()
     * 
     * @return
     */
    function codeConstruct()
    {

        $code = null;
        $code .= "\r\n\t".'function __construct(){'."\r\n";
        $code .= "\r\n\t\t".'// File upload allowed'."\r\n";
        $code .= "\r\n\t\t".'$whitelist_files[] '."\t\t".'= array("mimetype"=>"image/jpeg","ext"=>"jpg") ; '."\t\t".'';
        $code .= "\r\n\t\t".'$whitelist_files[] '."\t\t".'= array("mimetype"=>"image/jpg","ext"=>"jpg") ; '."\t\t".'';
        $code .= "\r\n\t\t".'$whitelist_files[] '."\t\t".'= array("mimetype"=>"image/png","ext"=>"png") ; '."\t\t".'';
        $code .= "\r\n\t\t".'$whitelist_files[] '."\t\t".'= array("mimetype"=>"text/plain","ext"=>"txt") ; '."\t\t".'';
        $code .= "\r\n\t\t".'$this->whitelist_files = $whitelist_files;'."\r\n";
        $code .= "\r\n\t\t".'add_action("plugins_loaded", array($this, "app_textdomain")); //load language/textdomain'."\r\n";
        $code .= "\r\n\t\t".'/** register post type **/'."\r\n";
        global $raw_plugin_generator;
        foreach($this->config['tables'] as $tables) {
            $is_true = false;
            if(isset($raw_plugin_generator['wp_plugin_generator']['table'][$tables['prefix']]['status'])) {
                if($raw_plugin_generator['wp_plugin_generator']['table'][$tables['prefix']]['status'] == 'checked') {
                    $is_true = true;
                }
            }
            if($is_true == true) {
                $code .= "\t\t".'add_action("init", array($this, "post_type_app_'.$tables['prefix'].'"));'."\r\n";
            }
        }
        $code .= "\r\n";
        $code .= "\t\t"."if(".strtoupper($this->config['app']['prefix'])."_RESTAPI2 == true){\r\n";
        $code .= "\t\t\t".'/** register rest router **/'."\r\n";
        foreach($this->config['tables'] as $tables) {
            $is_true = false;
            if(isset($raw_plugin_generator['wp_plugin_generator']['table'][$tables['prefix']]['status'])) {
                if($raw_plugin_generator['wp_plugin_generator']['table'][$tables['prefix']]['status'] == 'checked') {
                    $is_true = true;
                }
            }
            if($is_true == true) {
                $code .= "\t\t\t".'add_action("rest_api_init", array($this,"register_rest_route_app_'.$tables['prefix'].'"));'."\r\n";
            }
        }

        if(isset($this->config['forms'])) {
            $code .= "\r\n";
            $code .= "\t\t\t".'/** register rest router for form request **/'."\r\n";
            foreach($this->config['forms'] as $forms) {
                $code .= "\t\t\t".'add_action("rest_api_init", array($this,"register_rest_route_app_'.$forms['table'].'_submit"));'."\r\n";
            }
        }
        $code .= "\r\n\t\t"."}else{\r\n";


        if(isset($this->config['forms'])) {
            $code .= "\r\n";
            $code .= "\t\t\t".'/** register ajax/ajax_nopriv_ **/'."\r\n";
            foreach($this->config['tables'] as $tables) {
                $is_true = false;
                if(isset($raw_plugin_generator['wp_plugin_generator']['table'][$tables['prefix']]['status'])) {
                    if($raw_plugin_generator['wp_plugin_generator']['table'][$tables['prefix']]['status'] == 'checked') {
                        $is_true = true;
                    }
                }
                if($is_true == true) {
                    $code .= "\t\t\t".'add_action("wp_ajax_nopriv_app_'.$tables['prefix'].'", array($this,"ajax_app_'.$tables['prefix'].'"));'."\r\n";
                    $code .= "\t\t\t".'add_action("wp_ajax_app_'.$tables['prefix'].'", array($this,"ajax_app_'.$tables['prefix'].'"));'."\r\n";
                    $code .= "\r\n";
                }
            }
            foreach($this->config['forms'] as $forms) {
                $code .= "\t\t\t".'add_action("wp_ajax_nopriv_app_'.$forms['table'].'_submit", array($this,"ajax_app_'.$forms['table'].'_submit"));'."\r\n";
                $code .= "\t\t\t".'add_action("wp_ajax_app_'.$forms['table'].'_submit", array($this,"ajax_app_'.$forms['table'].'_submit"));'."\r\n";
                $code .= "\r\n";
            }
        }
        $code .= "\r\n\t\t"."}\r\n";
        $code .= "\r\n";


        $code .= "\t\t".'/** register metabox for admin **/'."\r\n";
        $code .= "\t\t".'if(is_admin()){'."\r\n";
        $code .= "\t\t\t".'add_action("admin_head",array($this,"admin_head_app_'.$this->config['app']['prefix'].'"),1);'."\r\n";
        foreach($this->config['tables'] as $tables) {
            $is_true = false;
            if(isset($raw_plugin_generator['wp_plugin_generator']['table'][$tables['prefix']]['status'])) {
                if($raw_plugin_generator['wp_plugin_generator']['table'][$tables['prefix']]['status'] == 'checked') {
                    $is_true = true;
                }
            }
            if($is_true == true) {
                $code .= "\t\t\t".'add_action("add_meta_boxes",array($this,"metabox_app_'.$tables['prefix'].'"));'."\r\n";
                $code .= "\t\t\t".'add_action("save_post",array($this,"metabox_app_'.$tables['prefix'].'_save"));'."\r\n";
            }
        }
        $code .= "\t\t".'}'."\r\n";
        $code .= "\r\n\t".'}'."\r\n";
        return $code;
    }

    // TODO: WpPluginGenerator::codeTextdomain()
    /**
     * WpPluginGenerator::codeTextdomain()
     * 
     * @return
     */
    function codeTextdomain()
    {
        $code = null;
        $code .= "\r\n";
        $code .= "\r\n";
        $code .= "\t".'// Register textdomain'."\r\n";
        $code .= "\t".'function app_textdomain(){'."\r\n";
        $code .= "\t\t".'load_plugin_textdomain("app-'.str_replace('_','-',$this->config['app']['prefix']).'", false, '.strtoupper($this->config['app']['prefix']).'_DIR . "/languages");'."\r\n";
        $code .= "\t".'}'."\r\n";
        return $code;
    }

    // TODO: WpPluginGenerator::codeRestAPI()
    /**
     * WpPluginGenerator::codeRestAPI()
     * 
     * @return
     */
    function codeRestAPI()
    {
        global $raw_plugin_generator;
        $is_true = false;
        $code = null;
        foreach($this->config['tables'] as $tables) {
            $is_true = false;
            if(isset($raw_plugin_generator['wp_plugin_generator']['table'][$tables['prefix']]['status'])) {
                if($raw_plugin_generator['wp_plugin_generator']['table'][$tables['prefix']]['status'] == 'checked') {
                    $is_true = true;
                }
            }
            if($is_true == true) {
                $code .= "\r\n";
                $code .= "\r\n";
                $code .= "\t".'// TO'.'DO: register routes app_'.$tables['prefix'].''."\r\n";
                $code .= "\t".'function register_rest_route_app_'.$tables['prefix'].'(){'."\r\n";
                $code .= "\t\t".'register_rest_route("'.$this->config['app']['prefix'].'/v2","app_'.$tables['prefix'].'",array('."\r\n";
                $code .= "\t\t\t".'"methods" => "GET",'."\r\n";
                $code .= "\t\t\t".'"callback" =>array($this, "app_'.$tables['prefix'].'_callback"),'."\r\n";
                $code .= "\t\t\t".'"permission_callback" => function (WP_REST_Request $request){return true;}'."\r\n";
                $code .= "\t\t".'));'."\r\n";
                $code .= "\t".'}'."\r\n";
                $code .= "\t\r\n";
                $code .= "\t\r\n";
                $code .= "\t".'// TO'.'DO: callback routes app_'.$tables['prefix'].''."\r\n";
                $code .= "\t".'function app_'.$tables['prefix'].'_callback($request){'."\r\n";
                $code .= "\t\r\n";
                $code .= "\t\t".'$metadata = array();'."\r\n";
                $code .= "\t\t"."if(".strtoupper($this->config['app']['prefix'])."_RESTAPI2 == true){\r\n";
                $code .= "\t\t\t".'$parameters = $request->get_query_params();'."\r\n";
                $code .= "\t\t".'}else{'."\r\n";
                $code .= "\t\t\t".'$parameters = $request;'."\r\n";
                $code .= "\t\t".'}'."\r\n";
                $code .= "\t\r\n";
                $code .= "\t\t".'if(isset($parameters["numberposts"])){'."\r\n";
                $code .= "\t\t\t".'$numberposts = (int) $parameters["numberposts"];'."\r\n";
                $code .= "\t\t".'}else{'."\r\n";
                $code .= "\t\t\t".'$numberposts =-1;'."\r\n";
                $code .= "\t\t".'}'."\r\n";
                $code .= "\t\t".'$metakey=$metavalue=null;'."\r\n";
                $code .= "\t\r\n";
                $_col_id = 'id';
                $zz = 0;
                foreach($tables['cols'] as $column) {
                    if($column['type'] != 'id') {
                        $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));
                        $code .= "\t\t".'if(isset($parameters["'.$this->str2var($column['title']).'"])){ '."\r\n";
                        $code .= "\t\t\t".'if($parameters["'.$this->str2var($column['title']).'"]=="-1"){'."\r\n";
                        $code .= "\t\t\t\t".'$parameters["'.$this->str2var($column['title']).'"]="";'."\r\n";
                        $code .= "\t\t\t".'}'."\r\n";
                        $code .= "\t\t\t".'$metakey = "_'.$name.'";'."\r\n";
                        $code .= "\t\t\t".'$metavalue = esc_sql($parameters["'.$this->str2var($column['title']).'"]);'."\r\n";
                        $code .= "\t\t".'}'."\r\n";
                        $code .= "\t\t".''."\r\n";
                    } else {
                        if($zz == 0) {
                            $_col_id = $this->str2var($column['title']);
                        }
                        $zz++;
                    }
                }
                $code .= "\t\t".'$orderby = "date";'."\r\n";
                $code .= "\t\t".'if(isset($parameters["order"])){'."\r\n";
                $code .= "\t\t\t".'if($parameters["order"]=="random"){'."\r\n";
                $code .= "\t\t\t\t".'$orderby = "rand";'."\r\n";
                $code .= "\t\t\t".'}'."\r\n";
                //foreach ($tables['cols'] as $column)
                //{
                //    if ($column['type'] != 'id')
                //    {
                //         $code .= '          if($parameters["order"]=="' . $this->str2var($column['title']) . '"){' . "\r\n";
                //         $code .= '              $orderby = "' . $this->str2var($column['title']) . '";' . "\r\n";
                //         $code .= '          }' . "\r\n";
                //     }
                //}
                $code .= "\t\t".'}'."\r\n";
                $code .= "\t\t\t".''."\r\n";

                $code .= "\t\t".'$sort = "DESC";'."\r\n";
                $code .= "\t\t".'if(isset($parameters["sort"])){'."\r\n";
                $code .= "\t\t\t".'if($parameters["sort"]=="desc"){'."\r\n";
                $code .= "\t\t\t\t".'$sort = "DESC";'."\r\n";
                $code .= "\t\t\t".'}else{'."\r\n";
                $code .= "\t\t\t\t".'$sort = "ASC";'."\r\n";
                $code .= "\t\t\t".'}'."\r\n";

                $code .= "\t\t".'}'."\r\n";
                $code .= "\t\t\t".''."\r\n";

                $code .= "\t\t".'$posts = get_posts(array("orderby"=>$orderby,"order"=>$sort,"post_type"=> "app_'.$tables['prefix'].'","post_status"=>"publish","numberposts"=> $numberposts,"meta_key"=>$metakey,"meta_value"=>$metavalue));'."\r\n";
                $code .= "\t\t\t".''."\r\n";
                $code .= "\t\t".'foreach($posts as $post){'."\r\n";
                $zz = 0;
                foreach($tables['cols'] as $column) {
                    if($column['type'] != 'id') {
                        if(!isset($column['json'])) {
                            $column['json'] = 'false';
                        }
                        if($column['json'] == 'true') {
                            $name = $this->str2var($tables['prefix'].'_'.$this->str2var($column['title']));

                            $column_type_code = "\t\t\t".'$metadata[$post->ID]["'.$this->str2var($column['title'],false).'"] = get_post_meta($post->ID,"_'.$name.'",true);'."\r\n";
                            switch($column['type']) {
                                case 'to_trusted':
                                    $column_type_code = "\t\t\t".'$metadata[$post->ID]["'.$this->str2var($column['title'],false).'"] = html_entity_decode(get_post_meta($post->ID,"_'.$name.'",true));'."\r\n";
                                    break;
                                case 'date':
                                    $column_type_code = "\t\t\t".'$metadata[$post->ID]["'.$this->str2var($column['title'],false).'"] = mktime( 0,0,0,substr(get_post_meta($post->ID,"_'.$name.'",true),5,2),substr(get_post_meta($post->ID,"_'.$name.'",true),8,2),substr(get_post_meta($post->ID,"_'.$name.'",true),0,4)) * 1000 ;'."\r\n";
                                    break;
                                case 'date_php':
                                    $column_type_code = "\t\t\t".'$metadata[$post->ID]["'.$this->str2var($column['title'],false).'"] = mktime( 0,0,0,substr(get_post_meta($post->ID,"_'.$name.'",true),5,2),substr(get_post_meta($post->ID,"_'.$name.'",true),8,2),substr(get_post_meta($post->ID,"_'.$name.'",true),0,4)) ;'."\r\n";
                                    break;
                                case 'datetime':
                                    $column_type_code = "\t\t\t".'$metadata[$post->ID]["'.$this->str2var($column['title'],false).'"] = mktime(substr(get_post_meta($post->ID,"_'.$name.'",true),11,2),substr(get_post_meta($post->ID,"_'.$name.'",true),14,2),substr(get_post_meta($post->ID,"_'.$name.'",true),17,2),substr(get_post_meta($post->ID,"_'.$name.'",true),5,2),substr(get_post_meta($post->ID,"_'.$name.'",true),8,2),substr(get_post_meta($post->ID,"_'.$name.'",true),0,4)) * 1000 ;'."\r\n";
                                    break;
                                case 'datetime_php':
                                    $column_type_code = "\t\t\t".'$metadata[$post->ID]["'.$this->str2var($column['title'],false).'"] = mktime(substr(get_post_meta($post->ID,"_'.$name.'",true),11,2),substr(get_post_meta($post->ID,"_'.$name.'",true),14,2),substr(get_post_meta($post->ID,"_'.$name.'",true),17,2),substr(get_post_meta($post->ID,"_'.$name.'",true),5,2),substr(get_post_meta($post->ID,"_'.$name.'",true),8,2),substr(get_post_meta($post->ID,"_'.$name.'",true),0,4)) ;'."\r\n";
                                    break;
                                case 'datetime_string':
                                    $column_type_code = "\t\t\t".'$metadata[$post->ID]["'.$this->str2var($column['title'],false).'"] = mktime(substr(get_post_meta($post->ID,"_'.$name.'",true),11,2),substr(get_post_meta($post->ID,"_'.$name.'",true),14,2),substr(get_post_meta($post->ID,"_'.$name.'",true),17,2),substr(get_post_meta($post->ID,"_'.$name.'",true),5,2),substr(get_post_meta($post->ID,"_'.$name.'",true),8,2),substr(get_post_meta($post->ID,"_'.$name.'",true),0,4)) ;'."\r\n";
                                    break;
                            }


                            $code .= $column_type_code;
                        }
                    } else {
                        if($zz == 0) {
                            $code .= "\t\t\t".'$metadata[$post->ID]["'.$this->str2var($column['title'],false).'"] = $post->ID;'."\r\n";
                        }
                        $zz++;
                    }

                }
                $code .= "\t\t".'}'."\r\n";
                $code .= "\t\t\t".''."\r\n";

                $code .= "\t\t".'if(!is_array($metadata)){$metadata = array();}'."\r\n";
                $code .= "\t\t".'$return = array_values($metadata);'."\r\n";
                $code .= "\t\t".'if(isset($_GET["'.$_col_id.'"])){'."\r\n";
                $code .= "\t\t\t\t".'$return = $return[0];'."\r\n";
                $code .= "\t\t".'}'."\r\n";
                $code .= "\t\t".'if (empty($metadata)){return array();}'."\r\n";
                $code .= "\t\t".'return $return;'."\r\n";
                $code .= "\t".'}'."\r\n";
                $code .= "\t\t\t".''."\r\n";
                $code .= "\t\t\t".''."\r\n";
                $code .= "\t".'/** JSON '.$tables['prefix'].' **/'."\r\n";
                $code .= "\t".'function ajax_app_'.$tables['prefix'].'(){'."\r\n";
                $code .= "\t\t".'$request = $_GET;'."\r\n";
                $code .= "\t\t".'$rest_api = $this->app_'.$tables['prefix'].'_callback($request);'."\r\n";
                $code .= "\t\t".'header("Content-type: application/json");'."\r\n";
                $code .= "\t\t".'if (isset($_SERVER["HTTP_ORIGIN"])){'."\r\n";
                $code .= "\t\t\t".'header("Access-Control-Allow-Origin: {$_SERVER[\'HTTP_ORIGIN\']}");'."\r\n";
                $code .= "\t\t".'}'."\r\n";
                $code .= "\t\t".'if(defined("JSON_UNESCAPED_UNICODE")){'."\r\n";
                $code .= "\t\t\t".'die(json_encode($rest_api,JSON_UNESCAPED_UNICODE));'."\r\n";
                $code .= "\t\t".'}else{'."\r\n";
                $code .= "\t\t\t".'die(json_encode($rest_api));'."\r\n";
                $code .= "\t\t".'}'."\r\n";
                $code .= "\t".'}'."\r\n";
                $code .= "\r\n";
            }
        }
        // TODO: REGISTER ROUTER FOR FORM
        $remove_from_query = array(
            'divider',
            'submit',
            'button',
            'reset');
        if(isset($this->config['forms'])) {
            $code .= "\r\n";
            foreach($this->config['forms'] as $forms) {
                $code .= '	/** Register rest router for form request '.$forms['table'].' **/'."\r\n";
                $code .= '	function register_rest_route_app_'.$forms['table'].'_submit(){'."\r\n";
                $code .= '      register_rest_route("'.$this->config['app']['prefix'].'/v2","app_'.$forms['table'].'_submit",array('."\r\n";
                $code .= '          "methods" => "POST",'."\r\n";
                $code .= '          "callback" =>array($this, "app_'.$forms['table'].'_submit_callback"),'."\r\n";
                $code .= '          "permission_callback" => function (WP_REST_Request $request){return true;}'."\r\n";
                $code .= '      ));'."\r\n";
                $code .= '	}'."\r\n";
                $code .= "\r\n";
                $code .= '	/** callback rest router '.$forms['table'].' **/'."\r\n";
                $code .= '   function app_'.$forms['table'].'_submit_callback($request){'."\r\n";
                $code .= "\t\t".'$parameters = $_POST;'."\r\n";
                $code .= "\t\t".'//prepare data post'."\r\n";
                $code .= "\t\t".'$new_post_arg = array('."\r\n";
                $code .= "\t\t\t".'"post_title" => "form app '.$forms['table'].'",'."\r\n";
                $code .= "\t\t\t".'"post_content" => "",'."\r\n";
                $code .= "\t\t\t".'"post_status" => "pending", // (draft|publish|pending|future|private)'."\r\n";
                $code .= "\t\t\t".'"post_type" => "app_'.$forms['table'].'",'."\r\n";
                $code .= "\t\t".'); '."\r\n";
                $code .= "\t\t".'//insert data post to database'."\r\n";
                $code .= "\t\t".'$new_post_id = wp_insert_post($new_post_arg);'."\r\n";
                $code .= "\t\t".'if($new_post_id){'."\r\n";
                $code .= "\t\t".'include( ABSPATH . "wp-admin/includes/image.php");'."\r\n";
                $code .= "\t\t".'//now you can use $post_id within add_post_meta or update_post_meta '."\r\n";
                $is_file_upload = false;
                foreach($forms['input'] as $input) {
                    if(!in_array($input['type'],$remove_from_query)) {
                        if($input['type'] != 'file') {
                            $code .= "\t\t\t".'if(isset($parameters["'.$this->str2var($input['name']).'"])){'."\r\n";
                            $code .= "\t\t\t\t".'$metavalue_'.$this->str2var($input['name']).' = wp_strip_all_tags($parameters["'.$this->str2var($input['name']).'"]);'."\r\n";
                            $code .= "\t\t\t\t".'if(!add_post_meta($new_post_id ,"_'.$forms['table'].'_'.$this->str2var($input['name']).'", $metavalue_'.$this->str2var($input['name']).', true)){'."\r\n";
                            $code .= "\t\t\t\t\t".'update_post_meta($new_post_id , "_'.$forms['table'].'_'.$this->str2var($input['name']).'", $metavalue_'.$this->str2var($input['name']).'); '."\r\n";
                            $code .= "\t\t\t\t".'}'."\r\n";
                            $code .= "\t\t\t".'}'."\r\n";
                        } else {
                            $is_file_upload = true;
                            $code .= "\t\t\t".'$invalid_file = true;'."\r\n";
                            $code .= "\t\t\t".'if(isset($parameters["'.$this->str2var($input['name']).'"])){'."\r\n";
                            $code .= "\t\t\t\t".'$upload_dir = wp_upload_dir();'."\r\n";
                            $code .= "\t\t\t\t".'$app_upload_dirname = $upload_dir["basedir"]."/" . date("Y") . "/" . date("m");'."\r\n";
                            $code .= "\t\t\t\t".'$app_upload_url = content_url("uploads")."/" . date("Y") . "/" . date("m");'."\r\n";
                            $code .= "\t\t\t\t".'if (!file_exists( $app_upload_dirname )){'."\r\n";
                            $code .= "\t\t\t\t\t".'wp_mkdir_p( $app_upload_dirname );'."\r\n";
                            $code .= "\t\t\t\t".'}'."\r\n";
                            $code .= "\t\t\t\t".'foreach($this->whitelist_files as $image_allowed){'."// whitelist mimetype\r\n";
                            $code .= "\t\t\t\t\t".'$mimetype_image_allowed[] = $image_allowed["mimetype"];'."// create list\r\n";
                            $code .= "\t\t\t\t".'}'."\r\n";
                            $code .= "\t\t\t\t".'$parse_file = explode(";",substr($parameters["'.$this->str2var($input['name']).'"],5,strlen($parameters["'.$this->str2var($input['name']).'"])));'."// parsing file\r\n";
                            $code .= "\t\t\t\t".'$file_'.str2var($input['name'],false).' = base64_decode(str_replace("base64,","",$parse_file[1]));'."\r\n";
                            $code .= "\t\t\t\t".'if(in_array(strtolower($parse_file[0]),$mimetype_image_allowed)){'."// whitelist image\r\n";
                            $code .= "\t\t\t\t\t".'$ext = "tmp";'."\r\n";
                            $code .= "\t\t\t\t\t".'foreach($this->whitelist_files as $image_allowed){'."// searching extention\r\n";
                            //$code .= "\t\t\t\t\t\t" . '$invalid_file = true;' . "\r\n";
                            $code .= "\t\t\t\t\t\t".'if(strtolower($parse_file[0])==$image_allowed["mimetype"]){'."// filter\r\n";
                            $code .= "\t\t\t\t\t\t\t".'$invalid_file = false;'."\r\n";
                            $code .= "\t\t\t\t\t\t\t".'$ext = $image_allowed["ext"];'."\r\n";
                            $code .= "\t\t\t\t\t\t\t".'$file_name = "'.str2var($input['name'],false).'-" . sha1($file_'.str2var($input['name'],false).').".".$ext;'."\r\n";
                            $code .= "\t\t\t\t\t\t\t".'file_put_contents($app_upload_dirname."/".$file_name,$file_'.str2var($input['name'],false).');'."\r\n";
                            $code .= "\t\t\t\t\t\t\t".'$metavalue_'.$this->str2var($input['name']).' = wp_strip_all_tags( $app_upload_url."/".$file_name );'."\r\n";
                            $code .= "\t\t\t\t\t\t\t".'if(!add_post_meta($new_post_id ,"_'.$forms['table'].'_'.$this->str2var($input['name']).'", $metavalue_'.$this->str2var($input['name']).', true)){'."\r\n";
                            $code .= "\t\t\t\t\t\t\t\t".'update_post_meta($new_post_id , "_'.$forms['table'].'_'.$this->str2var($input['name']).'", $metavalue_'.$this->str2var($input['name']).'); '."\r\n";
                            $code .= "\t\t\t\t\t\t\t".'}'."\r\n";
                            $code .= "\t\t\t\t\t\t\t\t".'$file_path = $app_upload_dirname."/".$file_name;'."\r\n";
                            //  $code .= "\t\t\t\t\t\t\t\t" . '$wp_filetype = wp_check_filetype($file_path, null);' . "\r\n";
                            $code .= "\t\t\t\t\t\t\t\t".'$attachment = array('."\r\n";
                            $code .= "\t\t\t\t\t\t\t\t\t".'"guid"           => $metavalue_'.$this->str2var($input['name']).','."\r\n";
                            $code .= "\t\t\t\t\t\t\t\t\t".'"post_mime_type" => $parse_file[0],'."\r\n";
                            $code .= "\t\t\t\t\t\t\t\t\t".'"post_title"     => $file_name,'."\r\n";
                            $code .= "\t\t\t\t\t\t\t\t\t".'"post_status"    => "pending",'."\r\n";
                            $code .= "\t\t\t\t\t\t\t\t\t".'"post_date"      => date("Y-m-d H:i:s")'."\r\n";
                            $code .= "\t\t\t\t\t\t\t\t".');'."\r\n";
                            //  $code .= "\t\t\t\t\t\t\t\t" . 'file_put_contents("data.txt",json_encode($wp_filetype));' . "\r\n";
                            $code .= "\t\t\t\t\t\t\t\t".'$attachment_id = wp_insert_attachment($attachment, $file_path,$new_post_id);'."\r\n";
                            $code .= "\t\t\t\t\t\t\t\t".'$attachment_data = wp_generate_attachment_metadata($attachment_id, $file_path);'."\r\n";
                            $code .= "\t\t\t\t\t\t\t\t".'wp_update_attachment_metadata($attachment_id, $attachment_data);'."\r\n";
                            $code .= "\t\t\t\t\t\t".'}'."\r\n";
                            $code .= "\t\t\t\t\t".'}'."\r\n";
                            $code .= "\t\t\t\t".'}'."// whitelist files\r\n";
                            $code .= "\t\t\t".'}'."\r\n";
                        }
                    }
                }
                if($is_file_upload == false) {
                    $code .= "\t\t\t".'$data["message"] = "'.addslashes($forms['msg_ok']).'";'."\r\n";
                    $code .= "\t\t\t".'$data["title"] = "Successfully";'."\r\n";
                } else {
                    $code .= "\t\t\t".'if($invalid_file==false){'."\r\n";
                    $code .= "\t\t\t\t".'$data["message"] = "'.addslashes($forms['msg_ok']).'";'."\r\n";
                    $code .= "\t\t\t\t".'$data["title"] = "Successfully";'."\r\n";
                    $code .= "\t\t\t".'}else{'."\r\n";
                    $code .= "\t\t\t\t".'$data["message"] = "Please upload valid file";'."\r\n";
                    $code .= "\t\t\t\t".'$data["title"] = "File invalid!";'."\r\n";
                    $code .= "\t\t\t".'}'."\r\n";
                }
                $code .= "\t\t".'}else{'."\r\n";
                $code .= "\t\t\t".'$data["message"] = "'.addslashes($forms['msg_error']).'";'."\r\n";
                $code .= "\t\t\t".'$data["title"] = "Notice!";'."\r\n";
                $code .= "\t\t".'}'."\r\n";
                $code .= "\t\t".'return $data;'."\r\n";
                $code .= "\t".'}'."\r\n";
                $code .= "\r\n";
                $code .= "\t".'/** JSON '.$forms['table'].' **/'."\r\n";
                $code .= "\t".'function ajax_app_'.$forms['table'].'_submit(){'."\r\n";
                $code .= "\t\t".'$rest_api = $this->app_'.$forms['table'].'_submit_callback($POST);'."\r\n";
                $code .= "\t\t".'header("Content-type: application/json");'."\r\n";
                $code .= "\t\t".'if (isset($_SERVER["HTTP_ORIGIN"])){'."\r\n";
                $code .= "\t\t\t".'header("Access-Control-Allow-Origin: {$_SERVER[\'HTTP_ORIGIN\']}");'."\r\n";
                $code .= "\t\t".'}'."\r\n";
                $code .= "\t\t".'if(defined("JSON_UNESCAPED_UNICODE")){'."\r\n";
                $code .= "\t\t\t".'die(json_encode($rest_api,JSON_UNESCAPED_UNICODE));'."\r\n";
                $code .= "\t\t".'}else{'."\r\n";
                $code .= "\t\t\t".'die(json_encode($rest_api));'."\r\n";
                $code .= "\t\t".'}'."\r\n";
                $code .= "\t".'}'."\r\n";
                $code .= "\t"."\r\n";
            }
        }
        return $code;
    }
    /**
     * WpPluginGenerator::code()
     * 
     * @return
     */
    function php()
    {
        $code = '<?php'."\r\n";
        $code .= $this->codeHeader();
        $code .= 'class App'.str_replace(' ','',ucwords(str_replace(array('_','-'),' ',$this->config['app']['prefix']))).'{'."\r\n";
        $code .= $this->codeConstruct();
        $code .= $this->codeTextdomain();
        $code .= $this->codePostType();
        $code .= $this->codeMetaBox();
        $code .= $this->codeRestAPI();
        $code .= $this->codeIonicon();
        $code .= '}'."\r\n";
        $code .= 'new App'.str_replace(' ','',ucwords(str_replace(array('_','-'),' ',$this->config['app']['prefix']))).'();'."\r\n";
        return $code;
    }
    function js()
    {
        $code = '(function($){'."\r\n";
        $code .= $this->js;
        $code .= '})(jQuery);'."\r\n";
        return $code;
    }
    /**
     * WpPluginGenerator::str2var()
     * 
     * @param mixed $string
     * @return
     */
    function str2var($string,$lowercase = true)
    {
        $char = 'abcdefghijklmnopqrstuvwxyz_12345678900A';
        $Allow = null;
        if($lowercase == true) {
            $string = strtolower($string);
        } else {
            $char .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        $string = str_replace(array(
            ' ',
            '-',
            '-',
            '__'),'_',($string));
        $string = str_replace(array('___','__'),'_',($string));
        for($i = 0; $i < strlen($string); $i++) {
            if(strstr($char,$string[$i]) != false) {
                $Allow .= $string[$i];
            }
        }
        return $Allow;
    }
    /**
     * WpPluginGenerator::var2text()
     * 
     * @param mixed $string
     * @return
     */
    function var2text($string)
    {
        $_string = str_replace('_',' ',$string);
        return ucwords($_string);
    }
}
$wp = new WpPluginGenerator($config);
$output_php = $wp->php();
$output_js = $wp->js();
$output_texdomain = 'Create a file POT, PO and MO using POEdit or Loco Translate Plugin, 
then rename accordance with textdomain used by the plugin, as follows:
* app_'.str_replace('_','-',$_SESSION['PROJECT']['app']['prefix']).'-de_DE.po
* app_'.str_replace('_','-',$_SESSION['PROJECT']['app']['prefix']).'-id_ID.po
* app_'.str_replace('_','-',$_SESSION['PROJECT']['app']['prefix']).'_ES.po
* app_'.str_replace('_','-',$_SESSION['PROJECT']['app']['prefix']).'-en_US.po
* app_'.str_replace('_','-',$_SESSION['PROJECT']['app']['prefix']).'-de_DE.mo
* app_'.str_replace('_','-',$_SESSION['PROJECT']['app']['prefix']).'-id_ID.mo
* app_'.str_replace('_','-',$_SESSION['PROJECT']['app']['prefix']).'-es_ES.mo
* app_'.str_replace('_','-',$_SESSION['PROJECT']['app']['prefix']).'-en_US.mo
Download:
* http://wordpress.org/extend/plugins/loco-translate
* http://poedit.net/download
';
$php_filename = 'app-'.str_replace('_','-',$_SESSION['PROJECT']['app']['prefix']).'/app-'.str_replace('_','-',$_SESSION['PROJECT']['app']['prefix']).'.php';
$js_filename = 'app-'.str_replace('_','-',$_SESSION['PROJECT']['app']['prefix']).'/js/admin.js';
$textdomain_filename = 'app-'.str_replace('_','-',$_SESSION['PROJECT']['app']['prefix']).'/languages/readme.txt';
$content = null;
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-wordpress fa-stack-1x"></i></span>Backend Tools -&raquo; (IMAB) WordPress Plugin Generator</h4>';
$app_tables = $_SESSION["PROJECT"]['tables'];
if(!isset($_SESSION["PROJECT"]['forms'])) {
    $_SESSION["PROJECT"]['forms'] = array();
}

if(!isset($raw_plugin_generator['wp_plugin_generator']['url'])) {
    $raw_plugin_generator['wp_plugin_generator']['url'] = '';
}

$app_forms = $_SESSION["PROJECT"]['forms'];
$content .= '<p><span class="label label-danger">Info</span> : This feature is used for WordPress as back-end only (data can not display in blog). ';
$content .= 'If you make changes on table, update again your wordpress plugin on your site</p>';
$form_input = null;


$form_input .= '<blockquote class="blockquote blockquote-danger">';
$form_input .= '<h4>'.__('The rules that apply are:').'</h4>';
$form_input .= '<ol>';
$form_input .= '<li>When making changes in <code>tables</code>, <code>forms</code> and <code>this settings</code>, you must replace the code that has been uploaded as well.</li>';
$form_input .= '<li><code>Checked the tables</code> that you want to display on the JSON Files.</li>';
$form_input .= '<li>If you need a column/value did not want to appear in JSON, go to the table and unchecked <code>Source</code> of column that does not want to appear.</li>';
$form_input .= '<li>Column contain character <code>.</code>, <code>:</code>, <code>\'</code> and <code>[]</code> not support for (IMAB) WordPress Plugin Generator.</li>';
$form_input .= '<li><code>Update URL List Item</code> button only for default table, for table with <code>dynamic 1st param</code> or <code>relation</code> that you should edit table manually operated</li>';
$form_input .= '</ol>';
$form_input .= '</blockquote>';

$form_input .= '<div class="row">';
$form_input .= '<div class="col-md-3">';
$form_input .= $bs->FormGroup('wp_plugin_generator[url]','default','text','WordPress URL','http://anaski.net/','URL planning to be used',' ','5',htmlentities($raw_plugin_generator['wp_plugin_generator']['url']));

$form_input .= '</div>';
$form_input .= '<div class="col-md-9">';
$form_input .= '<table class="table table-striped">';
$form_input .= '<thead>';
$form_input .= '<tr><th style="width:50px"></th><th style="width:300px">Tables</th><th>Used for</th><th>(IMAB) Tables</th></tr>';
$form_input .= '</thead>';
$form_input .= '<tbody>';
foreach($app_tables as $app_table) {
    $readonly = $note = $checked = $_note_table = '';
    foreach($app_table['cols'] as $cols) {

        if(preg_match("/\.|\[|\(|\:|\'/",$cols['title'])) {
            $readonly = 'readonly disabled';
            $_note_table = '<blockquote class="blockquote blockquote-danger"><h4>Ops, disable...!!!</h4><p>Reason: Column contain character <code>.</code>, <code>:</code>, <code>\'</code> and <code>[]</code>. Found in variable column <code>'.htmlentities($cols['title']).'</code> is not compatible, replace with <code>'.str_replace(array(
                '[',
                ']',
                '(',
                ')',
                '.'),'_',str2SQL($cols['title'])).'</code> in Table Menu</p></blockquote>';
        }
    }


    if(isset($raw_plugin_generator['wp_plugin_generator']['table'][$app_table['prefix']]['status'])) {
        $checked = $raw_plugin_generator['wp_plugin_generator']['table'][$app_table['prefix']]['status'];
    }
    $note = '<div>';
    $note .= '<ul>';
    foreach($app_forms as $app_form) {
        if($app_form['table'] == $app_table['prefix']) {
            $note .= '<li>form request <code>`'.$app_form['title'].'`</code></li>';
        }
    }
    if($app_table['parent'] != '') {
        $note .= '<li>Data listing <code>`'.$app_table['parent'].'`</code></li>';
    }
    $note .= '</ul>';
    $note .= '</div>';

    $form_input .= '<tr>';
    $form_input .= '<td>';
    $form_input .= '<input type="hidden" name="wp_plugin_generator[table]['.$app_table['prefix'].'][name]" value="'.$app_table['prefix'].'" />';
    $form_input .= $bs->FormGroup('wp_plugin_generator[table]['.$app_table['prefix'].'][status]','inline','checkbox',' ','','',$readonly.' '.$checked,'8','checked');
    $form_input .= '</td>';
    if(!isset($app_table['version'])) {
        $app_table['version'] = '?';
    }
    $form_input .= '<td>';
    $form_input .= '<a href="./?page=tables&prefix='.str2var($app_table['title'],false).'" target="_blank">'.trim($app_table['title']).'</a> ('.$app_table['version'].')';
    $form_input .= $_note_table;
    $form_input .= '</td>';

    $form_input .= '<td>';
    $form_input .= $note;

    $form_input .= '</td>';

    $column_id = 'id';
    foreach($app_table['cols'] as $_col) {
        if($_col['type'] == 'id') {
            $column_id = $_col['title'];
        }
    }
    if(!isset($raw_plugin_generator['wp_plugin_generator']['url'])) {
        $raw_plugin_generator['wp_plugin_generator']['url'] = null;
    }
    $url_list_item = $raw_plugin_generator['wp_plugin_generator']['url'].'/wp-json/'.$file_name.'/v2/app_'.str2var($app_table['title'],false).'';
    $url_single_item = $raw_plugin_generator['wp_plugin_generator']['url'].'/wp-json/'.$file_name.'/v2/app_'.str2var($app_table['title'],false).'?'.$column_id.'=';

    $form_input .= '<td>';
    $form_input .= '<a class="btn btn-xs btn-danger '.$readonly.'" target="_blank" href="./?page=tables&prefix='.str2var($app_table['title'],false).'&source_json=online&url_list_item='.urlencode($url_list_item).'&url_single_item='.urlencode($url_single_item).'&update">'.__('Update URL').'</a>';
    $form_input .= '&nbsp;<a class="btn btn-xs btn-primary '.$readonly.'" target="_blank" href="'.$url_list_item.'">'.__('Check URL').'</a>';

    $form_input .= '</td>';

    $form_input .= '</tr>';
}
$form_input .= '</tbody>';
$form_input .= '</table>';
$form_input .= '</div>';
$form_input .= '</div>';

$form_input .= '<blockquote class="blockquote blockquote-info">Please select the table that you want to display on the JSON Files, then click save setting.</blockquote>';

$form_input .= '<div class="clearfix"></div>';
$form_input .= $bs->FormGroup(null,'default','html',null,$bs->ButtonGroups(null,array(array(
        'name' => 'wp_plugin_save',
        'label' => __('Save Setting'),
        'tag' => 'submit',
        'color' => 'primary'),array(
        'label' => __('Reset'),
        'tag' => 'reset',
        'color' => 'default'))));
$content .= '<ul class="nav nav-tabs">';
$content .= '<li class="active"><a href="#code" data-toggle="tab">'.__('Code Generator').'</a></li>';
$content .= '<li><a href="#help" data-toggle="tab" >'.__('How To Use?').'</a></li>';
$content .= '</ul>';
$content .= '<br/>';
$content .= '<div class="tab-content">';
$content .= '<div class="tab-pane active" id="code">';
$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading">';
$content .= '<h4 class="panel-title">'.__('General').'</h4>';
$content .= '</div>';
$content .= '<div class="panel-body">';
$content .= $bs->Forms('app-save','','post','default',$form_input);
$content .= '</div>';
$content .= '</div>';
$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading">';
$content .= '<h4 class="panel-title">'.__('WordPress Plugin').'</h4>';
$content .= '</div>';
$content .= '<div class="panel-body">';
$content .= '<h4 class="page-title">PHP Code</h4><p>'.$php_filename.'</p><textarea id="code-php">'.htmlentities($output_php).'</textarea>';
$content .= '<h4 class="page-title">JS Code</h4><p>'.$js_filename.'</p><textarea id="code-js">'.htmlentities($output_js).'</textarea>';
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';
$content .= '<div class="tab-pane" id="help">';
$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading">';
$content .= '<h4 class="panel-title">'.__('Help').'</h4>';
$content .= '</div>';
$content .= '<div class="panel-body">';
$content .= '

<ol>
<li>Create some tables in <code>(IMAB) Tables</code></li>
<li>Save setting in <code>(IMAB) WordPress Plugin Generator</code></li>
<li>
Then, on WordPress Site, requires WordPress REST API (Version 2) and must be active in your WordPress Site.
<ol>
<li>Unzip and Upload `rest-api.xxx.zip` to the `/wp-content/plugins/rest-api` directory</li>
<li>Activate the plugin through the \'plugins\' menu in WordPress</li>
<li>Then Unzip and Upload `'.$_SESSION['PROJECT']['app']['prefix'].'.zip` to the `/wp-content/plugins/app-'.str_replace('_','-',$_SESSION['PROJECT']['app']['prefix']).'` directory</li>
<li>Activate the plugin through the \'plugins\' menu in WordPress</li>
</ol>
</li>
<li>Last step, please update the link on the List Item URL session on your (IMAB) Tables.</li>

</ol>

<p><a class="btn btn-danger" href="./'.$filezip.'" >Your WordPress Plugin</a> <a class="btn btn-default" href="https://wordpress.org/plugins/rest-api/">REST API (Version 2)</a></p>';
$content .= '<table class="table table-striped">';
foreach($app_tables as $app_table) {
    $new_colums = array();
    foreach($app_table['cols'] as $col) {
        $new_colums[str2var($col['title'],false)] = $col;
    }
    $content .= '<tr>'."\r\n";
    $content .= '<td colspan="4"><h5 class="text-success">TABLE '.strtoupper(htmlentities($app_table['prefix'])).'</h5></td>'."\r\n";
    $content .= '</tr>'."\r\n";
    $content .= '<tr>';
    $content .= '<td>Method</td>';
    $content .= '<td>JSON For</td>';
    $content .= '<td>Filter By</td>';
    $content .= '<td>URL</td>';
    $content .= '</tr>';
    $content .= '<tr>'."\r\n";
    $content .= '<td>GET</td>'."\r\n";
    $content .= '<td><span class="label label-primary">URL List Item</span></td>'."\r\n";
    $content .= '<td>all</td>'."\r\n";
    $content .= '<td><code>http://[your-domain]/wp-json/'.$_SESSION['PROJECT']['app']['prefix'].'/v2/app_'.$app_table['prefix'].'?numberposts=10</code></td>'."\r\n";
    $content .= '</tr>'."\r\n";
    $content .= '<tr>'."\r\n";
    $content .= '<td>GET</td>'."\r\n";
    $content .= '<td><span class="label label-primary">URL List Item</span></td>'."\r\n";
    $content .= '<td>random</td>'."\r\n";
    $content .= '<td><code>http://[your-domain]/wp-json/'.$_SESSION['PROJECT']['app']['prefix'].'/v2/app_'.$app_table['prefix'].'?order=random</code></td>'."\r\n";
    $content .= '</tr>'."\r\n";
    foreach($new_colums as $col) {
        if($col['type'] !== 'id') {
            $content .= '<tr>'."\r\n";
            $content .= '<td>GET</td>'."\r\n";
            $content .= '<td><span class="label label-info">URL List Item + 1st param</span></td>'."\r\n";
            $content .= '<td>'.(str2var($col['title'],false)).'</td>'."\r\n";
            $content .= '<td><code>http://[your-domain]/wp-json/'.$_SESSION['PROJECT']['app']['prefix'].'/v2/app_'.$app_table['prefix'].'?'.(str2var($col['title'],false)).'=-1&numberposts=10</code></td>'."\r\n";
            $content .= '</tr>'."\r\n";
        }
    }
    $exist_id = false;
    foreach($new_colums as $col) {
        if($col['type'] == 'id') {
            if($exist_id == false) {
                $content .= '<tr>'."\r\n";
                $content .= '<td>GET</td>'."\r\n";
                $content .= '<td><span class="label label-success">URL Single Item</span></td>'."\r\n";
                $content .= '<td>'.(str2var($col['title'])).'</td>'."\r\n";
                $content .= '<td><code>http://[your-domain]/wp-json/'.$_SESSION['PROJECT']['app']['prefix'].'/v2/app_'.$app_table['prefix'].'?'.(str2var($col['title'],false)).'=</code></td>'."\r\n";
                $content .= '</tr>'."\r\n";
                $exist_id = true;
            }
        }
    }
}
if(isset($_SESSION['PROJECT']['forms'])) {
    $forms = $_SESSION['PROJECT']['forms'];
    $content .= '<tr>'."\r\n";
    $content .= '<td colspan="4"><h5 class="text-success">FORM SUBMIT</h5></td>'."\r\n";
    $content .= '</tr>'."\r\n";
    $content .= '<tr>';
    $content .= '<td>Method</td>';
    $content .= '<td>JSON For</td>';
    $content .= '<td>Filter By</td>';
    $content .= '<td>URL</td>';
    $content .= '</tr>';
    foreach($forms as $form) {
        $content .= '<tr>'."\r\n";
        $content .= '<td>POST</td>'."\r\n";
        $content .= '<td><span class="label label-warning">form '.str2var($form['title']).'</span></td>'."\r\n";
        $content .= '<td>-</td>'."\r\n";
        $content .= '<td><code>http://[your-domain]/wp-json/'.$_SESSION['PROJECT']['app']['prefix'].'/v2/app_'.$form['prefix'].'_submit</code></td>'."\r\n";
        $content .= '</tr>'."\r\n";
    }
}
$content .= '</table>';
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';

$zip = new ZipArchive();
if($zip->open($filezip,ZIPARCHIVE::CREATE) !== true) {
    exit("cannot open <$filezip>\n");
}
$zip->addFromString($php_filename,$output_php);
$zip->addFromString($js_filename,$output_js);
$zip->addFromString($textdomain_filename,$output_texdomain);
$zip->close();

// TODO: DEBUG
if(JSM_DEBUG == true) {
    @mkdir('/xampp/htdocs/wwwroot/wordpress.co.id/public_html/wp-content/plugins/app-'.str_replace('_','-',$_SESSION['PROJECT']['app']['prefix']).'/');
    @mkdir('/xampp/htdocs/wwwroot/wordpress.co.id/public_html/wp-content/plugins/app-'.str_replace('_','-',$_SESSION['PROJECT']['app']['prefix']).'/js');
    @mkdir('/xampp/htdocs/wwwroot/wordpress.co.id/public_html/wp-content/plugins/app-'.str_replace('_','-',$_SESSION['PROJECT']['app']['prefix']).'/languages');
    file_put_contents('/xampp/htdocs/wwwroot/wordpress.co.id/public_html/wp-content/plugins/app-'.str_replace('_','-',$_SESSION['PROJECT']['app']['prefix']).'/app-'.$_SESSION['PROJECT']['app']['prefix'].'.php',$output_php);
    file_put_contents('/xampp/htdocs/wwwroot/wordpress.co.id/public_html/wp-content/plugins/app-'.str_replace('_','-',$_SESSION['PROJECT']['app']['prefix']).'/js/admin.js',$output_js);
    file_put_contents('/xampp/htdocs/wwwroot/wordpress.co.id/public_html/wp-content/plugins/app-'.str_replace('_','-',$_SESSION['PROJECT']['app']['prefix']).'/languages/readme.txt',$output_texdomain);
}
$footer = '
<link rel="stylesheet" href="./templates/default/vendor/codemirror/lib/codemirror.css">
<script src="./templates/default/vendor/codemirror/lib/codemirror.js"></script>
<script src="./templates/default/vendor/codemirror/mode/clike/clike.js"></script>
<script src="./templates/default/vendor/codemirror/mode/javascript/javascript.js"></script>
<script src="./templates/default/vendor/codemirror/mode/php/php.js"></script>
<script type="text/javascript">
  var editor = CodeMirror.fromTextArea(document.getElementById("code-php"), {
        lineNumbers: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true
  });
  var editor = CodeMirror.fromTextArea(document.getElementById("code-js"), {
        lineNumbers: true,
        matchBrackets: true,
        mode: "text/javascript",
        indentUnit: 4,
        indentWithTabs: true
  });
</script>
';
$template->demo_url = $out_path.'/www/#/';
$template->title = $template->base_title.' | '.'Backend Tools -&raquo; WordPress Plugin Generator';
$template->base_desc = 'Wordpress Plugin Generator';
$template->content = $content;
$template->emulator = false;
$template->footer = $footer;

?>