<?php

/**
 * @author Jasman
 * @copyright 2016
 */
$require_target_page = true;
if (isset($_POST['page-builder']))
{
    $postdata['prefix'] = str2var($_GET['target']);
    $postdata['source'] = str2var($_GET['source']);
    $postdata['audio_player'] = $_POST['audio_player'];
    $json_save['page_builder']['audio_player'][$postdata['prefix']] = $postdata;
    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/page_builder.audio_player.' . $postdata['prefix'] . '.json', json_encode($json_save));

    $new_page_class = '';
    $new_page_title = $postdata['audio_player']['title'];
    $new_page_prefix = $postdata['prefix'];
    $form_markup = null;

    $form_markup .= '

 <div class="padding">
        
        <h4 class="text-center">{{ audioPlayer.Title }}</h4>
        
        <div class="range range-positive">
            <i class="">{{ audioPlayer.timeElapsed }}</i>
            <input type="range" name="volume" min="0" max="{{ audioPlayer.timeDuration }}" value="{{ audioPlayer.timeElapsed }}" >
            <i class="">{{ audioPlayer.timeElapsed }}</i>
        </div>
        
</div>
 
 
    <ion-list class="list">
      <div class="item item-input">
        <i class="icon ion-search placeholder-icon"></i>
        <input type="search" ng-model="q" placeholder="Search" aria-label="filter ' . $postdata['source'] . 's" />
      </div>
    </ion-list>
    
    <div class="list light-bg">
      <a class="item item-icon-left item-icon-right" ng-repeat="item in data_' . $postdata['source'] . 's | filter:q as results" ng-init="$last ? fireEvent() : null" ng-click="PlayThis(item)">
        <i class="icon ion-ios-musical-notes"></i>
        <span ng-bind-html="item.' . $postdata['audio_player']['column_audio_title'] . ' | to_trusted"></span>
        <i class="icon {{ item._icon }}"></i>
      </a>
    </div>
    
    <ion-list ng-if="results.length == 0" class="list">
      <div class="item"  >
        <p>No results found...!</p>
      </div>
    </ion-list>

';


    $new_page_js = '
    
if ($rootScope.audioPlayer == null)
{
	$rootScope.audioPlayer = $document[0].createElement("audio");
    $rootScope.audioPlayer.Title = "Untitled";
    $rootScope.audioPlayer.playerState = "waiting...";
    $rootScope.audioPlayer.timeElapsed = "0";
}
 

$rootScope.PlayThis = function(currentItem)
{
	$rootScope.audioPlayer.Title = currentItem.' . $postdata['audio_player']['column_audio_title'] . ';
    $rootScope.audioPlayer.Source = currentItem.' . $postdata['audio_player']['column_audio_link'] . ';
    $rootScope.audioPlayer.loadSrc = currentItem.' . $postdata['audio_player']['column_audio_link'] . ';
	$rootScope.audioPlayer.src = $sce.trustAsResourceUrl(currentItem.' . $postdata['audio_player']['column_audio_link'] . ');
	$rootScope.audioPlayer.pause();
	$rootScope.audioPlayer.play();
}

$interval(function(){
        
	$rootScope.audioPlayer.timeElapsed = parseInt($rootScope.audioPlayer.currentTime) || 0;
	$rootScope.audioPlayer.timeDuration = parseInt($rootScope.audioPlayer.duration) || 0;
   
    	var player_state = "";
    	switch ($rootScope.audioPlayer.readyState)
    	{
        	case 0:
        		player_state = "select audio...";
        		break;
        	case 1:
        		player_state = "metadata...";
        		break;
        	case 2:
        		player_state = "current audio...";
        		break;
        	case 3:
        		player_state = "found next audio..";
        		break;
        	case 4:
        		player_state = "playing " + $rootScope.audioPlayer.timeDuration + "s";
        		break;
    	}
    
    	$rootScope.audioPlayer.playerState = player_state;
        
    	if (typeof $rootScope.audioPlayer.playerVolume != "undefined")
    	{
    		$rootScope.audioPlayer.volume = parseFloat($rootScope.audioPlayer.playerVolume / 100);
    	}
    
        var newArr = [];
        angular.forEach($scope.data_' . $postdata['source'] . 's, function (item, index){
              if(item.' . $postdata['audio_player']['column_audio_link'] . ' == $rootScope.audioPlayer.Source){
                  item._playing = true;
                  item._icon = "ion-radio-waves";            
              }else{
                  item._playing = false;
                  item._icon = "ion-null";   
              } 
              newArr.push(item);
        });
        
        $scope.' . $postdata['source'] . 's = newArr;
    
   
    
}, 1000);

    ';
    $new_page_content = $form_markup;
    $new_page_css = null;
    $new_page_css = '
 
#playbar {
    position: absolute;
    bottom: 0px !important;
    height: 50px !important;
    z-index: 10;
    display: table;
    table-layout: fixed; 
    width: 100%;  
    background-color: #000 !important;
    color: #fff !important;
}
       
    ';
    $after_ionicview = null;
    //$after_ionicview = '<div id="playbar">Test</div>';

    create_page($new_page_class, $new_page_title, $new_page_prefix, $new_page_content, $new_page_css, $new_page_js, false, 'ion-email', false, false, false, false, true, false, false, '', $after_ionicview);
}
$project = new ImaProject();

