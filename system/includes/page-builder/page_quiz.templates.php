<?php

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2016
 * @license Commercial License
 * 
 * @package Ionic App Builder
 */

$require_target_page = true;

$bs = new jsmBootstrap();
$how_to_use = '
<blockquote class="blockquote blockquote-warning">
<h4>How to use?</h4>
<ul>
    <li>
<p>Go to <a target="_blank" href="./?page=tables">(IMAB) Table</a> Menu, then create a table with example column : <code>id</code>, <code>question</code>, <code>answer</code>, <code>option_a</code>, <code>option_b</code>, <code>option_c</code></p>
<p>Example Data</p>
<table class="table table-bordered">
<thead>
    <tr>
        <th>id</th>
    	<th>question</th>
    	<th>answer</th>
    	<th>option_a</th>
    	<th>option_b</th>
    	<th>option_c</th>
    </tr>
</thead>
<tbody>
<tr>
    <td>1</td>
	<td>2 + 2 ?</td>
	<td>a</td>
	<td><strong>4</strong></td>
	<td>5</td>
	<td>6</td>
</tr>
<tr>
    <td>2</td>
	<td>1 * 1 ?</td>
	<td>c</td>
	<td>2</td>
	<td>3</td>
	<td><strong>1</strong></td>
</tr>
<tr>
    <td>3</td>
	<td>4 + 2 ?</td>
	<td>c</td>
	<td>4</td>
	<td>5</td>
	<td><strong>6</strong></td>
</tr>
</tbody>
</table>
    
    </li>
    <li>Make sure the table successfully retrieves data from your backend, use <code>Coding/PageBuilder</code> as <code>Template for Data Listing</code> option.</li>
    <li>Then fill form on page this (Extra Menus -&raquo; (IMAB) Page Builder -&raquo; Page Quiz) and click <code>Save</code>.</li>
</ul>



</blockquote>
';

if(!isset($_GET['source']))
{
    $_GET['source'] = '';
}
if(!isset($_GET['target']))
{
    $_GET['target'] = '';
}

