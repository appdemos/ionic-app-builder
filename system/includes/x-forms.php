<?php

if(!defined('JSM_EXEC'))
{
    die(':)');
}
$file_name = 'test';
$bs = new jsmBootstrap();
$form_input = $html = $js_helper = null;
if(isset($_SESSION['FILE_NAME']))
{
    $file_name = $_SESSION['FILE_NAME'];
} else
{
    header('Location: ./?page=dashboard&err=project');
    die();
}

if(!isset($_SESSION["PROJECT"]['menu']))
{
    header('Location: ./?page=menu&err=new');
    die();
}

if(!isset($_SESSION["PROJECT"]['app']['direction']))
{
    $_SESSION["PROJECT"]['app']['direction'] = 'ltr';
}
$direction = null;
if($_SESSION["PROJECT"]['app']['direction'] == 'rtl')
{
    $direction = 'dir="rtl"';
}
$subpage_path = str2var($_SESSION["PROJECT"]['menu']['title']);

if(!isset($_GET['prefix']))
{
    $_GET['prefix'] = '';
}

if(isset($_GET['delete']))
{
    $delete_prefix = str2var($_GET['delete']);
    @unlink("projects/".$file_name."/forms.".$delete_prefix.".json");
    @unlink("projects/".$file_name."/page.form_".$delete_prefix.".json");
    buildIonic($file_name);
    header('Location: ./?page=x-forms&err=null&notice=delete');
    die();
}

