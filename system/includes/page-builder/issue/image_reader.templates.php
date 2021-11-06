<?php

/**
 * @author Jasman
 * @copyright 2016
 */

$bs = new jsmBootstrap();
if (!isset($_GET['source']))
{
    $_GET['source'] = '';
}
if (!isset($_GET['target']))
{
    $_GET['target'] = '';
}
$how_to_use = '
<blockquote>
<ul>
    <li>Go to <strong>Table</strong> Menu, then create a table with example column : <em>id</em>, <em>url</em></li>
    <li>Then fill form on page this.</li>
</ul>
</blockquote>
';
if (isset($_POST['page-builder']))
{

        $postdata = $_POST['image_reader'];
        $postdata['prefix'] = str2var($_GET['target']);
        $postdata['source'] = str2var($_GET['source']);
        $json_save['page_builder']['image_reader'][$postdata['prefix']] = $postdata;

        file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/page_builder.image_reader.' . $postdata['prefix'] . '.json', json_encode($json_save));

        $page_content = null;

        $page_content .= "\t\t" . '<!-- code listing -->' . "\r\n";
        $page_content .= "\t\t\t" . '<div ng-repeat="item in ' . $postdata['source'] . 's" >' . "\r\n";
        $page_content .= "\t\t\t\t" . '<ion-scroll direction="xy" zooming="true" >' . "\r\n";
        $page_content .= "\t\t\t\t\t\t" . '<img zoom-tap="true" ng-src="{{item.' . str2var($postdata['image_url'], false) . '}}" />' . "\r\n";
        $page_content .= "\t\t\t\t" . '</ion-scroll>' . "\r\n";
        $page_content .= "\t\t\t" . '</div>' . "\r\n";
        $page_content .= "\t\t" . '<!-- ./code listing -->' . "\r\n";


        $new_page_class = $postdata['prefix'];
        $new_page_title = htmlentities($postdata['title']);
        $new_page_prefix = $postdata['prefix'];
        $new_page_content = $page_content;
        $new_page_js = '';
        $new_page_css = '';
        create_page($new_page_class, $new_page_title, $new_page_prefix, $new_page_content, $new_page_css, $new_page_js, false, 'ion-ios-book', true, true);
    }

$project = new ImaProject();


$option_image_urls[0] = array('label' => '< select column >', 'value' => 'none');

// TODO: if table source
if (($_GET['source'] != '') && ($_GET['target'] != ''))
{
    $pagebuilder_file = 'projects/' . $_SESSION['FILE_NAME'] . '/page_builder.image_reader.' . str2var($_GET['target']) . '.json';
    $raw_data = array();
    if (file_exists($pagebuilder_file))
    {
        $get_raw_data = json_decode(file_get_contents($pagebuilder_file), true);
        $raw_data = $get_raw_data['page_builder']['image_reader'][str2var($_GET['target'])];
    }
    if (!isset($raw_data['title']))
    {
        $raw_data['title'] = 'Manga Reader';
    }


    $table_source = str2var($_GET['source']);
    $z = 1;
    foreach ($project->get_columns($table_source) as $column)
    {
        $option_image_urls[$z] = array('label' => 'Column `' . $column['value'] . '`', 'value' => $column['value']);
        $z++;
    }

    $_option_image_urls = $option_image_urls;
    if (isset($raw_data['image_url']))
    {
        $z = 0;
        foreach ($option_image_urls as $option_image_url)
        {
            if ($option_image_url['value'] == $raw_data['image_url'])
            {
                $_option_image_urls[$z]['active'] = true;
            }
            $z++;
        }
    }


    $form_input .= $bs->FormGroup('image_reader[title]', 'horizontal', 'text', 'Page Title', '', '', '', '4', $raw_data['title']);
    $form_input .= $bs->FormGroup('image_reader[image_url]', 'horizontal', 'select', 'Image URL', $_option_image_urls, 'Column used for image url', null, '4');

} else
{
    // TODO: table source
    $option_table[] = array('label' => '< select table >', 'value' => '');
    $z = 1;
    foreach ($project->get_tables() as $table)
    {
        $option_table[$z] = array('label' => 'Table `' . $table['title'] . '`', 'value' => $table['prefix']);
        if ($_GET['source'] == $table['prefix'])
        {
            $option_table[$z]['active'] = true;
        }
        $z++;
    }

    // TODO: page target
    $option_page[] = array('label' => '< page >', 'value' => '');
    $z = 1;
    foreach ($project->get_pages() as $page)
    {

        $option_page[$z] = array('label' => 'Page `' . $page['title'] . '`', 'value' => $page['prefix']);
        if ($_GET['target'] == $page['prefix'])
        {
            $option_page[$z]['active'] = true;
        }
        $z++;

    }

    $form_input .= $bs->FormGroup('page_target', 'horizontal', 'select', 'Page Target', $option_page, 'Page will be overwritten', null, '4');
    $form_input .= $bs->FormGroup('table_source', 'horizontal', 'select', 'Data Source', $option_table, 'Table source for quote', null, '4');

}

$preview_url .= $_GET['target'];

$footer .= '
<script type="text/javascript">
     $("#table_source,#page_target").on("change",function(){
        window.location= "./?page=x-page-builder&prefix=image_reader&source=" + $("#table_source").val() + "&target=" +  $("#page_target").val() ;
        return false;
     });
</script>
';

?>