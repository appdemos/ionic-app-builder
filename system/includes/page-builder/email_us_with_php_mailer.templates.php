<?php

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2016
 * @license Commercial License
 * 
 * @package Ionic App Builder
 */
if (isset($_SESSION['FILE_NAME']))
{
    $file_name = $_SESSION['FILE_NAME'];
} else
{
    header('Location: ./?page=dashboard&err=project');
    die();
}

$require_target_page = true;
$pagebuilder_file = 'projects/' . $_SESSION['FILE_NAME'] . '/page_builder.email_us_with_php_mailer.' . str2var($_GET['target']) . '.json';
if (isset($_POST['page-builder']))
{
    $postdata['prefix'] = str2var($_GET['target']);
    $postdata['page_title'] = $_POST['page_title'];
    $postdata['email_recipient'] = $_POST['email_recipient'];

    $postdata['label_email'] = $_POST['label_email'];
    $postdata['label_name'] = $_POST['label_name'];
    $postdata['label_message'] = $_POST['label_message'];
    $postdata['placeholder_email'] = $_POST['placeholder_email'];
    $postdata['placeholder_name'] = $_POST['placeholder_name'];
    $postdata['placeholder_message'] = $_POST['placeholder_message'];
    $postdata['label_button'] = $_POST['label_button'];
    $postdata['url_action'] = $_POST['url_action'];

    $postdata['page_content'] = $_POST['page_content'];
    $postdata['page_background'] = $_POST['page_background'];


    $json_save['page_builder']['email_us_with_php_mailer'][$postdata['prefix']] = $postdata;
    file_put_contents($pagebuilder_file, json_encode($json_save));

    // TODO: + page -+- intro
    $new_page = null;
    $new_page['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $new_page['page'][0]['prefix'] = $postdata['prefix'];
    $new_page['page'][0]['parent'] = '';
    $new_page['page'][0]['for'] = 'feedback';
    $new_page['page'][0]['title'] = $postdata['page_title'];
    $new_page['page'][0]['img_bg'] = $postdata['page_background'];
    $new_page['page'][0]['lock'] = true;
    $new_page['page'][0]['js'] = '$ionicConfig.backButton.text("");';
    $new_page['page'][0]['menu'] = $file_name;
    $new_page['page'][0]['menutype'] = $_SESSION['PROJECT']['menu']['type'] . '-custom';
    $new_page['page'][0]['content'] = '
    <div class="list card">
        <div class="item item-text-wrap">' . $postdata['page_content'] . '</div>
        <a class="item item-icon-left assertive" ng-href="#/' . $file_name . '/form_' . $postdata['prefix'] . '">
        <i class="icon ion-email"></i>
        Feedback
        </a>
    </div>    
    ';
    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.' . $postdata['prefix'] . '.json', json_encode($new_page));


    // TODO: + page -+- form
    $new_page = null;
    $new_page['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $new_page['page'][0]['prefix'] = 'form_' . $postdata['prefix'];
    $new_page['page'][0]['parent'] = '';
    $new_page['page'][0]['for'] = 'forms';
    $new_page['page'][0]['title'] = $postdata['page_title'];
    $new_page['page'][0]['img_bg'] = $postdata['page_background'];
    $new_page['page'][0]['lock'] = true;
    $new_page['page'][0]['js'] = '$ionicConfig.backButton.text("");';
    $new_page['page'][0]['menu'] = $file_name;
    $new_page['page'][0]['menutype'] = $_SESSION['PROJECT']['menu']['type'] . '-custom';
    $new_page['page'][0]['content'] = '
<div class="list card"  >
	<form ng-submit="submit' . ucwords($postdata['prefix']) . '()">

	<!-- input feedback_email -->
	<label class="item item-input item-stacked-label">
		<span class="input-label">' . $postdata['label_email'] . '</span>
		<input type="email" ng-model="form_' . $postdata['prefix'] . '.feedback_email" name="feedback_email" placeholder="' . $postdata['placeholder_email'] . '" ng-required="true"/>
	</label>
	<!-- ./input feedback_email -->

	<!-- input feedback_name -->
	<label class="item item-input item-stacked-label">
		<span class="input-label">' . $postdata['label_name'] . '</span>
		<input type="text" ng-model="form_' . $postdata['prefix'] . '.feedback_name" name="feedback_name" placeholder="' . $postdata['placeholder_name'] . '" ng-required="true"/>
	</label>
	<!-- ./input feedback_name -->

	<!-- input feedback_content -->
	<label class="item item-input item-stacked-label">
		<span class="input-label">' . $postdata['label_message'] . '</span>
		<textarea ng-model="form_' . $postdata['prefix'] . '.feedback_message" name="feedback_message" placeholder="' . $postdata['label_message'] . '" ng-required="true">' . htmlentities($postdata['placeholder_message']) . '</textarea>
	</label>
	<!-- ./input feedback_content -->

	<!-- input send -->
	<div class="item item-button noborder">
		<button class="button button-assertive ink">' . $postdata['label_button'] . '</button>
	</div>
	<!-- ./input send -->
	</form>
</div>
<br/><br/><br/><br/>
 
    ';
    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.form_' . $postdata['prefix'] . '.json', json_encode($new_page));

    $new_table = null;
    $_var = $postdata['prefix'];
    $new_table['tables'][$_var]['prefix'] = $_var;
    $new_table['tables'][$_var]['title'] = $_var;
    $new_table['tables'][$_var]['db_type'] = 'online';
    $new_table['tables'][$_var]['db_type'] = 'online';
    $new_table['tables'][$_var]['db_var'] = '';
    $new_table['tables'][$_var]['db_url'] = '';
    $new_table['tables'][$_var]['db_url_single'] = '';
    $new_table['tables'][$_var]['parent'] = '';
    $new_table['tables'][$_var]['version'] = 'Upd.' . date('ymdhi');
    $new_table['tables'][$_var]['builder_link'] = @$_SERVER["HTTP_REFERER"];


    $y = 0;
    $new_table['tables'][$_var]['cols'][$y]['title'] = 'feedback_id';
    $new_table['tables'][$_var]['cols'][$y]['label'] = 'feedback_id';
    $new_table['tables'][$_var]['cols'][$y]['type'] = 'id';
    $new_table['tables'][$_var]['cols'][$y]['json'] = 'true';

    $y++;
    $new_table['tables'][$_var]['cols'][$y]['title'] = 'feedback_email';
    $new_table['tables'][$_var]['cols'][$y]['label'] = 'feedback_email';
    $new_table['tables'][$_var]['cols'][$y]['type'] = 'text';
    $new_table['tables'][$_var]['cols'][$y]['json'] = 'true';

    $y++;
    $new_table['tables'][$_var]['cols'][$y]['title'] = 'feedback_name';
    $new_table['tables'][$_var]['cols'][$y]['label'] = 'feedback_name';
    $new_table['tables'][$_var]['cols'][$y]['type'] = 'text';
    $new_table['tables'][$_var]['cols'][$y]['json'] = 'true';

    $y++;
    $new_table['tables'][$_var]['cols'][$y]['title'] = 'feedback_message';
    $new_table['tables'][$_var]['cols'][$y]['label'] = 'feedback_message';
    $new_table['tables'][$_var]['cols'][$y]['type'] = 'text';
    $new_table['tables'][$_var]['cols'][$y]['json'] = 'true';

    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/tables.' . $postdata['prefix'] . '.json', json_encode($new_table));


    $new_form = null;
    $new_form['forms'][$_var]['prefix'] = $_var;
    $new_form['forms'][$_var]['title'] = $_var;
    $new_form['forms'][$_var]['select'] = $_var;
    $new_form['forms'][$_var]['table'] = $_var;
    $new_form['forms'][$_var]['layout'] = 'list';
    $new_form['forms'][$_var]['max'] = '4';
    $new_form['forms'][$_var]['style'] = 'stacked';
    $new_form['forms'][$_var]['method'] = 'post';
    $new_form['forms'][$_var]['action'] = $postdata['url_action'];
    $new_form['forms'][$_var]['msg_error'] = 'Please! complete the form provided.';
    $new_form['forms'][$_var]['msg_ok'] = 'Your request has been sent.';
    $new_form['forms'][$_var]['version'] = 'Upd.' . date('ymdhi');

    $y = 0;
    $new_form['forms'][$_var]['input'][$y]['label'] = $postdata['label_email'];
    $new_form['forms'][$_var]['input'][$y]['name'] = 'feedback_email';
    $new_form['forms'][$_var]['input'][$y]['type'] = 'email';
    $new_form['forms'][$_var]['input'][$y]['placeholder'] = $postdata['placeholder_email'];

    $y++;
    $new_form['forms'][$_var]['input'][$y]['label'] = $postdata['feedback_name'];
    $new_form['forms'][$_var]['input'][$y]['name'] = 'feedback_name';
    $new_form['forms'][$_var]['input'][$y]['type'] = 'text';
    $new_form['forms'][$_var]['input'][$y]['placeholder'] = $postdata['feedback_name'];

    $y++;
    $new_form['forms'][$_var]['input'][$y]['label'] = $postdata['label_message'];
    $new_form['forms'][$_var]['input'][$y]['name'] = 'feedback_message';
    $new_form['forms'][$_var]['input'][$y]['type'] = 'textarea';
    $new_form['forms'][$_var]['input'][$y]['placeholder'] = $postdata['placeholder_message'];

    $y++;
    $new_form['forms'][$_var]['input'][$y]['label'] = $postdata['label_button'];
    $new_form['forms'][$_var]['input'][$y]['name'] = 'feedback_submit';
    $new_form['forms'][$_var]['input'][$y]['type'] = 'button';
    $new_form['forms'][$_var]['input'][$y]['placeholder'] = $postdata['label_button'];

    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/forms.' . $postdata['prefix'] . '.json', json_encode($new_form));


    buildIonic($file_name);
    header('Location: ./?page=x-page-builder&prefix=email_us_with_php_mailer&target=' . $postdata['prefix']);
    die();
}


$raw_data = array();
if (file_exists($pagebuilder_file))
{
    $get_raw_data = json_decode(file_get_contents($pagebuilder_file), true);
    $raw_data = $get_raw_data['page_builder']['email_us_with_php_mailer'][str2var($_GET['target'])];
}

if (!isset($raw_data['page_title']))
{
    $raw_data['page_title'] = 'Bug Report';
}

if (!isset($raw_data['url_action']))
{
    $raw_data['url_action'] = 'http://demo.ihsana.net/php-mailer.php';
}

if (!isset($raw_data['email_recipient']))
{
    $raw_data['email_recipient'] = $_SESSION['PROJECT']['app']['author_email'];
}

if (!isset($raw_data['label_button']))
{
    $raw_data['label_button'] = 'Submit';
}

if (!isset($raw_data['label_email']))
{
    $raw_data['label_email'] = 'Your Email';
}
if (!isset($raw_data['placeholder_email']))
{
    $raw_data['placeholder_email'] = 'user@domain.com';
}

if (!isset($raw_data['placeholder_name']))
{
    $raw_data['placeholder_name'] = 'Jasman Jambak';
}

if (!isset($raw_data['label_name']))
{
    $raw_data['label_name'] = 'Your Name';
}

if (!isset($raw_data['label_message']))
{
    $raw_data['label_message'] = 'Your Message';
}

if (!isset($raw_data['placeholder_message']))
{
    $raw_data['placeholder_message'] = 'Write your comments';
}

if (!isset($raw_data['page_content']))
{
    $raw_data['page_content'] = '
    <h1>Report a Broken Feature</h1>
    <blockquote>
    Let us know about a broken feature, 
    Be sure to mention which page you were on and 
    what you were doing when you encountered the bug.
    </blockquote>
    ';
}

// TODO: page target
$project = new ImaProject();
$out_path = 'output/' . $file_name;
$preview_url = $out_path . '/www/#/' . $_SESSION['PROJECT']['app']['prefix'] . '/' . $_GET['target'];


$option_page[] = array('label' => '< select page >', 'value' => '');
$z = 1;
foreach ($project->get_pages() as $page)
{
    $option_page[$z] = array('label' => 'Page `' . $page['prefix'] . '`  ' . $page['builder'] . '', 'value' => $page['prefix']);
    if ($_GET['target'] == $page['prefix'])
    {
        $option_page[$z]['active'] = true;
    }
    $z++;
}

if (!isset($raw_data['page_background']))
{
    $raw_data['page_background'] = 'data/images/background/bg11.jpg';
}

$form_input .= '
<blockquote class="blockquote blockquote-warning">
<h4>How to use?</h4>
<ol>
    <li>Upload <a target="_blank" href="./?page=z-others&prefix=php-mailer.phps">php-mailer</a> to your server (Go to <code>Backend Tools</code> -&raquo; <code>Backend Tools</code> -&raquo; <code>PHP Mailer</code> )</li>
    <li>Select the target page and complete the form fields available</li>
    <li>Then click Save</li>
</ol>
</blockquote>
';

$form_input .= $bs->FormGroup('page_target', 'horizontal', 'select', 'Page Target', $option_page, 'Page will be overwritten', null, '4');


if ($_GET['target'] !== '')
{
    $form_input .= '<h4>Settings</h4>';
    $form_input .= $bs->FormGroup('page_title', 'horizontal', 'text', 'Page Title', 'Feedback', '', '', '5', $raw_data['page_title']);
    $form_input .= $bs->FormGroup('page_content', 'horizontal', 'textarea', 'Content/Greeting', '', '', '', '8', $raw_data['page_content']);
    $form_input .= $bs->FormGroup('page_background', 'horizontal', 'text', 'Background', 'Background', '', 'data-type="image-picker"', '8', $raw_data['page_background']);
    $form_input .= $bs->FormGroup('email_recipient', 'horizontal', 'text', 'Email Recipient', 'app@domain.com', '', '', '6', $raw_data['email_recipient']);
    $form_input .= $bs->FormGroup('url_action', 'horizontal', 'text', 'REST-API / PHP Mailer', 'http://ihsana.net/php-mailer.php', '', '', '6', $raw_data['url_action']);


    $form_input .= '<h4>Labels</h4>';
    $form_input .= $bs->FormGroup('label_email', 'horizontal', 'text', 'Label Email', 'Your Email', '', '', '6', $raw_data['label_email']);
    $form_input .= $bs->FormGroup('placeholder_email', 'horizontal', 'text', 'Placeholder Email', 'user@domain.com', '', '', '5', $raw_data['placeholder_email']);

    $form_input .= $bs->FormGroup('label_name', 'horizontal', 'text', 'Label Name', 'Your Name', '', '', '5', $raw_data['label_name']);
    $form_input .= $bs->FormGroup('placeholder_name', 'horizontal', 'text', 'Placeholder Name', 'Jasman', '', '', '5', $raw_data['placeholder_name']);

    $form_input .= $bs->FormGroup('label_message', 'horizontal', 'text', 'Label Message', 'Your comments', '', '', '5', $raw_data['label_message']);
    $form_input .= $bs->FormGroup('placeholder_message', 'horizontal', 'text', 'Placeholder Message', 'Write your comments', '', '', '5', $raw_data['placeholder_message']);
    $form_input .= $bs->FormGroup('label_button', 'horizontal', 'text', 'Label Button', 'Submit', '', '', '5', $raw_data['label_button']);

}

$footer .= '
<script src="./templates/default/vendor/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    tinymce.init({
        selector : "#page_content",
        force_br_newlines : false,
        force_p_newlines : false,
        forced_root_block : "",
        
    });
     $("#page_target").on("change",function(){
        window.location= "./?page=x-page-builder&prefix=email_us_with_php_mailer&target=" +  $("#page_target").val() ;
        return false;
     });
</script>
';

?>