if(isset($_POST['page-builder']))
{
    $postdata = $_POST['quiz'];
    $postdata['prefix'] = str2var($_GET['target']);
    $postdata['source'] = str2var($_GET['source']);

    $json_save['page_builder']['page_quiz'][$postdata['prefix']] = $postdata;
    file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/page_builder.page_quiz.'.$postdata['prefix'].'.json',json_encode($json_save));

    $page_content = null;
    $page_content .= "\r\n";
    $page_content .= "\t\t\t".'<ion-refresher pulling-text="Pull to refresh..."  on-refresh="doRefresh()"></ion-refresher>'."\r\n";
    $page_content .= "\t\t\t".'<div class="list card">'."\r\n";
    $page_content .= "\t\t\t\t".'<div class="item item-header item-stable item-button-right">'."\r\n";
    $page_content .= "\t\t\t\t\t".''.htmlentities($postdata['title_question']).''."\r\n";
    $page_content .= "\t\t\t\t\t".'<span class="badge badge-assertive">{{ current.remaining }}</span>'."\r\n";
    $page_content .= "\t\t\t\t\t".'<button class="button button-balanced ink"  ng-click="modal_score_open($event)"><i class="icon ion-ribbon-a"></i></button>'."\r\n";
    $page_content .= "\t\t\t\t".'</div>'."\r\n";

    if(isset($postdata['column_question']))
    {
        $page_content .= "\t\t\t\t".'<div class="item item-text-wrap">'."\r\n";
        $page_content .= "\t\t\t\t\t".'<div ng-bind-html="current.'.$postdata['column_question'].' | strHTML" ></div>'."\r\n";
        $page_content .= "\t\t\t\t".'</div>'."\r\n";

    }
    //$page_content .= "\t\t\t\t\t".'<br/><pre>{{ current.'.str2var($postdata['column_answers']).' | json }}</pre>'."\r\n";
    $page_content .= "\t\t\t".'</div>'."\r\n";


    $page_content .= "\t\t\t".'<div class="card">'."\r\n";
    $page_content .= "\t\t\t\t".'<div class="item item-header item-calm text-center">'."\r\n";
    $page_content .= "\t\t\t\t\t".''.htmlentities($postdata['title_choice']).''."\r\n";
    $page_content .= "\t\t\t\t".'</div>'."\r\n";

    $page_content .= "\t\t\t\t".'<ion-list>'."\r\n";

    if(isset($postdata['column_option_a']))
    {
        $page_content .= "\t\t\t\t\t".'<ion-radio ng-model="choice" ng-value="\'a\'"><span class="item-text-wrap" ng-if="current.'.str2var($postdata['column_option_a']).'" ng-bind-html="current.'.str2var($postdata['column_option_a']).' | strHTML"></span></ion-radio>'."\r\n";
    }

    if(isset($postdata['column_option_b']))
    {
        $page_content .= "\t\t\t\t\t".'<ion-radio ng-model="choice" ng-value="\'b\'"><span class="item-text-wrap" ng-if="current.'.str2var($postdata['column_option_b']).'" ng-bind-html="current.'.str2var($postdata['column_option_b']).' | strHTML"></span></ion-radio>'."\r\n";
    }

    if(isset($postdata['column_option_c']))
    {
        if($postdata['column_option_c'] != "none")
        {
            $page_content .= "\t\t\t\t\t".'<ion-radio ng-model="choice" ng-value="\'c\'"><span class="item-text-wrap" ng-if="current.'.str2var($postdata['column_option_c']).'" ng-bind-html="current.'.str2var($postdata['column_option_c']).' | strHTML"></span></ion-radio>'."\r\n";
        }
    }
    if(isset($postdata['column_option_d']))
    {
        if($postdata['column_option_d'] != "none")
        {
            $page_content .= "\t\t\t\t\t".'<ion-radio ng-model="choice" ng-value="\'d\'"><span class="item-text-wrap" ng-if="current.'.str2var($postdata['column_option_d']).'" ng-bind-html="current.'.str2var($postdata['column_option_d']).' | strHTML"></span></ion-radio>'."\r\n";
        }
    }
    if(isset($postdata['column_option_e']))
    {
        if($postdata['column_option_e'] != "none")
        {
            $page_content .= "\t\t\t\t\t".'<ion-radio ng-model="choice" ng-value="\'e\'"><span class="item-text-wrap" ng-if="current.'.str2var($postdata['column_option_e']).'" ng-bind-html="current.'.str2var($postdata['column_option_e']).' | strHTML"></span></ion-radio>'."\r\n";
        }
    }
    $page_content .= "\t\t\t\t".'</ion-list>'."\r\n";

    $page_content .= "\t\t\t".'<div class="item noborder">'."\r\n";
    $page_content .= "\t\t\t\t".'<button ng-if="choice" class="button button-block button-assertive" ng-click="check_choose(choice)">'.htmlentities($postdata['button_next']).'</button>'."\r\n";
    $page_content .= "\t\t\t".'</div>'."\r\n";

    $page_content .= "\t\t\t".'</div>'."\r\n";

    $page_content .= "\t\t\t".'<br/><br/>'."\r\n";

    $page_content .= "\t\t\t".'<br/><br/>'."\r\n";
    $page_content .= "\t\t\t".'<script id="score.html" type="text/ng-template">'."\r\n";
    $page_content .= "\t\t\t\t".'<ion-modal-view>'."\r\n";
    $page_content .= "\t\t\t\t\t".'<ion-header-bar class="bar bar-header light bar-balanced">'."\r\n";
    $page_content .= "\t\t\t\t\t".'<div class="header-item title">'.htmlentities($postdata['title_score']).'</div>'."\r\n";
    $page_content .= "\t\t\t\t\t".'<div class="buttons buttons-right header-item"><span class="right-buttons"><button class="button button-icon button-clear ion-close ink-black" ng-click="modal_score_close()"></button></span></div>'."\r\n";
    $page_content .= "\t\t\t\t".'</ion-header-bar>'."\r\n";

    $page_content .= "\t\t\t\t\t".'<ion-content>'."\r\n";
    $page_content .= "\t\t\t\t\t\t".'<div class="card">'."\r\n";
    $page_content .= "\t\t\t\t\t\t\t".'<div class="row">'."\r\n";
    $page_content .= "\t\t\t\t\t\t\t\t".'<div class="col">'.htmlentities($postdata['text_correct']).'</div>'."\r\n";
    $page_content .= "\t\t\t\t\t\t\t\t".'<div class="col text-right">{{ current.correct }}</div>'."\r\n";
    $page_content .= "\t\t\t\t\t\t\t".'</div>'."\r\n";

    $page_content .= "\t\t\t\t\t\t\t".'<div class="row">'."\r\n";
    $page_content .= "\t\t\t\t\t\t\t\t".'<div class="col">'.htmlentities($postdata['text_wrong']).'</div>'."\r\n";
    $page_content .= "\t\t\t\t\t\t\t\t".'<div class="col text-right">{{ current.wrong }}</div>'."\r\n";
    $page_content .= "\t\t\t\t\t\t\t".'</div>'."\r\n";

    $page_content .= "\t\t\t\t\t\t\t".'<div class="row">'."\r\n";
    $page_content .= "\t\t\t\t\t\t\t\t".'<div class="col">'.htmlentities($postdata['text_total']).'</div>'."\r\n";
    $page_content .= "\t\t\t\t\t\t\t\t".'<div class="col text-right">{{ current.remaining }}</div>'."\r\n";
    $page_content .= "\t\t\t\t\t\t\t".'</div>'."\r\n";

    $page_content .= "\t\t\t\t\t\t\t".'<div class="row">'."\r\n";
    $page_content .= "\t\t\t\t\t\t\t\t".'<div class="col"><button class="button button-calm ion-ios-refresh-outline icon  icon-right" ng-click="modal_score_repeat()">'.htmlentities($postdata['button_repeat']).'</button></div>'."\r\n";
    $page_content .= "\t\t\t\t\t\t\t".'</div>'."\r\n";

    $page_content .= "\t\t\t\t\t\t".'</div>'."\r\n";

    $page_content .= "\t\t\t\t\t".'</ion-content>'."\r\n";
    $page_content .= "\t\t\t\t".'</ion-modal-view>'."\r\n";
    $page_content .= "\t\t\t".'</script>'."\r\n";

    $page_content .= "\t\t\t".'<script id="congrats.html" type="text/ng-template">'."\r\n";
    $page_content .= "\t\t\t\t".'<ion-modal-view>'."\r\n";
    $page_content .= "\t\t\t\t\t".'<ion-content>'."\r\n";
    $page_content .= "\t\t\t\t\t\t".'<div class="padding text-center">'."\r\n";
    $page_content .= "\t\t\t\t\t\t\t".'<div class="padding text-center">'."\r\n";
    $page_content .= "\t\t\t\t\t\t\t\t".'<span style="font-size:64px;" class="icon {{ current.congrats_icon }}"></span>'."\r\n";
    $page_content .= "\t\t\t\t\t\t\t\t".'<h3>{{ current.congrats_text }}</h3>'."\r\n";
        if(isset($postdata['column_answers_detail']))
    {
        if($postdata['column_answers_detail'] != "none")
        {
             $page_content .= "\t\t\t\t\t\t\t\t".'<div class="padding" style="border:2px dashed red;background:#eee"><span class="item-text-wrap" ng-if="current.'.str2var($postdata['column_answers_detail']).'" ng-bind-html="current.'.str2var($postdata['column_answers_detail']).' | strHTML"></span></div>'."\r\n";
        }
    }
    $page_content .= "\t\t\t\t\t\t\t".'</div>'."\r\n";
    $page_content .= "\t\t\t\t\t\t".'</div>'."\r\n";
    $page_content .= "\t\t\t\t\t".'</ion-content>'."\r\n";
    $page_content .= "\t\t\t\t".'</ion-modal-view>'."\r\n";
    $page_content .= "\t\t\t".'</script>'."\r\n";


    $page_content .= "\r\n";


    $new_page_class = $postdata['prefix'];
    $new_page_title = htmlentities($postdata['title']);
    $new_page_prefix = $postdata['prefix'];
    $new_page_content = $page_content;
    $new_page_js = '
 

var opt = {};    
opt["a"] = "'.$postdata['column_option_a'].'" ;
opt["b"] = "'.$postdata['column_option_b'].'" ;
opt["c"] = "'.$postdata['column_option_c'].'" ;
opt["d"] = "'.$postdata['column_option_d'].'" ;
opt["e"] = "'.$postdata['column_option_e'].'" ;

var title_of_confirm = "'.htmlentities($postdata['title_of_confirm']).'";
var content_of_confirm = "'.htmlentities($postdata['content_of_confirm']).'";  
var text_true = "'.htmlentities($postdata['text_correct']).'";
var text_false = "'.htmlentities($postdata['text_wrong']).'";  

var icon_for_true = "ion-happy-outline balanced";
var icon_for_false = "ion-sad-outline assertive";  

$scope.current_index = 0;
$scope.correct_answers = $scope.wrong_answers = 0;

$scope.update_score = function(){
    if(!data_'.$postdata['source'].'s){
        return false;
    }
    $scope.current.wrong = $scope.wrong_answers;
    $scope.current.correct = $scope.correct_answers; 
    $scope.current.remaining = ($scope.current_index + 1) + "/" + data_'.$postdata['source'].'s.length ;       
}

$scope.update_question = function(){ 
    $scope.choose = null;
    if(!data_'.$postdata['source'].'s){
       return false;
    }    
    $scope.current = data_'.$postdata['source'].'s[$scope.current_index];
    $ionicScrollDelegate.$getByHandle("top").scrollTop();
}

$scope.update_question();
$scope.update_score();

$scope.check_choose = function($choice){
    $ionicPopup.confirm({
        title: title_of_confirm,
        content: content_of_confirm
    }).then(function(respon){
        if(respon){
            if($choice === $scope.current.'.str2var($postdata['column_answers']).'){
                $scope.current.congrats_icon = icon_for_true;
                $scope.current.congrats_text = text_true;
                $scope.correct_answers++;
            }else{
                var getvar = $scope.current.'.str2var($postdata['column_answers']).' ;
                $scope.current.congrats_icon = icon_for_false;
                $scope.current.congrats_text = text_false;
                $scope.wrong_answers++;
            }        
            $scope.modal_congrats.show();
            $timeout(function(){
                if($scope.current_index == ( data_'.$postdata['source'].'s.length -1) ){
                    $scope.update_score();  
                    $scope.modal_score_open();
                }else{
                    $scope.current_index++;  
                    $scope.update_question(); 
                    $scope.update_score();                 
                }
                $scope.modal_congrats.hide();
			}, 3000);
        } 
  });    
};

/** dialog congrats **/
$ionicModal.fromTemplateUrl("congrats.html",{scope:$scope,animation:"slide-in-up"}).then(function(modal){
    $scope.modal_congrats = modal;
});

/** dialog score **/
$ionicModal.fromTemplateUrl("score.html",{scope:$scope,animation:"slide-in-up"}).then(function(modal){
    $scope.modal_score = modal;
});

$scope.modal_score_open = function(){
    $scope.modal_score.show();
}

$scope.modal_score_close = function(){
    $scope.modal_score.hide();
};

$scope.modal_score_repeat = function(){
    $scope.current_index = 0;
    $scope.correct_answers = $scope.wrong_answers = 0;
    $scope.update_question();
    $scope.update_score();
    $scope.modal_score.hide();
};

$scope.$on("$destroy", function(){
    $scope.modal_score.remove();
    $scope.modal_congrats.remove();
});

';
    $new_page_css = '';
    create_page($new_page_class,$new_page_title,$new_page_prefix,$new_page_content,$new_page_css,$new_page_js);
}
$project = new ImaProject();


