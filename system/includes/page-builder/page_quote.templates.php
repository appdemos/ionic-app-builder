<?php

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2016
 * @license Commercial License
 * 
 * @package Ionic App Builder
 */
if(isset($_SESSION['FILE_NAME'])) {
    $file_name = $_SESSION['FILE_NAME'];
} else {
    header('Location: ./?page=dashboard&err=project');
    die();
}
$require_target_page = true;

$bs = new jsmBootstrap();
if(!isset($_GET['source'])) {
    $_GET['source'] = '';
}
if(!isset($_GET['target'])) {
    $_GET['target'] = '';
}
$background = 'data/images/background/bg3.jpg';
$background = '';
$how_to_use = '
<blockquote class="blockquote blockquote-warning">
<h4>How to use?</h4>
<ul>
    <li>Go to <a target="_blank" href="./?page=tables">(IMAB) Table</a> Menu, then create a table with example column : <em>id</em>, <em>title</em>, <em>quote</em> and <em>source</em></li>
    <li>Make sure the table successfully retrieves data from your backend, use <code>Coding/PageBuilder</code> as <code>Template for Data Listing</code> option.</li>
    <li>Then fill form on page this (Extra Menus -&raquo; (IMAB) Page Builder -&raquo; Page Quote) and click <code>Save</code>.</li>