// TODO: --|-- SAVE - FORM
if(isset($_POST['forms-save']))
{
    $page_background = false;
    if(!empty($_POST['forms']['title']))
    {
        $forms_prefix = str2var($_POST['forms']['title']);
        if(!is_dir('projects/'.$file_name))
        {
            mkdir('projects/'.$file_name,0777,true);
        }

        if(strlen($forms_prefix) == '')
        {
            $forms_prefix = 'undefined';
        }

        $new_forms['forms'][$forms_prefix] = $_POST['forms'];
        $new_forms['forms'][$forms_prefix]['prefix'] = $forms_prefix;
        $new_forms['forms'][$forms_prefix]['version'] = $_POST['forms']['version'];
        //$new_forms['forms'][$forms_prefix]['tb_version'] = '';

        if(!isset($_POST['forms']['input']))
        {
            $_POST['forms']['input'] = array();
        }
        $new_forms['forms'][$forms_prefix]['input'] = array();
        $z = 0;
        foreach($_POST['forms']['input'] as $input)
        {
            if($input['name'] != '')
            {
                $new_forms['forms'][$forms_prefix]['input'][$z] = $input;
                $new_forms['forms'][$forms_prefix]['input'][$z]['name'] = str2var($input['name'],false);
                $z++;
            }
        }
        if($_POST['forms']['table'] == 'none')
        {
            $new_forms['forms'][$forms_prefix]['table'] = $forms_prefix;
        }

        file_put_contents('projects/'.$file_name.'/forms.'.$forms_prefix.'.json',json_encode($new_forms));


        // TODO: --|-- POPOVER
        $subpage_path = str2var($_SESSION["PROJECT"]['menu']['title']);
        $menu_link = '#/'.$subpage_path.'/form_'.$forms_prefix;

        if(!file_exists('projects/'.$file_name.'/popover.json'))
        {
            $popover_data['popover']['icon'] = 'ion-android-more-vertical';
        } else
        {
            $popover_data = json_decode(file_get_contents('projects/'.$file_name.'/popover.json'),true);
        }


        if(!isset($popover_data['popover']['menu']))
        {
            $popover_data['popover']['menu'][0] = array(
                'title' => htmlentities($_POST['forms']['title']),
                'link' => $menu_link,
                'type' => 'link');
        } else
        {
            $_is_exist = false;
            if(is_array($popover_data['popover']['menu']))
            {
                foreach($popover_data['popover']['menu'] as $menu)
                {
                    if($menu['link'] == $menu_link)
                    {
                        $_is_exist = true;
                    }
                }
            }
            if($_is_exist == false)
            {
                $popover_data['popover']['menu'][] = array(
                    'title' => htmlentities($_POST['forms']['title']),
                    'link' => $menu_link,
                    'type' => 'link');
            }

        }

        file_put_contents('projects/'.$file_name.'/popover.json',json_encode($popover_data));

        // TODO: --|-- CREATE MARKUP
        $form_markup = $input = null;

        $to_colm[] = array(
            'title' => 'id',
            'label' => 'id',
            'type' => 'id',
            'json' => 'true');

        foreach($new_forms['forms'][$forms_prefix]['input'] as $input)
        {
            $form_markup .= "\r\n";
            $form_markup .= "\r\n\t\t\t".'<!-- input '.str2var(strtolower($input['name'])).' -->';

            //global input
            if($input['type'] == 'toggle')
            {
                $form_markup .= "\r\n\t\t\t".'<div class="item item-toggle" ng-init="form_'.$forms_prefix.'.'.str2var($input['name'],false).'=true">';
                $form_markup .= "\r\n\t\t\t\t".$input['label'];
                $form_markup .= "\r\n\t\t\t\t".'<label class="toggle toggle-assertive">';
                $form_markup .= "\r\n\t\t\t\t\t".'<input ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" type="checkbox">';
                $form_markup .= "\r\n\t\t\t\t\t".'<div class="track"><div class="handle"></div></div>';
                $form_markup .= "\r\n\t\t\t\t".'</label>';
                $form_markup .= "\r\n\t\t\t".'</div>';
            }

            if($input['type'] == 'checkbox')
            {

                $form_markup .= "\r\n\t\t\t".'<ion-checkbox ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" ng-init="form_'.$forms_prefix.'.'.str2var($input['name'],false).'=true">';
                $form_markup .= "\r\n\t\t\t\t".$input['label'];
                $form_markup .= "\r\n\t\t\t".'</ion-checkbox>';

            }

            if($input['type'] == 'cordova-plugin-barcodescanner')
            {

                $form_markup .= "\r\n\t\t\t".'<div class="item item-input-inset">';
                $form_markup .= "\r\n\t\t\t\t".'<label class="item-input-wrapper">';
                $form_markup .= "\r\n\t\t\t\t\t".'<input ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" type="text" placeholder="'.$input['placeholder'].'">';
                $form_markup .= "\r\n\t\t\t\t".'</label>';
                $form_markup .= "\r\n\t\t\t\t".'<a class="button button-small" barcode-scanner barcode-text="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" >'.$input['label'].'</a>';
                $form_markup .= "\r\n\t\t\t".'</div>';
            }

            // TODO: MARKUP - cordova-plugin-geolocation
            if($input['type'] == 'cordova-plugin-geolocation')
            {
                $form_markup .= "\r\n\t\t\t".'<div class="item item-input-inset">';
                $form_markup .= "\r\n\t\t\t\t".'<label class="item-input-wrapper">';
                $form_markup .= "\r\n\t\t\t\t\t".'<input ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" type="text" placeholder="'.$input['placeholder'].'">';
                $form_markup .= "\r\n\t\t\t\t".'</label>';
                $form_markup .= "\r\n\t\t\t\t".'<a class="button button-small" geo-location geo-text="form_'.$forms_prefix.'.'.str2var($input['name'],false).'">'.$input['label'].'</a>';
                $form_markup .= "\r\n\t\t\t".'</div>';   
            }

            // TODO: MARKUP - hidden
            if($input['type'] == 'hidden')
            {
                $form_markup .= "\r\n\t\t\t".'<input type="hidden" ng-value="'.htmlentities($input['placeholder']).'" ng-init="form_'.$forms_prefix.'.'.str2var($input['name'],false).'=\''.htmlentities($input['placeholder']).'\'" ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" />';
            }

            // TODO: MARKUP - radio
            if($input['type'] == 'radio')
            {
                $var = str2var($input['name'],false);

                if($hit_radio[$var] == 0)
                {
                    $init_value = 'ng-init="form_'.$forms_prefix.'.'.str2var($input['name'],false).'=\''.htmlentities($input['label']).'\'"';
                } else
                {
                    $init_value = '';
                }
                $form_markup .= "\r\n\t\t\t".'<ion-radio ng-value="\''.$input['label'].'\'"  ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" '.$init_value.'>';
                $form_markup .= "\r\n\t\t\t\t".$input['label'];
                $form_markup .= "\r\n\t\t\t".'</ion-radio>';
                $hit_radio[$var]++;
            }

            // TODO: MARKUP - select
            if($input['type'] == 'select')
            {
                $options = explode('|',$input['placeholder']);
                if(!is_array($options))
                {
                    $options = array();
                }
                if(!isset($options[0]))
                {
                    $options[0] = '';
                }
                if($options[0] == '')
                {
                    $options[0] = 'option';
                }
                $form_markup .= "\r\n\t\t\t".'<label class="item item-input item-select noborder" ng-init="form_'.$forms_prefix.'.'.str2var($input['name'],false).'=\''.htmlentities($options[0]).'\'">';
                $form_markup .= "\r\n\t\t\t\t".'<span class="input-label">'.$input['label'].'</span>';
                $form_markup .= "\r\n\t\t\t\t".'<select ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" >';
                foreach($options as $option)
                {
                    $form_markup .= "\r\n\t\t\t\t\t".'<option value="'.htmlentities($option).'">'.htmlentities($option).'</option>';
                }
                $form_markup .= "\r\n\t\t\t\t".'</select>';

                $form_markup .= "\r\n\t\t\t".'</label>';
            }
            // TODO: MARKUP - range
            if($input['type'] == 'range')
            {
                $form_markup .= "\r\n\t\t\t".'<div class="item range" ng-init="form_'.$forms_prefix.'.'.str2var($input['name'],false).'=50">';
                $form_markup .= "\r\n\t\t\t\t".'<i class="icon ion-ios-arrow-back"></i>';
                $form_markup .= "\r\n\t\t\t\t".'<input type="range" ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'"/>';
                $form_markup .= "\r\n\t\t\t\t".'<i class="icon ion-ios-arrow-forward"></i>';
                $form_markup .= "\r\n\t\t\t".'</div>';
            }
            // TODO: MARKUP - divider
            if($input['type'] == 'divider')
            {
                $form_markup .= "\r\n\t\t\t".'<div class="item item-divider noborder">';
                $form_markup .= "\r\n\t\t\t\t".$input['label'];
                $form_markup .= "\r\n\t\t\t".'</div>';
            }
            // TODO: MARKUP - date
            if($input['type'] == 'date')
            {
                if($input['label'] == '')
                {
                    $input['label'] = 'Date: ';
                }
                $form_markup .= "\r\n\t\t\t".'<div class="item item item-icon-left" ion-datetime-picker date ng-init="form_'.$forms_prefix.'.'.str2var($input['name'],false).'=\'0\'" ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'">';
                $form_markup .= "\r\n\t\t\t\t".'<i class="icon ion-ios-calendar-outline positive"></i>';
                $form_markup .= "\r\n\t\t\t\t".'<span>'.$input['label'].'</span>';
                $form_markup .= "\r\n\t\t\t\t".'<strong>{{ form_'.$forms_prefix.'.'.str2var($input['name'],false).' | date:\'yyyy-MM-dd\' }}</strong>';
                $form_markup .= "\r\n\t\t\t".'</div>';
            }

            // TODO: MARKUP - datetime
            if($input['type'] == 'datetime')
            {
                if($input['label'] == '')
                {
                    $input['label'] = 'Date/Time: ';
                }
                $form_markup .= "\r\n\t\t\t".'<div class="item item item-icon-left" ion-datetime-picker date time ng-init="form_'.$forms_prefix.'.'.str2var($input['name'],false).'=\'0\'" ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'">';
                $form_markup .= "\r\n\t\t\t\t".'<i class="icon ion-ios-calendar positive"></i>';
                $form_markup .= "\r\n\t\t\t\t".'<span>'.$input['label'].'</span>';
                $form_markup .= "\r\n\t\t\t\t".'<strong>{{ form_'.$forms_prefix.'.'.str2var($input['name'],false).' | date:\'yyyy-MM-dd H:mm:ss\' }}</strong>';
                $form_markup .= "\r\n\t\t\t".'</div>';
            }
            // TODO: MARKUP - time
            if($input['type'] == 'time')
            {
                if($input['label'] == '')
                {
                    $input['label'] = 'Time: ';
                }
                $form_markup .= "\r\n\t\t\t".'<div class="item item item-icon-left" ion-datetime-picker time ng-init="form_'.$forms_prefix.'.'.str2var($input['name'],false).'=\'0\'" ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'">';
                $form_markup .= "\r\n\t\t\t\t".'<i class="icon ion-ios-time positive"></i>';
                $form_markup .= "\r\n\t\t\t\t".'<span>'.$input['label'].'</span>';
                $form_markup .= "\r\n\t\t\t\t".'<strong>{{ form_'.$forms_prefix.'.'.str2var($input['name'],false).' | date:\'H:mm:ss\' }}</strong>';
                $form_markup .= "\r\n\t\t\t".'</div>';
            }

            // TODO: MARKUP - select-table
            if(preg_match("/select\-table\-/i",$input['type']))
            {
                $var_table_name = str_replace('select-table-','',$input['type']);
                $tb_ctrl = $_SESSION['PROJECT']['tables'][$var_table_name];
                $option_value = $option_label = $tb_ctrl['cols'][0]['title'];
                foreach($tb_ctrl['cols'] as $cols)
                {
                    if($cols['type'] == 'id')
                    {
                        $option_label = $option_value = $cols['title'];
                    }
                    if($cols['type'] == 'heading-1')
                    {
                        $option_label = $option_value = $cols['title'];
                    }
                }

                $form_markup .= "\r\n\t\t\t".'<label ng-controller="'.$tb_ctrl['parent'].'Ctrl" class="item item-input item-select noborder" ng-init="form_'.$forms_prefix.'.'.str2var($input['name'],false).'=\''.$input['placeholder'].'\'">';
                $form_markup .= "\r\n\t\t\t\t".'<span class="input-label">'.$input['label'].'</span>';
                $form_markup .= "\r\n\t\t\t\t".'<select ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" >';
                $form_markup .= "\r\n\t\t\t\t\t".'<option ng-repeat="item_'.$tb_ctrl['prefix'].' in data_'.$tb_ctrl['prefix'].'s"  value="{{ item_'.$tb_ctrl['prefix'].'.'.$option_label.' }} ">{{ item_'.$tb_ctrl['prefix'].'.'.$option_label.' }}</option>';
                $form_markup .= "\r\n\t\t\t\t".'</select>';
                $form_markup .= "\r\n\t\t\t".'</label>';
            }


            // TODO: MARKUP - radio-table
            if(preg_match("/radio\-table\-/i",$input['type']))
            {
                $var_table_name = str_replace('radio-table-','',$input['type']);
                $tb_ctrl = $_SESSION['PROJECT']['tables'][$var_table_name];
                $option_value = $option_label = $tb_ctrl['cols'][0]['title'];
                foreach($tb_ctrl['cols'] as $cols)
                {
                    if($cols['type'] == 'id')
                    {
                        $option_label = $option_value = $cols['title'];
                    }
                    if($cols['type'] == 'heading-1')
                    {
                        $option_label = $option_value = $cols['title'];
                    }
                }
                $form_markup .= "\r\n\t\t\t".'<div ng-controller="'.$tb_ctrl['parent'].'Ctrl" ng-init="form_'.$forms_prefix.'.'.str2var($input['name'],false).'=\''.htmlentities($input['placeholder']).'\'">';
                $form_markup .= "\r\n\t\t\t\t".'<ion-radio ng-repeat="item_'.$tb_ctrl['prefix'].' in data_'.$tb_ctrl['prefix'].'s" ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" ng-value="item_'.$tb_ctrl['prefix'].'.'.$option_label.'">';
                $form_markup .= "\r\n\t\t\t\t\t".'{{ item_'.$tb_ctrl['prefix'].'.'.$option_label.' }}';
                $form_markup .= "\r\n\t\t\t\t".'</ion-radio>';
                $form_markup .= "\r\n\t\t\t".'</div>';

            }

            switch($new_forms['forms'][$forms_prefix]['style'])
            {
                case 'placeholder':

                    switch($input['type'])
                    {
                        case 'text':
                            $form_markup .= "\r\n\t\t\t".'<label class="item item-input item-placeholder-label" >';
                            $form_markup .= "\r\n\t\t\t\t".'<input type="text" ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['placeholder'].'" ng-required="true"/>';
                            $form_markup .= "\r\n\t\t\t".'</label>';
                            break;


                        case 'file':
                            $form_markup .= "\r\n\t\t\t".'<label class="item item-input item-placeholder-label">';
                            $form_markup .= "\r\n\t\t\t\t".'<input type="file" fileread="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['placeholder'].'" ng-required="true"/>';
                            $form_markup .= "\r\n\t\t\t".'</label>';
                            break;

                        case 'textarea':
                            $form_markup .= "\r\n\t\t\t".'<label class="item item-input item-placeholder-label">';
                            $form_markup .= "\r\n\t\t\t\t".'<textarea ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['placeholder'].'" ng-required="true"></textarea>';
                            $form_markup .= "\r\n\t\t\t".'</label>';
                            break;
                        case 'password':
                            $form_markup .= "\r\n\t\t\t".'<label class="item item-input item-placeholder-label">';
                            $form_markup .= "\r\n\t\t\t\t".'<input type="password" ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['placeholder'].'" ng-required="true"/>';
                            $form_markup .= "\r\n\t\t\t".'</label>';
                            break;
                        case 'number':
                            $form_markup .= "\r\n\t\t\t".'<label class="item item-input item-placeholder-label">';
                            $form_markup .= "\r\n\t\t\t\t".'<input type="number" ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['placeholder'].'" ng-required="true"/>';
                            $form_markup .= "\r\n\t\t\t".'</label>';
                            break;
                        case 'email':
                            $form_markup .= "\r\n\t\t\t".'<label class="item item-input item-placeholder-label">';
                            $form_markup .= "\r\n\t\t\t\t".'<input type="email" ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['placeholder'].'" ng-required="true"/>';
                            $form_markup .= "\r\n\t\t\t".'</label>';
                            break;
                    }
                    $page_background = false;
                    break;
                case 'inline':
                    switch($input['type'])
                    {
                        case 'text':
                            $form_markup .= "\r\n\t\t\t".'<label class="item item-input item-inline">';
                            $form_markup .= "\r\n\t\t\t\t".'<span class="input-label">'.$input['label'].'</span>';
                            $form_markup .= "\r\n\t\t\t\t".'<input type="text" ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['placeholder'].'" ng-required="true"/>';
                            $form_markup .= "\r\n\t\t\t".'</label>';
                            break;
                        case 'file':
                            $form_markup .= "\r\n\t\t\t".'<label class="item item-input item-inline">';
                            $form_markup .= "\r\n\t\t\t\t".'<span class="input-label">'.$input['label'].'</span>';
                            $form_markup .= "\r\n\t\t\t\t".'<input type="file" fileread="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['placeholder'].'" ng-required="true"/>';
                            $form_markup .= "\r\n\t\t\t".'</label>';
                            break;
                        case 'textarea':
                            $form_markup .= "\r\n\t\t\t".'<label class="item item-input item-inline">';
                            $form_markup .= "\r\n\t\t\t\t".'<span class="input-label">'.$input['label'].'</span>';
                            $form_markup .= "\r\n\t\t\t\t".'<textarea ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['label'].'" ng-required="true"></textarea>';
                            $form_markup .= "\r\n\t\t\t".'</label>';
                            break;
                        case 'password':
                            $form_markup .= "\r\n\t\t\t".'<label class="item item-input item-inline">';
                            $form_markup .= "\r\n\t\t\t\t".'<span class="input-label">'.$input['label'].'</span>';
                            $form_markup .= "\r\n\t\t\t\t".'<input type="password" ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['placeholder'].'" ng-required="true"/>';
                            $form_markup .= "\r\n\t\t\t".'</label>';
                            break;
                        case 'number':
                            $form_markup .= "\r\n\t\t\t".'<label class="item item-input item-inline">';
                            $form_markup .= "\r\n\t\t\t\t".'<span class="input-label">'.$input['label'].'</span>';
                            $form_markup .= "\r\n\t\t\t\t".'<input type="number" ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['placeholder'].'" ng-required="true"/>';
                            $form_markup .= "\r\n\t\t\t".'</label>';
                            break;
                        case 'email':
                            $form_markup .= "\r\n\t\t\t".'<label class="item item-input item-inline">';
                            $form_markup .= "\r\n\t\t\t\t".'<span class="input-label">'.$input['label'].'</span>';
                            $form_markup .= "\r\n\t\t\t\t".'<input type="email" ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['placeholder'].'" ng-required="true"/>';
                            $form_markup .= "\r\n\t\t\t".'</label>';
                            break;
                    }
                    $page_background = false;
                    break;
                case 'stacked':
                    switch($input['type'])
                    {
                        case 'text':
                            $form_markup .= "\r\n\t\t\t".'<label class="item item-input item-stacked-label">';
                            $form_markup .= "\r\n\t\t\t\t".'<span class="input-label">'.$input['label'].'</span>';
                            $form_markup .= "\r\n\t\t\t\t".'<input type="text" ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['placeholder'].'" ng-required="true"/>';
                            $form_markup .= "\r\n\t\t\t".'</label>';
                            break;
                        case 'file':
                            $form_markup .= "\r\n\t\t\t".'<label class="item item-input item-stacked-label">';
                            $form_markup .= "\r\n\t\t\t\t".'<span class="input-label">'.$input['label'].'</span>';
                            $form_markup .= "\r\n\t\t\t\t".'<input type="file" fileread="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['placeholder'].'" ng-required="true"/>';
                            $form_markup .= "\r\n\t\t\t".'</label>';
                            break;
                        case 'textarea':
                            $form_markup .= "\r\n\t\t\t".'<label class="item item-input item-stacked-label">';
                            $form_markup .= "\r\n\t\t\t\t".'<span class="input-label">'.$input['label'].'</span>';
                            $form_markup .= "\r\n\t\t\t\t".'<textarea ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['label'].'" ng-required="true"></textarea>';
                            $form_markup .= "\r\n\t\t\t".'</label>';
                            break;
                        case 'password':
                            $form_markup .= "\r\n\t\t\t".'<label class="item item-input item-stacked-label">';
                            $form_markup .= "\r\n\t\t\t\t".'<span class="input-label">'.$input['label'].'</span>';
                            $form_markup .= "\r\n\t\t\t\t".'<input type="password" ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['placeholder'].'" ng-required="true"/>';
                            $form_markup .= "\r\n\t\t\t".'</label>';
                            break;
                        case 'number':
                            $form_markup .= "\r\n\t\t\t".'<label class="item item-input item-stacked-label">';
                            $form_markup .= "\r\n\t\t\t\t".'<span class="input-label">'.$input['label'].'</span>';
                            $form_markup .= "\r\n\t\t\t\t".'<input type="number" ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['placeholder'].'" ng-required="true"/>';
                            $form_markup .= "\r\n\t\t\t".'</label>';
                            break;
                        case 'email':
                            $form_markup .= "\r\n\t\t\t".'<label class="item item-input item-stacked-label">';
                            $form_markup .= "\r\n\t\t\t\t".'<span class="input-label">'.$input['label'].'</span>';
                            $form_markup .= "\r\n\t\t\t\t".'<input type="email" ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['placeholder'].'" ng-required="true"/>';
                            $form_markup .= "\r\n\t\t\t".'</label>';
                            break;
                    }
                    $page_background = false;
                    break;
                case 'floating':
                    switch($input['type'])
                    {
                        case 'text':
                            $form_markup .= "\r\n\t\t\t".'<label class="item item-input item-floating-label">';
                            $form_markup .= "\r\n\t\t\t\t".'<span class="input-label">'.$input['label'].'</span>';
                            $form_markup .= "\r\n\t\t\t\t".'<input type="text" ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['placeholder'].'" ng-required="true"/>';
                            $form_markup .= "\r\n\t\t\t".'</label>';
                            break;
                        case 'file':
                            $form_markup .= "\r\n\t\t\t".'<label class="item item-input item-floating-label">';
                            $form_markup .= "\r\n\t\t\t\t".'<span class="input-label">'.$input['label'].'</span>';
                            $form_markup .= "\r\n\t\t\t\t".'<input type="file" fileread="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['placeholder'].'" ng-required="true"/>';
                            $form_markup .= "\r\n\t\t\t".'</label>';
                            break;
                        case 'textarea':
                            $form_markup .= "\r\n\t\t\t".'<label class="item item-input item-floating-label">';
                            $form_markup .= "\r\n\t\t\t\t".'<span class="input-label">'.$input['label'].'</span>';
                            $form_markup .= "\r\n\t\t\t\t".'<textarea ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['label'].'" ng-required="true"></textarea>';
                            $form_markup .= "\r\n\t\t\t".'</label>';
                            break;
                        case 'password':
                            $form_markup .= "\r\n\t\t\t".'<label class="item item-input item-floating-label">';
                            $form_markup .= "\r\n\t\t\t\t".'<span class="input-label">'.$input['label'].'</span>';
                            $form_markup .= "\r\n\t\t\t\t".'<input type="password" ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['placeholder'].'" ng-required="true"/>';
                            $form_markup .= "\r\n\t\t\t".'</label>';
                            break;
                        case 'number':
                            $form_markup .= "\r\n\t\t\t".'<label class="item item-input item-floating-label">';
                            $form_markup .= "\r\n\t\t\t\t".'<span class="input-label">'.$input['label'].'</span>';
                            $form_markup .= "\r\n\t\t\t\t".'<input type="number" ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['placeholder'].'" ng-required="true"/>';
                            $form_markup .= "\r\n\t\t\t".'</label>';
                            break;
                        case 'email':
                            $form_markup .= "\r\n\t\t\t".'<label class="item item-input item-floating-label">';
                            $form_markup .= "\r\n\t\t\t\t".'<span class="input-label">'.$input['label'].'</span>';
                            $form_markup .= "\r\n\t\t\t\t".'<input type="email" ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['placeholder'].'" ng-required="true"/>';
                            $form_markup .= "\r\n\t\t\t".'</label>';
                            break;
                    }
                    $page_background = false;
                    break;
                case 'md-input':
                    switch($input['type'])
                    {
                        case 'text':
                            $form_markup .= "\r\n\t\t\t".'<ion-md-input ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['label'].'" highlight-color="energized" type="text" ng-required="true"></ion-md-input>';
                            break;
                        case 'file':
                            $form_markup .= "\r\n\t\t\t".'<label class="item item-input item-floating-label">';
                            $form_markup .= "\r\n\t\t\t\t".'<span class="input-label">'.$input['label'].'</span>';
                            $form_markup .= "\r\n\t\t\t\t".'<input type="file" fileread="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['placeholder'].'" ng-required="true"/>';
                            $form_markup .= "\r\n\t\t\t".'</label>';
                            break;
                        case 'textarea':
                            $form_markup .= "\r\n\t\t\t".'<ion-md-input ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['label'].'" highlight-color="energized" type="text" ng-required="true"></ion-md-input>';
                            break;
                        case 'password':
                            $form_markup .= "\r\n\t\t\t".'<ion-md-input ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['label'].'" highlight-color="energized" type="password" ng-required="true"></ion-md-input>';
                            break;
                        case 'number':
                            $form_markup .= "\r\n\t\t\t".'<ion-md-input ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['label'].'" highlight-color="energized" type="number" ng-required="true"></ion-md-input>';
                            break;
                        case 'email':
                            $form_markup .= "\r\n\t\t\t".'<ion-md-input ng-model="form_'.$forms_prefix.'.'.str2var($input['name'],false).'" name="'.str2var($input['name'],false).'" placeholder="'.$input['label'].'" highlight-color="energized" type="email" ng-required="true"></ion-md-input>';
                            break;
                    }
                    $page_background = true;
                    break;
            }

            switch($input['type'])
            {
                case 'cordova-plugin-geolocation':
                    $to_colm[] = array(
                        'title' => str2var($input['name'],false),
                        'label' => $input['label'].': [txt]',
                        'type' => 'text',
                        'json' => 'true');
                    break;
                case 'cordova-plugin-barcodescanner':
                    $to_colm[] = array(
                        'title' => str2var($input['name'],false),
                        'label' => $input['label'].': [txt]',
                        'type' => 'text',
                        'json' => 'true');
                    break;

                case 'select':
                    $to_colm[] = array(
                        'title' => str2var($input['name'],false),
                        'label' => $input['label'].': [txt]',
                        'type' => 'text',
                        'json' => 'true');
                    break;
                case 'text':
                    $to_colm[] = array(
                        'title' => str2var($input['name'],false),
                        'label' => $input['label'].': [txt]',
                        'type' => 'text',
                        'json' => 'true');
                    break;
                case 'date':
                    $to_colm[] = array(
                        'title' => str2var($input['name'],false),
                        'label' => $input['label'].': [txt]',
                        'type' => 'text',
                        'json' => 'true');
                    break;
                case 'datetime':
                    $to_colm[] = array(
                        'title' => str2var($input['name'],false),
                        'label' => $input['label'].': [txt]',
                        'type' => 'text',
                        'json' => 'true');
                    break;
                case 'time':
                    $to_colm[] = array(
                        'title' => str2var($input['name'],false),
                        'label' => $input['label'].': [txt]',
                        'type' => 'text',
                        'json' => 'true');
                    break;

                case 'hidden':
                    $to_colm[] = array(
                        'title' => str2var($input['name'],false),
                        'label' => $input['label'].': [txt]',
                        'type' => 'text',
                        'json' => 'true');
                    break;
                case 'file':
                    $to_colm[] = array(
                        'title' => str2var($input['name'],false),
                        'label' => $input['label'].': [txt]',
                        'type' => 'text',
                        'json' => 'true');
                    break;
                case 'textarea':
                    $to_colm[] = array(
                        'title' => str2var($input['name'],false),
                        'label' => $input['label'],
                        'type' => 'to_trusted',
                        'json' => 'true');
                    break;
                case 'password':
                    $to_colm[] = array(
                        'title' => str2var($input['name'],false),
                        'label' => $input['label'].': [txt]',
                        'type' => 'text',
                        'json' => 'false');
                    break;
                case 'number':
                    $to_colm[] = array(
                        'title' => str2var($input['name'],false),
                        'label' => $input['label'].': [txt]',
                        'type' => 'text',
                        'json' => 'true');
                    break;
                case 'email':
                    $to_colm[] = array(
                        'title' => str2var($input['name'],false),
                        'label' => $input['label'].': [txt]',
                        'type' => 'text',
                        'json' => 'true');
                    break;
                case 'toggle':
                    $to_colm[] = array(
                        'title' => str2var($input['name'],false),
                        'label' => $input['label'].': [txt]',
                        'type' => 'text',
                        'json' => 'true');
                    break;
                case 'checkbox':
                    $to_colm[] = array(
                        'title' => str2var($input['name'],false),
                        'label' => $input['label'].': [txt]',
                        'type' => 'text',
                        'json' => 'true');
                    break;
                case 'radio':
                    $to_colm[] = array(
                        'title' => str2var($input['name'],false),
                        'label' => $input['label'].': [txt]',
                        'type' => 'text',
                        'json' => 'true');
                    break;
                case 'range':
                    $to_colm[] = array(
                        'title' => str2var($input['name'],false),
                        'label' => $input['label'].': [txt]',
                        'type' => 'text',
                        'json' => 'true');
                    break;
            }

            if($input['type'] == 'button')
            {
                $form_markup .= "\r\n\t\t\t".'<div class="item item-button noborder">';
                $form_markup .= "\r\n\t\t\t\t".'<button class="button button-assertive ink">'.$input['label'].'</button>';
                $form_markup .= "\r\n\t\t\t".'</div>';
            }

            $form_markup .= "\r\n\t\t\t".'<!-- ./input '.str2var(strtolower($input['name'])).' -->';
        }

        $to_tables['tables'][$forms_prefix]['cols'] = $to_colm;
        $to_tables['tables'][$forms_prefix]['parent'] = '';
        $to_tables['tables'][$forms_prefix]['title'] = $_POST['forms']['title'];
        $to_tables['tables'][$forms_prefix]['template'] = '2-icon';
        $to_tables['tables'][$forms_prefix]['template_single'] = 'tabs';
        $to_tables['tables'][$forms_prefix]['itemtype'] = 'item';
        $to_tables['tables'][$forms_prefix]['itemcolor'] = 'dark';
        $to_tables['tables'][$forms_prefix]['db_type'] = 'offline';
        $to_tables['tables'][$forms_prefix]['db_var'] = '';
        $to_tables['tables'][$forms_prefix]['db_url'] = '';
        $to_tables['tables'][$forms_prefix]['db_url_single'] = '';
        $to_tables['tables'][$forms_prefix]['fetch_per_scroll'] = '1';
        $to_tables['tables'][$forms_prefix]['sample_data'] = 'true';
        $to_tables['tables'][$forms_prefix]['languages']['retrieval_error_title'] = 'Error';
        $to_tables['tables'][$forms_prefix]['languages']['retrieval_error_content'] = 'An error occurred while collecting data';
        $to_tables['tables'][$forms_prefix]['languages']['error_messages'] = 'true';
        $to_tables['tables'][$forms_prefix]['prefix'] = $forms_prefix;

        if($_POST['forms']['table'] == 'none')
        {
            $_POST['forms']['table'] = $forms_prefix;
        }

        if(!file_exists('projects/'.$file_name.'/tables.'.$_POST['forms']['table'].'.json'))
        {
            file_put_contents('projects/'.$file_name.'/tables.'.$_POST['forms']['table'].'.json',json_encode($to_tables));
        }


        $page_content = null;
        $page_content .= "\r\n\t\t".'<div class="list '.$new_forms['forms'][$forms_prefix]['layout'].'"  '.$direction.'>';
        $page_content .= "\r\n\t\t\t".'<form ng-submit="submit'.ucwords($forms_prefix).'()">';
        $page_content .= $form_markup;
        $page_content .= "\r\n\t\t\t".'</form>';
        $page_content .= "\r\n\t\t".'</div>';
        // TODO: --|-- DEBUG - HTML
        if(JSM_DEBUG == true)
        {
            $page_content .= "\r\n";
            //$page_content .= "\r\n\t\t" . '<!-- testing form';
            //$page_content .= "\r\n\t\t" . '<pre>{{form_' . $forms_prefix . ' | json}}</pre>';
            //$page_content .= "\r\n\t\t" . '-->';
            $page_content .= "\r\n";
        }


        $page_content .= "\r\n\t\t".'<br/><br/><br/><br/>';


        $overwrite_files = 'projects/'.$file_name.'/page.form_'.$forms_prefix.'.json';
        $new_page = null;

        $new_page['page'][] = array(
            'title' => $_POST['forms']['title'],
            'prefix' => 'form_'.$forms_prefix,
            'for' => 'forms',
            'last_edit_by' => 'forms',
            'parent' => '',
            'menutype' => $_SESSION['PROJECT']['menu']['type'].'-custom',
            'menu' => '',
            'version' => 'Upd.'.date('ymdhi'),
            'priority' => 'warning',
            'class' => 'has-header',
            'js' => $js_helper,
            'bg_image' => $page_background,
            'content' => $page_content);

        $is_lock = false;
        $lock_path = $overwrite_files;
        if(file_exists($lock_path))
        {
            $lock_data = json_decode(file_get_contents($lock_path),true);
            $is_lock = $lock_data['page'][0]['lock'];

            $new_page['page'][0]['img_bg'] = $lock_data['page'][0]['img_bg'];
            $new_page['page'][0]['img_hero'] = $lock_data['page'][0]['img_hero'];
        }

        if($new_forms['forms'][$forms_prefix]['style'] == 'md-input')
        {
            $new_page['page'][0]['img_bg'] = 'data/images/background/bg12.jpg';
            $new_page['page'][0]['css'] = '#form_'.$forms_prefix.' .list .item {border: 0;}'."\r\n";

        } else
        {
            $new_page['page'][0]['img_bg'] = '';
            $new_page['page'][0]['css'] = '#form_'.$forms_prefix.' .list .item {border: 0;}'."\r\n";
        }

        if($is_lock == true)
        {
            $error_notice[] = 'Page <code>'.$forms_prefix.'</code> is <span class="fa fa-lock"></span> locked.';
        } else
        {
            file_put_contents($overwrite_files,json_encode($new_page));
        }


        buildIonic($file_name);
        header("Location: ./?page=x-forms&prefix=".$forms_prefix.'&err=null&notice=save');
        die();
    }
}