$option_question_s[0] = $option_answers_s[0] = $option_option_a_s[0] = $option_option_b_s[0] = $option_option_c_s[0] = $option_option_d_s[0] = $option_option_e_s[0] = array('label' => '< select column >','value' => 'none');
$option_option_c_s[0] = $option_option_d_s[0] = $option_option_e_s[0] = $option_answers_detail_s[0] = array('label' => 'none','value' => 'none');

// TODO: if table source
if(($_GET['source'] != '') && ($_GET['target'] != ''))
{
    $pagebuilder_file = 'projects/'.$_SESSION['FILE_NAME'].'/page_builder.page_quiz.'.str2var($_GET['target']).'.json';
    $raw_data = array();
    if(file_exists($pagebuilder_file))
    {
        $get_raw_data = json_decode(file_get_contents($pagebuilder_file),true);
        $raw_data = $get_raw_data['page_builder']['page_quiz'][str2var($_GET['target'])];
    }
    if(!isset($raw_data['title']))
    {
        $raw_data['title'] = 'MyQuiz';
    }

    if(!isset($raw_data['title_of_confirm']))
    {
        $raw_data['title_of_confirm'] = 'Confirm!';
    }

    if(!isset($raw_data['content_of_confirm']))
    {
        $raw_data['content_of_confirm'] = 'Are you sure of the answer?';
    }
    if(!isset($raw_data['title_question']))
    {
        $raw_data['title_question'] = 'Question';
    }
    if(!isset($raw_data['title_choice']))
    {
        $raw_data['title_choice'] = 'Your choice';
    }
    if(!isset($raw_data['title_score']))
    {
        $raw_data['title_score'] = 'Your score';
    }
    if(!isset($raw_data['button_next']))
    {
        $raw_data['button_next'] = 'Next';
    }
    if(!isset($raw_data['button_repeat']))
    {
        $raw_data['button_repeat'] = 'Repeat';
    }
    if(!isset($raw_data['text_correct']))
    {
        $raw_data['text_correct'] = 'Correct';
    }

    if(!isset($raw_data['text_wrong']))
    {
        $raw_data['text_wrong'] = 'Wrong';
    }
    if(!isset($raw_data['text_total']))
    {
        $raw_data['text_total'] = 'Total';
    }

    $table_source = str2var($_GET['source']);
    $z = 1;
    foreach($project->get_columns($table_source) as $column)
    {
        $option_question_s[$z] = array('label' => 'Column `'.$column['value'].'`','value' => $column['value']);
        $option_answers_s[$z] = array('label' => 'Column `'.$column['value'].'`','value' => $column['value']);
        $option_option_a_s[$z] = array('label' => 'Column `'.$column['value'].'`','value' => $column['value']);
        $option_option_b_s[$z] = array('label' => 'Column `'.$column['value'].'`','value' => $column['value']);
        $option_option_c_s[$z] = array('label' => 'Column `'.$column['value'].'`','value' => $column['value']);
        $option_option_d_s[$z] = array('label' => 'Column `'.$column['value'].'`','value' => $column['value']);
        $option_option_e_s[$z] = array('label' => 'Column `'.$column['value'].'`','value' => $column['value']);

        $option_answers_detail_s[$z] = array('label' => 'Column `'.$column['value'].'`','value' => $column['value']);
        $z++;
    }

    $option_question = $option_question_s;
    if(isset($raw_data['column_question']))
    {
        $z = 0;
        foreach($option_question_s as $option_question_)
        {
            if($option_question_['value'] == $raw_data['column_question'])
            {
                $option_question[$z]['active'] = true;
            }
            $z++;
        }
    }

    $option_answers = $option_answers_s;
    if(isset($raw_data['column_answers']))
    {
        $z = 0;
        foreach($option_answers_s as $option_answers_)
        {
            if($option_answers_['value'] == $raw_data['column_answers'])
            {
                $option_answers[$z]['active'] = true;
            }
            $z++;
        }
    }

    $option_option_a = $option_option_a_s;
    if(isset($raw_data['column_option_a']))
    {
        $z = 0;
        foreach($option_option_a_s as $option_option_a_)
        {
            if($option_option_a_['value'] == $raw_data['column_option_a'])
            {
                $option_option_a[$z]['active'] = true;
            }
            $z++;
        }
    }
    $option_option_b = $option_option_b_s;
    if(isset($raw_data['column_option_b']))
    {
        $z = 0;
        foreach($option_option_b_s as $option_option_b_)
        {
            if($option_option_b_['value'] == $raw_data['column_option_b'])
            {
                $option_option_b[$z]['active'] = true;
            }
            $z++;
        }
    }

    $option_option_c = $option_option_c_s;
    if(isset($raw_data['column_option_b']))
    {
        $z = 0;
        foreach($option_option_c_s as $option_option_c_)
        {
            if($option_option_c_['value'] == $raw_data['column_option_c'])
            {
                $option_option_c[$z]['active'] = true;
            }
            $z++;
        }
    }

    $option_option_d = $option_option_d_s;
    if(isset($raw_data['column_option_d']))
    {
        $z = 0;
        foreach($option_option_d_s as $option_option_d_)
        {
            if($option_option_d_['value'] == $raw_data['column_option_d'])
            {
                $option_option_d[$z]['active'] = true;
            }
            $z++;
        }
    }

    $option_option_e = $option_option_e_s;
    if(isset($raw_data['column_option_e']))
    {
        $z = 0;
        foreach($option_option_e_s as $option_option_e_)
        {
            if($option_option_e_['value'] == $raw_data['column_option_e'])
            {
                $option_option_e[$z]['active'] = true;
            }
            $z++;
        }
    }

    $option_answers_detail = $option_answers_detail_s;
    if(isset($raw_data['column_answers_detail']))
    {
        $z = 0;
        foreach($option_answers_detail_s as $option_answers_detail_)
        {
            if($option_answers_detail_['value'] == $raw_data['column_answers_detail'])
            {
                $option_answers_detail[$z]['active'] = true;
            }
            $z++;
        }
    }


    $form_input .= $bs->FormGroup('quiz[title]','horizontal','text','Page Title','','','','4',$raw_data['title']);
    $form_input .= '<hr/>';
    $form_input .= '<h5>Question</h5>';
    $form_input .= $bs->FormGroup('quiz[column_question]','horizontal','select','Question',$option_question,'Column used for questions',null,'4');
    $form_input .= $bs->FormGroup('quiz[column_answers]','horizontal','select','Correct Answer',$option_answers,'The correct answer should be using option A, B, C, D, or E',null,'5');
    $form_input .= $bs->FormGroup('quiz[column_answers_detail]','horizontal','select','Explanation of the answer',$option_answers_detail,'Explain more detail about answers',null,'5');

    $form_input .= '<hr/>';
    $form_input .= '<h5>Option</h5>';
    $form_input .= $bs->FormGroup('quiz[column_option_a]','horizontal','select','Option A',$option_option_a,'The choice answers',null,'3');
    $form_input .= $bs->FormGroup('quiz[column_option_b]','horizontal','select','Option B',$option_option_b,'The choice answers',null,'3');
    $form_input .= $bs->FormGroup('quiz[column_option_c]','horizontal','select','Option C',$option_option_c,'The choice answers',null,'3');
    $form_input .= $bs->FormGroup('quiz[column_option_d]','horizontal','select','Option D',$option_option_d,'The choice answers',null,'3');
    $form_input .= $bs->FormGroup('quiz[column_option_e]','horizontal','select','Option E',$option_option_e,'The choice answers',null,'3');


    $form_input .= '<hr/>';
    $form_input .= '<h5>Language</h5>';
    $form_input .= $bs->FormGroup('quiz[title_question]','horizontal','text','Title Question','Question!','','','5',$raw_data['title_question']);
    $form_input .= $bs->FormGroup('quiz[title_choice]','horizontal','text','Title Choice','Your choice','','','5',$raw_data['title_choice']);
    $form_input .= $bs->FormGroup('quiz[title_score]','horizontal','text','Title Choice','Your score','','','5',$raw_data['title_score']);

    $form_input .= $bs->FormGroup('quiz[title_of_confirm]','horizontal','text','Title of confirm','Confirm!','','','4',$raw_data['title_of_confirm']);
    $form_input .= $bs->FormGroup('quiz[content_of_confirm]','horizontal','text','Content of confirm','Are you sure of the answer?','','','8',$raw_data['content_of_confirm']);
    $form_input .= $bs->FormGroup('quiz[button_next]','horizontal','text','Button Next','Next','','','8',$raw_data['button_next']);
    $form_input .= $bs->FormGroup('quiz[button_repeat]','horizontal','text','Button Repeat','Repeat','','','8',$raw_data['button_repeat']);

    $form_input .= $bs->FormGroup('quiz[text_correct]','horizontal','text','Text Correct','Correct','','','8',$raw_data['text_correct']);
    $form_input .= $bs->FormGroup('quiz[text_wrong]','horizontal','text','Text Wrong','Wrong','','','8',$raw_data['text_wrong']);
    $form_input .= $bs->FormGroup('quiz[text_total]','horizontal','text','Text Total','Total','','','8',$raw_data['text_total']);

} else
{
    // TODO: table source
    $option_table[] = array('label' => '< select table >','value' => '');
    $z = 1;
    foreach($project->get_tables() as $table)
    {
        $option_table[$z] = array('label' => 'Table `'.$table['title'].'`','value' => $table['prefix']);
        if($_GET['source'] == $table['prefix'])
        {
            $option_table[$z]['active'] = true;
        }
        $z++;
    }

    // TODO: page target
    $option_page[] = array('label' => '< select page >','value' => '');
    $z = 1;
    foreach($project->get_pages() as $page)
    {
        $option_page[$z] = array('label' => 'Page `'.$page['prefix'].'` '.$page['builder'].'','value' => $page['prefix']);
        if($_GET['target'] == $page['prefix'])
        {
            $option_page[$z]['active'] = true;
        }
        $z++;
    }

    $form_input .= $bs->FormGroup('page_target','horizontal','select','Page Target',$option_page,'Page will be overwritten',null,'4');
    $form_input .= $bs->FormGroup('table_source','horizontal','select','Data Source',$option_table,'Table source for quiz',null,'4');

}

$preview_url .= $_GET['target'];

$footer .= '
<script type="text/javascript">
     $("#table_source,#page_target").on("change",function(){
        window.location= "./?page=x-page-builder&prefix=page_quiz&source=" + $("#table_source").val() + "&target=" +  $("#page_target").val() ;
        return false;
     });
</script>
';

?>