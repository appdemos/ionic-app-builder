<?php

/**
 * @author Jasman
 * @copyright 2018
 */

$require_target_page = true;

if (isset($_SESSION['FILE_NAME']))
{
    $file_name = $_SESSION['FILE_NAME'];
} else
{
    header('Location: ./?page=dashboard&err=project');
    die();
}

if (isset($_POST['page-builder']))
{
    if (isset($_POST['page_target']))
    {
        $postdata['prefix'] = $_POST['page_target'];

    }

    $postdata['page_title'] = $_POST['coinmarketcap']['page_title'];
    $var = $postdata['prefix'];
    $json_save['page_builder']['coinmarketcap'][$var] = $postdata;
    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/page_builder.page_coinmarketcap.' . $var . '.json', json_encode($json_save));


    $require_target_page = false;
    $z = -1;

    $newTable['tables'][$var] = null;
    $newTable['tables'][$var]['db_url'] = 'https://api.coinmarketcap.com/v1/ticker/';
    $newTable['tables'][$var]['db_url_single'] = '';
    $newTable['tables'][$var]['prefix'] = $var;
    $newTable['tables'][$var]['db_type'] = 'online';
    $newTable['tables'][$var]['db_var'] = '';
    $newTable['tables'][$var]['parent'] = $var;
    $newTable['tables'][$var]['title'] = $var;
    $newTable['tables'][$var]['bookmarks'] = 'none';
    $newTable['tables'][$var]['version'] = 'Upd.' . date('ymdhi');
    $newTable['tables'][$var]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $newTable['tables'][$var]['template'] = 'none';
    $newTable['tables'][$var]['template_single'] = 'none';

    $z++;
    $newTable['tables'][$var]['cols'][$z]['label'] = 'ID';
    $newTable['tables'][$var]['cols'][$z]['title'] = 'id';
    $newTable['tables'][$var]['cols'][$z]['type'] = 'id';
    $newTable['tables'][$var]['cols'][$z]['json'] = 'true';

    $z++;
    $newTable['tables'][$var]['cols'][$z]['label'] = 'name';
    $newTable['tables'][$var]['cols'][$z]['title'] = 'name';
    $newTable['tables'][$var]['cols'][$z]['type'] = 'heading-1';
    $newTable['tables'][$var]['cols'][$z]['page_list'] = 'true';
    $newTable['tables'][$var]['cols'][$z]['page_detail'] = 'true';
    $newTable['tables'][$var]['cols'][$z]['json'] = 'true';

    $z++;
    $newTable['tables'][$var]['cols'][$z]['label'] = '[txt]';
    $newTable['tables'][$var]['cols'][$z]['title'] = 'symbol';
    $newTable['tables'][$var]['cols'][$z]['type'] = 'text';
    $newTable['tables'][$var]['cols'][$z]['json'] = 'true';

    $z++;
    $newTable['tables'][$var]['cols'][$z]['label'] = '<span title="[txt]">{{ item.price_usd | currency:"$":4 }}</span>';
    $newTable['tables'][$var]['cols'][$z]['title'] = 'price_usd';
    $newTable['tables'][$var]['cols'][$z]['type'] = 'text';
    $newTable['tables'][$var]['cols'][$z]['page_list'] = 'true';
    $newTable['tables'][$var]['cols'][$z]['page_detail'] = 'true';
    $newTable['tables'][$var]['cols'][$z]['json'] = 'true';


    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/tables.' . $var . '.json', json_encode($newTable));


    $newPage = null;
    $newPage['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $newPage['page'][0]['js'] = '$ionicConfig.backButton.text("");';
    $newPage['page'][0]['title'] = $postdata['page_title'];
    $newPage['page'][0]['prefix'] = $var;
    $newPage['page'][0]['lock'] = false;
    $newPage['page'][0]['parent'] = "";
    $newPage['page'][0]['menutype'] = $_SESSION['PROJECT']['menu']['type'] . '';
    $newPage['page'][0]['menu'] = '';
    $newPage['page'][0]['query'] = null;
    $newPage['page'][0]['query_value'] = '';
    $newPage['page'][0]['db_url_dinamic'] = false;
    $newPage['page'][0]['for'] = 'table-list';
    $newPage['page'][0]['last_edit_by'] = 'table (ticker)';
    $newPage['page'][0]['css'] = null;
    $newPage['page'][0]['css'] .= '.img32{width:32px !important;height:32px !important;}';
    $newPage['page'][0]['css'] .= '.img16{width:16px !important;height:16px !important;}';
    $newPage['page'][0]['css'] .= '.bg-white{background: #fff !important;}';

    $newPage['page'][0]['cache'] = 'false';
    $newPage['page'][0]['content'] = '
<ion-refresher pulling-text="{{ \'Pull to refresh...\' | translate }}"  on-refresh="doRefresh()"></ion-refresher>
			
<ion-list class="list" >
	<div class="item item-input">
		<i class="icon ion-search placeholder-icon"></i>
		<input type="search" ng-model="filter_' . $var . 's" placeholder="{{ \'Search\' | translate }}" aria-label="filter ' . $var . 's" />
	</div>
</ion-list>
         
<div class="list animate-none">
	<a class="item item-icon-left ink-colorful item-icon-right bg-white" ng-repeat="item in ' . $var . 's | filter:filter_' . $var . 's as results" ng-init="$last ? fireEvent() : null" ng-href="#/' . $file_name . '/' . $var . '_singles/{{ item.id }}" >
          <i class="icon ion-images positive icon-{{ item.symbol | lowercase }}"></i>
          <strong ng-bind-html="item.name | to_trusted"></strong><br/>
          <span class="item-text-wrap" ng-if="item.price_usd" title="{{item.price_usd}}">{{ item.price_usd | currency:"$":6 }}</span>
          <span class="icon assertive bg-white">{{ item.percent_change_24h }}%</span>  
	</a>
</div>        

<ion-list class="list">
	<ion-infinite-scroll ng-if="!noMoreItemsAvailable" on-infinite="onInfinite()" distance="5px" ng-if="hasMoreData"></ion-infinite-scroll>
</ion-list>
      
<ion-list class="list">
	<div class="item" ng-if="results.length == 0" >
		<p>{{ \'No results found...!\' | translate }}</p>
	</div>
</ion-list>
                    
    ';


    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.' . $var . '.json', json_encode($newPage));

    $newPage = null;
    $newPage['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $newPage['page'][0]['js'] = '$ionicConfig.backButton.text("");';
    $newPage['page'][0]['title'] = '{{ ' . $var . '.name }}';
    $newPage['page'][0]['prefix'] = $var . '_singles';
    $newPage['page'][0]['lock'] = false;
    $newPage['page'][0]['parent'] = $var;
    $newPage['page'][0]['menutype'] = 'sub-' . $_SESSION['PROJECT']['menu']['type'] . '';
    $newPage['page'][0]['menu'] = $file_name;
    $newPage['page'][0]['query'][0] = 'id';
    $newPage['page'][0]['query_value'] = '';
    $newPage['page'][0]['db_url_dinamic'] = false;
    $newPage['page'][0]['for'] = 'table-item';
    $newPage['page'][0]['last_edit_by'] = 'table (ticker)';
    $newPage['page'][0]['css'] = null;
    $newPage['page'][0]['css'] .= '.coins-wrapper{background:#fff;opacity:0.9;color:#000;margin: 0;}';
    $newPage['page'][0]['css'] .= '.coins-wrapper h3{background:#fff;opacity:0.9;color:#000;margin: 0;}';
    $newPage['page'][0]['css'] .= '.info-wrapper{background:#fff;opacity:0.7;color:#000;margin: 0;}';
    $newPage['page'][0]['cache'] = 'false';
    $newPage['page'][0]['img_bg'] = 'data/images/background/bg0.jpg';

    $newPage['page'][0]['content'] = '
    
<div class="padding">
    
    <div class="coins-wrapper text-center padding">
        <i class="icon ion-images positive icon-{{  ' . $var . '.symbol | lowercase }}"></i>
        <h3>{{ ' . $var . '.name }}</h3>
        <span class="badge badge-assertive" ng-if="' . $var . '.price_usd" title="{{' . $var . '.price_usd}}">
            {{ ' . $var . '.price_usd | currency:"USD ":6 }}
       </span>
       <br/>
        <span class="calm" ng-if="' . $var . '.price_btc" title="{{' . $var . '.price_usd}}">
                {{ ' . $var . '.price_btc | currency:"BTC ":6 }}
        </span>
    
    </div>
         
    <div class="info-wrapper padding">  
      
        <div class="row">
            	<div class="col-33 text-center padding">
                    1h<br/><span class="badge badge-positive" >{{ ' . $var . '.percent_change_1h }}%</span>
                </div>
            
            	<div class="col-33 text-center padding">
                    24h<br/><span class="badge badge-calm" >{{ ' . $var . '.percent_change_24h }}%</span>
                </div>
                
            	<div class="col-33 text-center padding">
                    7d<br/><span class="badge badge-balanced" >{{ ' . $var . '.percent_change_7d }}%</span><strong></strong>
                </div>
        </div>
        
        <div class="text-center">
            <small>Last updated: {{ ' . $var . '.last_updated | date:\'hh:mm:ss Z\' }}</small>
        </div>
        
    </div>
    
    
</div>
    
    
    
    ';

    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.' . $var . '_singles.json', json_encode($newPage));


    $newScripts = null;
    $newScripts['scripts']['src'][0]['url'] = 'data/css/coins.min.css';
    $newScripts['scripts']['src'][0]['type'] = 'css';
    $newScripts['scripts']['dependency'] = '';

    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/scripts.json', json_encode($newScripts));

    $zip = new ZipArchive;
    if ($zip->open('system/includes/page-builder/page_coinmarketcap/coins.imz') === true)
    {
        $zip->extractTo('output/' . $file_name . '/www/data/');
        $zip->close();
    }


    buildIonic($file_name);
    header('Location: ./?page=x-page-builder&prefix=page_coinmarketcap&target=' . $var);
}

$var = str2var($_GET['target']);
$pagebuilder_file = 'projects/' . $_SESSION['FILE_NAME'] . '/page_builder.page_coinmarketcap.' . $var . '.json';


$raw_data = array();
if (file_exists($pagebuilder_file))
{
    $_raw_data = json_decode(file_get_contents($pagebuilder_file), true);
    $raw_data = $_raw_data['page_builder']['coinmarketcap'][$var];
}


if (!isset($raw_data['page_title']))
{
    $raw_data['page_title'] = 'Ticker Coint Market Cap';
}

// TODO: page target
$project = new ImaProject();
$out_path = 'output/' . $file_name;
$preview_url = $out_path . '/www/#/' . $_SESSION['PROJECT']['app']['prefix'] . '/' . $var;


$option_page[] = array('label' => '< select page >', 'value' => '');
$z = 1;
foreach ($project->get_pages() as $page)
{
    $option_page[$z] = array('label' => 'Page `' . $page['prefix'] . '` ' . $page['builder'] . '', 'value' => $page['prefix']);
    if ($_GET['target'] == $page['prefix'])
    {
        $option_page[$z]['active'] = true;
    }
    $z++;
}


$form_input .= '<hr/>';

$form_input .= $bs->FormGroup('page_target', 'horizontal', 'select', 'Page Target', $option_page, 'Page will be overwritten', null, '4');
if ($_GET['target'] != '')
{
    $form_input .= '<h4>Settings</h4>';
    $form_input .= $bs->FormGroup('coinmarketcap[page_title]', 'horizontal', 'text', 'Page Title', '', '', null, '7', $raw_data['page_title']);
}

$footer .= '
<script type="text/javascript">
     $("#page_target").on("change",function(){
        window.location= "./?page=x-page-builder&prefix=page_coinmarketcap&target=" +  $("#page_target").val() ;
        return false;
     });
</script>
';

?>