// TODO: --|-- OPTION - SELECT
$_forms_select[0] = array('value' => '','label' => '- '.__('New Form'));


$z = 1;
$db_source[] = array('label' => __('Create Table'),'value' => 'none');
foreach(glob("projects/".$file_name."/tables.*.json") as $filename)
{

    $_list_tabless = json_decode(file_get_contents($filename),true);
    if($_list_tabless['tables'] != null)
    {

        $_key = array_keys($_list_tabless['tables']);
        $key = $_key[0];
        if(!isset($_list_tabless['tables'][$key]['version']))
        {
            $_list_tabless['tables'][$key]['version'] = '?';
        }
        $_forms_select[$z] = array('label' => '|-- '.__('Use column on Table').' "'.ucwords($_list_tabless['tables'][$key]['title']).'" - '.$_list_tabless['tables'][$key]['version'].'','value' => 'tmp_'.$_list_tabless['tables'][$key]['prefix']);
        if($_GET['prefix'] == $_list_tabless['tables'][$key]['prefix'])
        {
            $_forms_select[$z]['active'] = true;
        }

        if(!isset($_list_tabless['tables'][$key]['version']))
        {
            $_list_tabless['tables'][$key]['version'] = '?';
        }

        $db_source[] = array('label' => __('Table').' `'.$_list_tabless['tables'][$key]['title'].'` - '.$_list_tabless['tables'][$key]['version'].'','value' => $_list_tabless['tables'][$key]['prefix']);

        $z++;
    }
}