</ul>
</blockquote>
';
if(isset($_POST['page-builder'])) {

    $postdata = $_POST['quote'];
    $postdata['prefix'] = str2var($_GET['target']);
    $postdata['source'] = str2var($_GET['source']);
    $json_save['page_builder']['page_quote'][$postdata['prefix']] = $postdata;
    file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/page_builder.page_quote.'.$postdata['prefix'].'.json',json_encode($json_save));

    $pull_for_refresh = 'pull for refresh';
    $search = 'Filter';

    $author = null;
    if($postdata['column_author'] != 'none') {
        $author = '<footer>{{ item.'.$postdata['column_author'].' }}</footer>';
    }
    $category = null;
    if($postdata['column_category'] != 'none') {
        $category = '<div class="item item-divider">{{ item.'.$postdata['column_category'].' }}</div>';
    }

    // TODO: + page -+- Quote

    $page_content = '
            <ion-refresher pulling-text="'.$pull_for_refresh.'"  on-refresh="doRefresh()"></ion-refresher>   
 			
             <ion-list class="card list">
				<div class="item item-input">
					<i class="icon ion-search placeholder-icon"></i>
					<input type="search" ng-model="q" placeholder="'.$search.'" aria-label="filter quotes" />
				</div>
			</ion-list>
            
            <div class="list">
                <div class="card" ng-repeat="item in '.$postdata['source'].'s | filter:q as results" ng-init="$last ? fireEvent() : null" >
                	 '.$category.'
                    <div class="item item-text-wrap">
                            <blockquote>
                                <p ng-bind-html="item.'.str2var($postdata['column_quote'],false).' | to_trusted"></p>
                                '.$author.'
                            </blockquote>
                            <button class="button button-small button-calm" ng-click="addToDbVirtual(item);"><i class="icon ion-ios-star"></i> Bookmark</button>
                    </div>
                    
                    <div class="item item-text-wrap">   
                        <div class="button-bar">
                        <button class="button button-small button-energized-900 ink icon ion-android-textsms" run-app-sms="true" phone="12345" message="{{item.'.str2var($postdata['column_quote'],false).'  }}"> SMS</button> 
                        <button class="button button-small button-calm-900 ink icon ion-social-twitter" run-app-twitter="true" message="{{item.'.str2var($postdata['column_quote'],false).'  }}">Twitter</button> 
                       
                        <button class="button button-small button-calm ink icon ion-email" run-app-email="true" email="username@domain" subject="Quote" message="{{item.'.str2var($postdata['column_quote'],false).'  }}"> Email</button> 
                        <button class="button button-small button-balanced-900 ink icon ion-social-whatsapp" run-app-whatsapp="true" message="{{item.'.str2var($postdata['column_quote'],false).'  }}"> WA</button> 
                        <button class="button button-small button-stable balanced ink icon ion-ios-chatbubble" run-app-line="true" message="{{item.'.str2var($postdata['column_quote'],false).'  }}"> Line</button> 
                        </div>
                    </div>  
                                        
                </div>
            </div>  
            
            <ion-list class="list">
                <ion-infinite-scroll ng-if="!noMoreItemsAvailable" on-infinite="onInfinite()" distance="5px" ng-if="hasMoreData"></ion-infinite-scroll>
            </ion-list>
            
            <ion-list class="list">
				<div class="item" ng-if="results.length == 0" >
					<p>'.$not_found.'</p>
				</div>
			</ion-list>
            	
    ';

    $_page = json_decode(file_get_contents('projects/'.$file_name.'/page.'.str2var($_GET['target']).'.json'),true);


    $_page['page'][0]['prefix'] = str2var($_GET['target']);
    $_page['page'][0]['img_bg'] = $background;
    $_page['page'][0]['lock'] = true;
    $_page['page'][0]['scroll'] = true;
    $_page['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $_page['page'][0]['content'] = $page_content;
    $_page['page'][0]['for'] = 'page_builder';
    $_page['page'][0]['title'] = htmlentities($postdata['title']);
    $_page['page'][0]['menu'] = $file_name;
    $_page['page'][0]['parent'] = $_SESSION['PROJECT']['menu']['type'];
    //$_page['page'][0]['menutype'] = $_SESSION['PROJECT']['menu']['type'];
    $_page['page'][0]['js'] = '$ionicConfig.backButton.text("");';


    file_put_contents(JSM_PATH.'/projects/'.$file_name.'/page.'.str2var($_GET['target']).'.json',json_encode($_page));


    $_page = null;
    $page_content = '


<ion-list class="list" ng-if="'.$postdata['source'].'_bookmark.length != 0">
       <div class="card" ng-repeat="item in '.$postdata['source'].'_bookmark"  >
        	 '.$category.'
            <div class="item item-text-wrap">
                    <blockquote>
                        <p ng-bind-html="item.'.str2var($postdata['column_quote'],false).' | to_trusted"></p>
                        '.$author.'
                    </blockquote>
            </div>
            
            <div class="item item-text-wrap">   
                <div class="button-bar">
                <button class="button button-small button-energized-900 ink icon ion-android-textsms" run-app-sms="true" phone="12345" message="{{item.'.str2var($postdata['column_quote'],false).'  }}"> SMS</button> 
                <button class="button button-small button-calm-900 ink icon ion-social-twitter" run-app-twitter="true" message="{{item.'.str2var($postdata['column_quote'],false).'  }}">Twitter</button> 
               
                <button class="button button-small button-calm ink icon ion-email" run-app-email="true" email="username@domain" subject="Quote" message="{{item.'.str2var($postdata['column_quote'],false).'  }}"> Email</button> 
                <button class="button button-small button-balanced-900 ink icon ion-social-whatsapp" run-app-whatsapp="true" message="{{item.'.str2var($postdata['column_quote'],false).'  }}"> WA</button> 
                <button class="button button-small button-stable balanced ink icon ion-ios-chatbubble" run-app-line="true" message="{{item.'.str2var($postdata['column_quote'],false).'  }}"> Line</button> 
                </div>
            </div>  
                                
        </div>
        
        <ion-item class="item item-button">
    	   <button class="button button-block button-calm" ng-click="clearDbVirtual'.ucwords($postdata['source']).'();">
                <i class="icon ion-ios-refresh-outline"></i> Clear
           </button>
    	</ion-item>

</ion-list>


<!-- no bookmark -->
<div class="'.$postdata['source'].'_bookmark padding text-center" ng-if="'.$postdata['source'].'_bookmark.length == 0">
	<i class="icon ion-ios-bookmarks-outline"></i>
	<p>There are no items</p>
</div>
<!-- no bookmark -->    
    
    ';
    $_page['page'][0]['prefix'] = str2var($postdata['source']).'_bookmark';
    $_page['page'][0]['img_bg'] = $background;
    $_page['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $_page['page'][0]['lock'] = true;
    $_page['page'][0]['content'] = $page_content;
    $_page['page'][0]['for'] = 'table-bookmarks';
    $_page['page'][0]['title'] = 'Bookmark of '.htmlentities($postdata['title']);
    $_page['page'][0]['menu'] = $file_name;
    $_page['page'][0]['parent'] = $_SESSION['PROJECT']['menu']['type'];
    $_page['page'][0]['menutype'] = $_SESSION['PROJECT']['menu']['type'];
    $_page['page'][0]['js'] = '$ionicConfig.backButton.text("");';
    $_page['page'][0]['css'] = '
.'.str2var($postdata['source']).'_bookmark{margin-top: 50%;}
.'.str2var($postdata['source']).'_bookmark .icon:before{font-size:72px;font-weight: 600;}
                                ';

    file_put_contents(JSM_PATH.'/projects/'.$file_name.'/page.'.str2var($postdata['source']).'_bookmark'.'.json',json_encode($_page));


    buildIonic($file_name);
    header('Location: ./?page=x-page-builder&prefix=page_quote&target='.str2var($_GET['target']).'&source='.str2var($_GET['source']));
    die();

}
$project = new ImaProject();


$option_quote_s[0] = $option_category_s[0] = $option_author_s[0] = array('label' => '< select column >','value' => 'none');

// TODO: if table source
if(($_GET['source'] != '') && ($_GET['target'] != '')) {
    $pagebuilder_file = 'projects/'.$_SESSION['FILE_NAME'].'/page_builder.page_quote.'.str2var($_GET['target']).'.json';
    $raw_data = array();
    if(file_exists($pagebuilder_file)) {
        $get_raw_data = json_decode(file_get_contents($pagebuilder_file),true);
        $raw_data = $get_raw_data['page_builder']['page_quote'][str2var($_GET['target'])];
    }
    if(!isset($raw_data['title'])) {
        $raw_data['title'] = 'Quote';
    }


    $table_source = str2var($_GET['source']);
    $z = 1;
    foreach($project->get_columns($table_source) as $column) {
        $option_quote_s[$z] = array('label' => 'Column `'.$column['value'].'`','value' => $column['value']);
        $option_category_s[$z] = array('label' => 'Column `'.$column['value'].'`','value' => $column['value']);
        $option_author_s[$z] = array('label' => 'Column `'.$column['value'].'`','value' => $column['value']);

        $z++;
    }

    $option_quote = $option_quote_s;
    if(isset($raw_data['column_quote'])) {
        $z = 0;
        foreach($option_quote_s as $option_quote_) {
            if($option_quote_['value'] == $raw_data['column_quote']) {
                $option_quote[$z]['active'] = true;
            }
            $z++;
        }
    }

    $option_category = $option_category_s;
    if(isset($raw_data['column_category'])) {
        $z = 0;
        foreach($option_category_s as $option_category_) {
            if($option_category_['value'] == $raw_data['column_category']) {
                $option_category[$z]['active'] = true;
            }
            $z++;
        }
    }

    $option_author = $option_author_s;
    if(isset($raw_data['column_author'])) {
        $z = 0;
        foreach($option_author_s as $option_author_) {
            if($option_author_['value'] == $raw_data['column_author']) {
                $option_author[$z]['active'] = true;
            }
            $z++;
        }
    }


    $form_input .= $bs->FormGroup('quote[title]','horizontal','text','Page Title','','','','4',$raw_data['title']);
    $form_input .= '<hr/>';
    $form_input .= '<h5>Column</h5>';
    $form_input .= $bs->FormGroup('quote[column_category]','horizontal','select','Category',$option_category,'Column used for category',null,'4');
    $form_input .= $bs->FormGroup('quote[column_quote]','horizontal','select','Quotes',$option_quote,'Column used for quotes',null,'4');
    $form_input .= $bs->FormGroup('quote[column_author]','horizontal','select','Author',$option_author,'Column used for author',null,'4');

} else {
    // TODO: table source
    $option_table[] = array('label' => '< select table >','value' => '');
    $z = 1;
    foreach($project->get_tables() as $table) {
        $option_table[$z] = array('label' => 'Table `'.$table['title'].'`','value' => $table['prefix']);
        if($_GET['source'] == $table['prefix']) {
            $option_table[$z]['active'] = true;
        }
        $z++;
    }

    // TODO: page target
    $option_page[] = array('label' => '< select page >','value' => '');
    $z = 1;
    foreach($project->get_pages() as $page) {

        $option_page[$z] = array('label' => 'Page `'.$page['prefix'].'` '.$page['builder'].'','value' => $page['prefix']);
        if($_GET['target'] == $page['prefix']) {
            $option_page[$z]['active'] = true;
        }
        $z++;

    }

    $form_input .= $bs->FormGroup('page_target','horizontal','select','Page Target',$option_page,'Page will be overwritten',null,'4');
    $form_input .= $bs->FormGroup('table_source','horizontal','select','Data Source',$option_table,'Table source for quote',null,'4');

}

$preview_url .= $_GET['target'];

$footer .= '
<script type="text/javascript">
     $("#table_source,#page_target").on("change",function(){
        window.location= "./?page=x-page-builder&prefix=page_quote&source=" + $("#table_source").val() + "&target=" +  $("#page_target").val() ;
        return false;
     });
</script>
';

?>