<?php


$require_target_page = true;
if (isset($_POST['page-builder']))
{
    if ($_GET['target'] != '')
    {
        if (isset($_POST['page_target']))
        {
            $_GET['target'] = $_POST['page_target'];
        }

        $postdata['prefix'] = str2var($_GET['target']);
        $postdata['page_title'] = $_POST['page_title'];
        $postdata['radio_name'] = $_POST['radio_name'];
        $postdata['radio_slogan'] = $_POST['radio_slogan'];
        $postdata['radio_url'] = $_POST['radio_url'];
        $postdata['radio_bg'] = $_POST['radio_bg'];

        $json_save['page_builder']['online_radio_player'][$postdata['prefix']] = $postdata;
        file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/page_builder.online_radio_player.' . $postdata['prefix'] . '.json', json_encode($json_save));

        $new_page_content = '
<div class="audioplayer-box">

	<div class="audioplayer-card text-center">
    
        <div ng-if="' . ($postdata['prefix']) . '_toggle_state" class="audioplayer-logo">
            <ion-spinner ng-if="state==0" class="audioplayer-spinner" icon="dots"></ion-spinner>
            <ion-spinner ng-if="state==1" class="audioplayer-spinner" icon="ios-small"></ion-spinner>
            <ion-spinner ng-if="state==2" class="audioplayer-spinner" icon="ios"></ion-spinner>
            <ion-spinner ng-if="state==3" class="audioplayer-spinner" icon="ios"></ion-spinner>
            <ion-spinner ng-if="state==4" class="audioplayer-spinner" icon="ripple"></ion-spinner>
        </div>
      
        <div ng-if="!' . ($postdata['prefix']) . '_toggle_state" class="audioplayer-logo">
            <div class="audioplayer-spinner">
                <i class="ion-volume-mute"></i>
            </div>
        </div>
      
        <div class="audioplayer-title">
        	<h3>' . $postdata['radio_name'] . '</h3>     
            <p>' . $postdata['radio_slogan'] . '</p>   
        </div>
    		
        <div class="text-center radio-button">
    		<button class="button button-block button-assertive" ng-click="' . ($postdata['prefix']) . 'TogglePlay()">
              <i ng-if="' . ($postdata['prefix']) . '_toggle_state" class="icon ion-play"></i>
              <i ng-if="!' . ($postdata['prefix']) . '_toggle_state" class="icon ion-pause"></i>
    		</button>
            <span class="calm">{{ stateText }}</span>
    	</div>
        
	</div>
</div>
';

        $new_page_js = '
        
// TODO: radio ' . $postdata['radio_url'] . '

$rootScope.' . ($postdata['prefix']) . 'RadioURL = "' . $postdata['radio_url'] . '";
$rootScope.' . ($postdata['prefix']) . '_toggle_state = false;

if (' . ($postdata['prefix']) . 'AudioPlayer == null) {
	var ' . ($postdata['prefix']) . 'AudioPlayer = $document[0].createElement("audio");
	' . ($postdata['prefix']) . 'AudioPlayer.src = $sce.trustAsResourceUrl($rootScope.' . ($postdata['prefix']) . 'RadioURL);
	try {
		' . ($postdata['prefix']) . 'AudioPlayer.play();
		$rootScope.' . ($postdata['prefix']) . '_toggle_state = true;
	} catch (e) {
		$rootScope.' . ($postdata['prefix']) . '_toggle_state = false;
        //console.log(e);
	}
}

$rootScope.' . ($postdata['prefix']) . 'TogglePlay = function() {
	if ($rootScope.' . ($postdata['prefix']) . '_toggle_state == false) {
		try {
			' . ($postdata['prefix']) . 'AudioPlayer.play();
			$rootScope.' . ($postdata['prefix']) . '_toggle_state = true;
		} catch (e) {
			$rootScope.' . ($postdata['prefix']) . '_toggle_state = false;
            //console.log(e);
		}
	} else {
		$rootScope.' . ($postdata['prefix']) . '_toggle_state = false;
		' . ($postdata['prefix']) . 'AudioPlayer.pause();
	}
}


$interval(function() {
	$rootScope.state = ' . ($postdata['prefix']) . 'AudioPlayer.readyState;
    $rootScope.stateText = "";
	switch($rootScope.state){
	   case 0:
            $rootScope.stateText = "no information";
            break;
	   case 1:
            $rootScope.stateText = "initialized";
            break;
	   case 2:
            $rootScope.stateText = "available";
            break;
	   case 3:
            $rootScope.stateText = "playback position";
            break;
	   case 4:
            $rootScope.stateText = "can be played";
            break;
	}
 
    
}, 500);


$scope.$on("$ionicView.beforeLeave", function (){
    $rootScope.' . ($postdata['prefix']) . '_toggle_state = false;
 	' . ($postdata['prefix']) . 'AudioPlayer.pause();
});

';
        $new_page_prefix = $postdata['prefix'];
        $new_page_class = '';
        $new_page_title = htmlentities($postdata['page_title']);
        $new_page_css = '
.audioplayer-box{min-height: 100%;height: auto; overflow: hidden;position: relative;}
.audioplayer-card{padding:20px;margin:20px;background-color: #000; opacity:0.9; }
.audioplayer-card .radio-button{padding:20px;}
.audioplayer-title{text-align:center;}
.audioplayer-title h3,.audioplayer-title p{color:#fff;}
.audioplayer-button{padding-top: 2px; font-size:42px !important; text-shadow: 0 0 1px #000;color: #ffffff !important;box-shadow: unset !important;} 
.audioplayer-spinner svg {width:128px;height:128px;stroke:#0f0;fill:#fff;}
.audioplayer-spinner i{color:#0df;font-size:128px;opacity: 0.9;}   
.audioplayer-logo {height: 130px;width: 100%;display: block;margin-top:12px;margin-bottom:12px;}     
    ';

        create_page($new_page_class, $new_page_title, $new_page_prefix, $new_page_content, $new_page_css, $new_page_js, true, 'ion-play', false, false, false, false, false, false, false, $postdata['radio_bg']);
    }
}

$out_path = 'output/' . $file_name;
$preview_url = $out_path . '/www/#/' . $_SESSION['PROJECT']['app']['prefix'] . '/' . $_GET['target'];

$project = new ImaProject();
$option_page[] = array('label' => '< page >', 'value' => '');
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

$pagebuilder_file = 'projects/' . $_SESSION['FILE_NAME'] . '/page_builder.online_radio_player.' . str2var($_GET['target']) . '.json';
$raw_data = array();
if (file_exists($pagebuilder_file))
{
    $get_raw_data = json_decode(file_get_contents($pagebuilder_file), true);
    $raw_data = $get_raw_data['page_builder']['online_radio_player'][str2var($_GET['target'])];
}

if (!isset($raw_data['page_title']))
{
    $raw_data['page_title'] = 'Live';
}

 
if (!isset($raw_data['radio_slogan']))
{
    $raw_data['radio_slogan'] = '756 AM';
}

if (!isset($raw_data['radio_name']))
{
    $raw_data['radio_name'] = 'Radio Rodja';
}

if (!isset($raw_data['radio_url']))
{
    $raw_data['radio_url'] = 'https://idhq.radiorodja.com/;stream.mp3?_=3';
}

if (!isset($raw_data['radio_bg']))
{
    $raw_data['radio_bg'] = 'data/images/background/bg1.jpg';
}


$form_input .= $bs->FormGroup('page_target', 'horizontal', 'select', 'Page Target', $option_page, 'Page will be overwritten', null, '7');

if ($_GET['target'] != '')
{
    $form_input .= $bs->FormGroup('page_title', 'horizontal', 'text', 'Page Title', 'Live Streaming', 'The title for the page', '', '4', $raw_data['page_title']);
    $form_input .= $bs->FormGroup('radio_bg', 'horizontal', 'text', 'Background', '', 'background image for the page (640x1024 or higher)', 'data-type="image-picker"', '8', $raw_data['radio_bg']);
    $form_input .= '<hr/>';
    $form_input .= $bs->FormGroup('radio_name', 'horizontal', 'text', 'Radio Name', 'RDI Jakarta FM', 'Write your radio name', '', '6', $raw_data['radio_name']);
    $form_input .= $bs->FormGroup('radio_slogan', 'horizontal', 'text', 'Radio Slogan', '97.1 FM', 'Write your radio slogan', '', '6', $raw_data['radio_slogan']);
    $form_input .= $bs->FormGroup('radio_url', 'horizontal', 'text', 'Radio URL', 'http://192.168.0.1:8600/;?.mp3', 'example: http://192.168.0.1:8600/;?.mp3', '', '5', $raw_data['radio_url']);
}
$footer .= '
<script type="text/javascript">
     $("#page_target").on("change",function(){
        window.location= "./?page=x-page-builder&prefix=page_radio_player&target=" +  $("#page_target").val() ;
        return false;
     });
</script>
';

?>