$z++;
foreach(glob("projects/".$file_name."/forms.*.json") as $filename)
{
    $_list_forms = json_decode(file_get_contents($filename),true);
    $_key = array_keys($_list_forms['forms']);
    $key = $_key[0];
    if(!isset($_list_forms['forms'][$key]['version']))
    {
        $_list_forms['forms'][$key]['version'] = '?';
    }
    $_forms_select[$z] = array('label' => '* '.__('Edit form').' "'.ucwords($_list_forms['forms'][$key]['title']).'" - '.$_list_forms['forms'][$key]['version'].' ','value' => $_list_forms['forms'][$key]['prefix']);

    if($_GET['prefix'] == $_list_forms['forms'][$key]['prefix'])
    {
        $_forms_select[$z]['active'] = true;
    }
    $z++;
}

$forms_prefix = $_GET['prefix'];
$raw_forms['forms'][$forms_prefix]['title'] = null;
$raw_forms['forms'][$forms_prefix]['input'] = array();
$raw_forms['forms'][$forms_prefix]['method'] = 'post';
$raw_forms['forms'][$forms_prefix]['action'] = '#';
$raw_forms['forms'][$forms_prefix]['layout'] = 'list';
$raw_forms['forms'][$forms_prefix]['table'] = '';
$raw_forms['forms'][$forms_prefix]['msg_ok'] = 'Your request has been sent.';
$raw_forms['forms'][$forms_prefix]['msg_error'] = 'Please! complete the form provided.';