$option_audio_title_s[0] = $option_audio_link_s[0] = $option_audio_cover_s[0] = $option_category_s[0] = $option_author_s[0] = $option_author_url_s[0] = array('label' => '< select column >', 'value' => 'none');

// TODO: if table source
if (($_GET['source'] != '') && ($_GET['target'] != ''))
{
    $pagebuilder_file = 'projects/' . $_SESSION['FILE_NAME'] . '/page_builder.audio_player.' . str2var($_GET['target']) . '.json';
    $raw_data = array();
    if (file_exists($pagebuilder_file))
    {
        $get_raw_data = json_decode(file_get_contents($pagebuilder_file), true);
        $raw_data = $get_raw_data['page_builder']['audio_player'][str2var($_GET['target'])];
    }
    if (!isset($raw_data['audio_player']['title']))
    {
        $raw_data['audio_player']['title'] = 'Audio Player';
    }


    $table_source = str2var($_GET['source']);
    $z = 1;
    foreach ($project->get_columns($table_source) as $column)
    {
        $option_audio_title_s[$z] = array('label' => 'Column `' . $column['value'] . '`', 'value' => $column['value']);
        $option_audio_link_s[$z] = array('label' => 'Column `' . $column['value'] . '`', 'value' => $column['value']);
        $option_audio_cover_s[$z] = array('label' => 'Column `' . $column['value'] . '`', 'value' => $column['value']);

        $option_category_s[$z] = array('label' => 'Column `' . $column['value'] . '`', 'value' => $column['value']);
        $option_author_s[$z] = array('label' => 'Column `' . $column['value'] . '`', 'value' => $column['value']);
        $option_author_url_s[$z] = array('label' => 'Column `' . $column['value'] . '`', 'value' => $column['value']);

        $z++;
    }

    $option_audio_title = $option_audio_title_s;
    $option_audio_link = $option_audio_link_s;
    $option_audio_cover = $option_audio_cover_s;

    if (isset($raw_data['audio_player']['column_audio_title']))
    {
        $z = 0;
        foreach ($option_audio_title_s as $option_audio_title_)
        {
            if ($option_audio_title_['value'] == $raw_data['audio_player']['column_audio_title'])
            {
                $option_audio_title[$z]['active'] = true;
            }
            $z++;
        }
    }


    if (isset($raw_data['audio_player']['column_audio_link']))
    {
        $z = 0;
        foreach ($option_audio_link_s as $option_audio_link_)
        {
            if ($option_audio_link_['value'] == $raw_data['audio_player']['column_audio_link'])
            {
                $option_audio_link[$z]['active'] = true;
            }
            $z++;
        }
    }


    if (isset($raw_data['audio_player']['column_audio_cover']))
    {
        $z = 0;
        foreach ($option_audio_cover_s as $option_audio_cover_)
        {
            if ($option_audio_cover_['value'] == $raw_data['audio_player']['column_audio_cover'])
            {
                $option_audio_cover[$z]['active'] = true;
            }
            $z++;
        }
    }


    $option_category = $option_category_s;
    if (isset($raw_data['audio_player']['column_category']))
    {
        $z = 0;
        foreach ($option_category_s as $option_category_)
        {
            if ($option_category_['value'] == $raw_data['audio_player']['column_category'])
            {
                $option_category[$z]['active'] = true;
            }
            $z++;
        }
    }

    $option_author = $option_author_s;
    if (isset($raw_data['audio_player']['column_author']))
    {
        $z = 0;
        foreach ($option_author_s as $option_author_)
        {
            if ($option_author_['value'] == $raw_data['audio_player']['column_author'])
            {
                $option_author[$z]['active'] = true;
            }
            $z++;
        }
    }
    $option_author_url = $option_author_url_s;
    if (isset($raw_data['audio_player']['column_author_url']))
    {
        $z = 0;
        foreach ($option_author_url_s as $option_author_url_)
        {
            if ($option_author_url_['value'] == $raw_data['audio_player']['column_author_url'])
            {
                $option_author_url[$z]['active'] = true;
            }
            $z++;
        }
    }

    //$option_table[] = array('label' => '< select table >', 'value' => '');
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
    $form_input .= '<input type="hidden" id="page_target" value="' . htmlentities($_GET['target']) . '" />';
    $form_input .= $bs->FormGroup('table_source', 'horizontal', 'select', 'Data Source', $option_table, 'Table source for player', null, '4');
    $form_input .= $bs->FormGroup('audio_player[title]', 'horizontal', 'text', 'Page Title', '', '', '', '4', $raw_data['audio_player']['title']);
    $form_input .= '<hr/>';
    $form_input .= '<h5>Column</h5>';
    $form_input .= $bs->FormGroup('audio_player[column_audio_title]', 'horizontal', 'select', 'Title Audio', $option_audio_title, 'Column used for title audio', null, '4');
    $form_input .= $bs->FormGroup('audio_player[column_audio_link]', 'horizontal', 'select', 'URL Audio', $option_audio_link, 'Column used for link audio', null, '4');

    //$form_input .= $bs->FormGroup('audio_player[column_audio_cover]', 'horizontal', 'select', 'Cover Audio', $option_audio_cover, 'Column used for cover', null, '4');
    //$form_input .= $bs->FormGroup('audio_player[column_category]', 'horizontal', 'select', 'Category', $option_category, 'Column used for category', null, '4');
    //$form_input .= '<hr/>';
    //$form_input .= $bs->FormGroup('audio_player[column_author]', 'horizontal', 'select', 'Author', $option_author, 'Column used for author', null, '4');
    //$form_input .= $bs->FormGroup('audio_player[column_author_url]', 'horizontal', 'select', 'Author URL', $option_author_url, 'Column used for link author', null, '4');

} else
{
    // TODO: table source


    // TODO: page target
    $option_page[] = array('label' => '< page >', 'value' => '');
    $z = 1;
    foreach ($project->get_pages() as $page)
    {

        $option_page[$z] = array('label' => 'Page `' . $page['prefix'] . '`', 'value' => $page['prefix']);
        if ($_GET['target'] == $page['prefix'])
        {
            $option_page[$z]['active'] = true;
        }
        $z++;

    }

    $form_input .= $bs->FormGroup('page_target', 'horizontal', 'select', 'Page Target', $option_page, 'Page will be overwritten', null, '4');


}

$preview_url .= $_GET['target'];

$footer .= '
<script type="text/javascript">
     $("#table_source,#page_target").on("change",function(){
        window.location= "./?page=x-page-builder&prefix=page_audio_player&source=" + $("#table_source").val() + "&target=" +  $("#page_target").val() ;
        return false;
     });
</script>
';

?>