if(isset($_GET['prefix']))
{
    $files = 'projects/'.$file_name.'/forms.'.$forms_prefix.'.json';
    if(file_exists($files))
    {
        $raw_forms = json_decode(file_get_contents($files),true);
    } else
    {
        $table_files = 'projects/'.$file_name.'/tables.'.str_replace('tmp_','',$forms_prefix).'.json';
        if(file_exists($table_files))
        {
            $_raw_tables = json_decode(file_get_contents($table_files),true);
            $raw_tables = array_values($_raw_tables['tables']);

            $raw_forms['forms'][$forms_prefix]['table'] = $raw_tables[0]['prefix'];

            if(isset($raw_tables[0]['cols']))
            {
                $z = 0;
                foreach($raw_tables[0]['cols'] as $cols)
                {
                    if($cols['type'] != 'id')
                    {
                        $raw_forms['forms'][$forms_prefix]['input'][$z]['label'] = $cols['title'];
                        $raw_forms['forms'][$forms_prefix]['input'][$z]['placeholder'] = $cols['title'];
                        $raw_forms['forms'][$forms_prefix]['input'][$z]['name'] = str2var($cols['title']);
                        $z++;
                    }

                }

                $raw_forms['forms'][$forms_prefix]['input'][$z]['label'] = 'Submit';
                $raw_forms['forms'][$forms_prefix]['input'][$z]['placeholder'] = 'Submit';
                $raw_forms['forms'][$forms_prefix]['input'][$z]['name'] = 'submit';
                $raw_forms['forms'][$forms_prefix]['input'][$z]['type'] = 'button';
                if(!isset($raw_tables[0]['version']))
                {
                    $raw_tables[0]['version'] = '';
                }
                $raw_forms['forms'][$forms_prefix]['version'] = $raw_tables[0]['version'];
            }


        }
    }


}
$z = 0;
foreach($db_source as $table_source)
{
    $_db_source[$z] = $table_source;
    if($table_source['value'] == $raw_forms['forms'][$forms_prefix]['table'])
    {
        $_db_source[$z]['active'] = true;
    }
    $z++;
}

// TODO: --|-- OPTION - FORM STYLE
$styles[] = array('value' => 'placeholder','label' => 'Placeholder Labels');
$styles[] = array('value' => 'inline','label' => 'Inline Labels');
$styles[] = array('value' => 'stacked','label' => 'Stacked Labels');
$styles[] = array('value' => 'floating','label' => 'Floating Labels');
$styles[] = array('value' => 'md-input','label' => 'Material Design Input');

$layouts[] = array('value' => 'list','label' => 'List');
$layouts[] = array('value' => 'card','label' => 'Card');


if(!isset($raw_forms['forms'][$forms_prefix]['style']))
{
    $raw_forms['forms'][$forms_prefix]['style'] = 'floating';
}
$z = 0;
foreach($styles as $style)
{
    $_styles[$z] = $style;
    if($raw_forms['forms'][$forms_prefix]['style'] == $style['value'])
    {
        $_styles[$z]['active'] = true;
    }
    $z++;
}

$z = 0;
foreach($layouts as $layout)
{
    $_layouts[$z] = $layout;
    if($raw_forms['forms'][$forms_prefix]['layout'] == $layout['value'])
    {
        $_layouts[$z]['active'] = true;
    }
    $z++;
}


$out_path = 'output/'.$file_name;
$content = $form_content = null;

// TODO: --|-- OPTION - INPUT TYPE
$input_types[] = array('value' => 'divider','label' => 'Divider');
$input_types[] = array('value' => 'text','label' => 'Text');
$input_types[] = array('value' => 'textarea','label' => 'Textarea');
$input_types[] = array('value' => 'password','label' => 'Password');
$input_types[] = array('value' => 'email','label' => 'Email');
$input_types[] = array('value' => 'number','label' => 'Number');
$input_types[] = array('value' => 'toggle','label' => 'Toggle');
$input_types[] = array('value' => 'checkbox','label' => 'Checkbox');
$input_types[] = array('value' => 'radio','label' => 'Radio');
$input_types[] = array('value' => 'range','label' => 'Range');
$input_types[] = array('value' => 'select','label' => 'Select');
$input_types[] = array('value' => 'button','label' => 'Button');
$input_types[] = array('value' => 'file','label' => 'Small File Upload');
$input_types[] = array('value' => 'date','label' => 'Date');
$input_types[] = array('value' => 'datetime','label' => 'Date/Time');
$input_types[] = array('value' => 'time','label' => 'Time');
$input_types[] = array('value' => 'hidden','label' => 'Hidden Field');
if(!isset($_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable']))
{
    $_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable'] = false;
}
if($_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable'] == true)
{
    $input_types[] = array('value' => 'cordova-plugin-barcodescanner','label' => 'Cordova - Barcode Scanner');
}


if(!isset($_SESSION['PROJECT']['cordova_plugin']['geolocation']['enable']))
{
    $_SESSION['PROJECT']['cordova_plugin']['geolocation']['enable'] = false;
}
if($_SESSION['PROJECT']['cordova_plugin']['geolocation']['enable'] == true)
{
   $input_types[] = array('value' => 'cordova-plugin-geolocation','label' => 'Cordova - Geolocation');
}




foreach($_SESSION['PROJECT']['tables'] as $tb_ctrl)
{
    if(!isset($tb_ctrl['prefix']))
    {
        $tb_ctrl['prefix'] = '???';
    }
    if(!isset($tb_ctrl['parent']))
    {
        $tb_ctrl['parent'] = '???';
    }

    $input_types[] = array('value' => 'select-table-'.$tb_ctrl['prefix'].'','label' => 'Select -> Option from Table `'.$tb_ctrl['prefix'].'`');
    $input_types[] = array('value' => 'radio-table-'.$tb_ctrl['prefix'].'','label' => 'Radio -> Option from Table `'.$tb_ctrl['prefix'].'`');

}


$max_inpunt = count($raw_forms['forms'][$forms_prefix]['input']);

if($max_inpunt == 0)
{
    $max_inpunt = 3;
}
if(isset($_GET['forms']))
{
    $max_inpunt = $_GET['forms'];
}

for($i = 1; $i <= 100; $i++)
{
    if($max_inpunt == $i)
    {
        $max_input[] = array(
            'value' => $i,
            'label' => '- '.$i.' input',
            'active' => true);
    } else
    {
        $max_input[] = array(
            'value' => $i,
            'label' => '- '.$i.' input',
            );
    }
}

$forms_method[] = array("label" => "POST","value" => "post");
//$forms_method[] = array("label" => "GET", "value" => "get");

$z++;
foreach($forms_method as $method)
{
    $_forms_method[$z] = $method;
    if($method['value'] == $raw_forms['forms'][$forms_prefix]['method'])
    {
        $_forms_method[$z]['active'] = true;
    }
    $z++;
}

$form_content .= '<div class="row">';

$form_content .= '<div class="col-md-4">';
$form_content .= $bs->FormGroup('forms[select]','default','select',__('Select Form'),$_forms_select,__('Use column on table ... for avoid error input'));
$form_content .= '</div>';

$form_content .= '<div class="col-md-2">';
$form_content .= $bs->FormGroup('forms[max]','default','select',__('Max Input'),$max_input,' ');
$form_content .= '</div>';

$form_content .= '<div class="col-md-2">';
$form_content .= $bs->FormGroup('forms[method]','default','select',__('Method'),$_forms_method);
$form_content .= '</div>';

$form_content .= '<div class="col-md-4">';
$form_content .= $bs->FormGroup('forms[table]','default','select',__('Insert into : '),$_db_source,'','required','8',$raw_forms['forms'][$forms_prefix]['table']);
$form_content .= '</div>';

$form_content .= '</div>';


$form_content .= '<div class="row">';

$form_content .= '<div class="col-md-4">';
$form_content .= $bs->FormGroup('forms[title]','default','text',__('Form Title'),'',__('<span class="label label-danger">note</span> Changing title will be duplicate form, use <strong>(IMAB) Page</strong> for edit page title'),'required','8',$raw_forms['forms'][$forms_prefix]['title']);
$form_content .= '</div>';

$form_content .= '<div class="col-md-4">';
$form_content .= $bs->FormGroup('forms[action]','default','text',__('URL Action'),'','','required','8',$raw_forms['forms'][$forms_prefix]['action']);
$form_content .= '</div>';

$form_content .= '<div class="col-md-4">';
$form_content .= $bs->FormGroup('forms[msg_ok]','default','text',__('Success Message'),'','','required','8',htmlentities($raw_forms['forms'][$forms_prefix]['msg_ok']));
$form_content .= '</div>';

$form_content .= '</div>';


$form_content .= '<div class="row">';

$form_content .= '<div class="col-md-4">';
$form_content .= $bs->FormGroup('forms[layout]','default','select',__('Page Layout'),$_layouts);
$form_content .= '</div>';

$form_content .= '<div class="col-md-4">';
$form_content .= $bs->FormGroup('forms[style]','default','select',__('Style'),$_styles);
$form_content .= '</div>';

$form_content .= '<div class="col-md-4">';
$form_content .= $bs->FormGroup('forms[msg_error]','default','text',__('Error Message'),'','','','8',htmlentities($raw_forms['forms'][$forms_prefix]['msg_error']));
$form_content .= '</div>';

if(!isset($raw_forms['forms'][$forms_prefix]['version']))
{
    $raw_forms['forms'][$forms_prefix]['version'] = '';
}
$form_content .= '<input type="hidden" name="forms[version]" value="'.htmlentities($raw_forms['forms'][$forms_prefix]['version']).'" />';

$form_content .= '</div>';

if($raw_forms['forms'][$forms_prefix]['title'] != '')
{
    $form_content .= '<div class="row">';
    $form_content .= '<div class="col-md-12">';

    $file_tables = 'projects/'.$file_name.'/tables.'.str2var($raw_forms['forms'][$forms_prefix]['table']).'.json';


    //note

    $form_content .= '<table class="table">';
    $form_content .= '<thead>';
    $form_content .= '<tr>';
    $form_content .= '<th>BackEnd</th><th>URL Action</th><th>Status</th>';
    $form_content .= '</tr>';
    $form_content .= '</thead>';
    $form_content .= '<tbody>';
    $form_content .= '<tr>';
    $form_content .= '<td>';
    $form_content .= 'PHP+MySQL RESTAPI';
    $form_content .= '</td>';

    $form_content .= '<td>';
    if(!file_exists($file_tables))
    {
        $form_content .= '<p>If you use this feature please create a table singular named: '.str2var($raw_forms['forms'][$forms_prefix]['table']).'</p>';
    } else
    {
        $form_content .= '<code>http://[domain]/[php_file].php?form='.str2var($raw_forms['forms'][$forms_prefix]['table']).'&json=submit</code>';
    }
    $form_content .= '</td>';

    $form_content .= '<td>';
    if(!file_exists($file_tables))
    {
        $form_content .= '<span class="label label-warning">Not Compatible</span>';
    } else
    {
        $form_content .= '<span class="label label-success">OK</span>';
    }


    $form_content .= '</td>';
    $form_content .= '</tr>';

    $form_content .= '<tr>';
    $form_content .= '<td>';
    $form_content .= 'WordPress Plugin';
    $form_content .= '</td>';
    $form_content .= '<td>';
    $form_content .= '<code>http://[your-domain]/wp-json/'.$_SESSION['PROJECT']['app']['prefix'].'/v2/app_'.str2var($raw_forms['forms'][$forms_prefix]['table']).'_submit</code>';
    $form_content .= '</td>';
    $form_content .= '<td>';
    $form_content .= '<span class="label label-success">OK</span>';
    $form_content .= '</td>';

    $form_content .= '</tr>';
    $form_content .= '</tbody>';
    $form_content .= '</table>';

    $form_content .= '</div>';
    $form_content .= '</div>';
}

$form_content .= '<hr/>';

$form_content .= '<div class="table-responsive">';
$form_content .= '<table class="table table-striped sortable">';
$form_content .= '<thead>';
$form_content .= '<tr>';
$form_content .= '<th></th><th>'.__('Label').'</th><th>'.__('Name').'</th><th>'.__('Type').'</th><th>'.__('Options/Placeholder').'</th><th></th>';
$form_content .= '</tr>';
$form_content .= '</thead>';

$form_content .= '<tbody>';
for($i = 0; $i < $max_inpunt; $i++)
{
    if(!isset($raw_forms['forms'][$forms_prefix]['input'][$i]['label']))
    {
        $raw_forms['forms'][$forms_prefix]['input'][$i]['label'] = '';
    }

    if(!isset($raw_forms['forms'][$forms_prefix]['input'][$i]['name']))
    {
        $raw_forms['forms'][$forms_prefix]['input'][$i]['name'] = '';
    }

    if(!isset($raw_forms['forms'][$forms_prefix]['input'][$i]['placeholder']))
    {
        $raw_forms['forms'][$forms_prefix]['input'][$i]['placeholder'] = '';
    }

    if(!isset($raw_forms['forms'][$forms_prefix]['input'][$i]['type']))
    {
        $raw_forms['forms'][$forms_prefix]['input'][$i]['type'] = 'text';
    }


    $z = 0;
    $_input_types = array();
    foreach($input_types as $input_type)
    {
        $_input_types[$z] = $input_type;
        if($input_type['value'] == $raw_forms['forms'][$forms_prefix]['input'][$i]['type'])
        {
            $_input_types[$z]['active'] = true;
        }
        $z++;
    }


    $form_content .= '<tr id="data-'.$i.'">';

    $form_content .= '<td class="v-align">';
    $form_content .= '<span class="glyphicon glyphicon-move"></span>';
    $form_content .= '</td>';

    $form_content .= '<td>';
    $form_content .= $bs->FormGroup('forms[input]['.$i.'][label]','default','text',null,'','<strong>format</strong>: text',' '.$direction,'8',htmlentities($raw_forms['forms'][$forms_prefix]['input'][$i]['label']));
    $form_content .= '</td>';

    $form_content .= '<td>';
    $form_content .= $bs->FormGroup('forms[input]['.$i.'][name]','default','text',null,'','<strong>format</strong>: a-z,0-9 and _','','8',htmlentities($raw_forms['forms'][$forms_prefix]['input'][$i]['name']));
    $form_content .= '</td>';

    $form_content .= '<td>';
    $form_content .= $bs->FormGroup('forms[input]['.$i.'][type]','default','select','',$_input_types,'','data-target="#data-helper-'.$i.'"','8','','input-type');
    $form_content .= '</td>';

    $form_content .= '<td>';
    $form_content .= $bs->FormGroup('forms[input]['.$i.'][placeholder]','default','text',null,'','<strong>format</strong>: <span id="data-helper-'.$i.'">text</span>','','8',htmlentities($raw_forms['forms'][$forms_prefix]['input'][$i]['placeholder']));
    $form_content .= '</td>';


    $form_content .= '<td>';
    $form_content .= '<a class="remove-item btn btn-danger btn-sm" href="#!_" data-target="#data-'.$i.'" ><i class="glyphicon glyphicon-trash"></i></a>';
    $form_content .= '</td>';

    $form_content .= '<tr>';
}
$form_content .= '</tbody>';
$form_content .= '</table>';
$form_content .= '</div>';

$button[] = array(
    'name' => 'forms-save',
    'label' => __('Save Form').' &raquo;',
    'tag' => 'submit',
    'color' => 'primary');

$button[] = array(
    'label' => __('Reset'),
    'tag' => 'reset',
    'color' => 'warning');


if($_GET['prefix'] != '')
{
    $button[] = array(
        'label' => __('Delete'),
        'icon' => 'glyphicon glyphicon glyphicon-trash',
        'tag' => 'anchor',
        'color' => 'danger',
        'link' => "./?page=x-forms&delete=".str2var($_GET['prefix']));
}

$form_content .= $bs->FormGroup(null,'default','html',null,$bs->ButtonGroups(null,$button));

$content = null;
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-list-alt fa-stack-1x"></i></span> Extra Menus -&raquo; (IMAB) Form Request</h4>';

$content .= '
<blockquote class="blockquote blockquote-info">
<h4>'.__('Forms Features only work for:').'</h4>
<ul>
    <li>'.__('Backend Using').' <a href="./?page=z-php-sql-restapi-generator" target="_blank">(IMAB) PHP + MySQL RESTAPI Generator</a></li>
    <li>'.__('Backend Using').' <a href="./?page=z-wordpress-plugin-generator" target="_blank">(IMAB) WordPress Plugin Generator</a></li>
</ul>
</blockquote>';

$content .= notice();
$content .= $bs->Forms('app-setup','','post','default',$form_content);

$footer = '
<script type="text/javascript">
$(document).ready(function(){
    $(".input-type").on("click",function(){
        var _val = $(this).val();
        var _target = $(this).attr("data-target");
        
        switch(_val) {
         case "select":
            $(_target).html("separator with |<br/><strong>example</strong>: option1|option2");
          break;
         default:
            $(_target).html("text");
            break;
        }
    });
});    
</script>
';


if(!isset($_SESSION['PROJECT']['page']))
{
    $_SESSION['PROJECT']['page'] = array();
}
$_current_pages = array();
if(is_array($_SESSION['PROJECT']['page']))
{
    foreach($_SESSION['PROJECT']['page'] as $current_page)
    {
        if(!isset($current_page['priority']))
        {
            $current_page['priority'] = 'danger';
        }
        $var = $current_page['prefix'];
        $_current_pages[$var] = $current_page['priority'];
    }
}

$footer .= '<script type="text/javascript">var current_pages = '.json_encode($_current_pages);
if(JSM_DEBUG == true)
{
    $footer .= ';console.log(current_pages);';
}

$footer .= '</script>';

$template->demo_url = $out_path.'/www/#/'.$subpage_path.'/form_'.str2var($_GET['prefix']);
$template->title = $template->base_title.' | '.'Extra Menus -&raquo; Form Request';
$template->base_desc = '';
$template->content = $content;
$template->footer = $footer;

?>