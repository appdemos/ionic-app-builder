<?php

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2016
 * @license Commercial License
 * 
 * @package Ionic App Builder
 */
 
if(!defined('JSM_EXEC')){
    die(':)');
}

$bs = new jsmBootstrap();
$content = $out_path = $footer = $html = null;

$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-users fa-stack-1x"></i></span>Helper Tools -&raquo; (IMAB) Credits and Licenses</h4>';

$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading">';
$content .= '<h5 class="panel-title">Tools Licenses</h5>';
$content .= '</div>';
$content .= '<div class="panel-body">';
$content .= '			
			<ul>
				<li><a href="http://codecanyon.net/licenses/terms/regular" target="_blank">Regular License</a></li>
				<li><a href="http://codecanyon.net/licenses/terms/extended" target="_blank">Extended License</a></li>
				<li><a href="http://codecanyon.net/licenses/terms/tools" target="_blank">Tools License</a></li>
			</ul>';
$content .= '</div>';
$content .= '</div>';
 

$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading">';
$content .= '<h5 class="panel-title">Other Licenses</h5>';
$content .= '</div>';
$content .= '<div class="panel-body">';

$content .= '
                <table class="table table-striped">
					<thead>
					<tr>
						<th>Name</th>
						<th>License</th>
						<th>Author</th>
						<th>Website</th>
					</tr>
					</thead>
					<tbody>

					<tr>
						<td>Ionic</td>
						<td>MIT License</td>
						<td>Drifty Co</td>
						<td>http://ionicframework.com/</td>
					</tr>
					
					<tr>
						<td>Ionic Material</td>
						<td>MIT License</td>
						<td>Rai Butera and Zach Fitzgerald</td>
						<td>https://github.com/zachsoft/Ionic-Material/</td>
					</tr>
					
					<tr>
						<td>Ionic LazyLoad</td>
						<td>MIT License</td>
						<td>Vincius Zilli Pavei, Michel Vidailhet</td>
						<td>https://github.com/paveisistemas/ionic-image-lazy-load</td>
					</tr>
					
					<tr>
						<td>Ion MD Input</td>
						<td>MIT License</td>
						<td>Mike Hartington</td>
						<td>https://github.com/mhartington/ion-md-input/</td>
					</tr>				
					 
 					<tr>
						<td>Ionic Rating</td>
						<td>MIT License</td>
						<td>xvfeng</td>
						<td>https://github.com/fraserxu/ionic-rating</td>
					</tr>	
                    
					<tr>
						<td>CodeMirror</td>
						<td>MIT License</td>
						<td>Marijn Haverbeke and others</td>
						<td>http://codemirror.net</td>
					</tr>
					
					<tr>
						<td>KCFinder</td>
						<td>GPL-3/LGPL-3 License</td>
						<td>sunHater</td>
						<td>http://kcfinder.sunhater.com</td>
					</tr>
					
					<tr>
						<td>Tabledit</td>
						<td>MIT License</td>
						<td>Celso Marques</td>
						<td>https://github.com/markcell/jQuery-Tabledit</td>
					</tr>
						
					<tr>
						<td>jQuery Sortable</td>
						<td>BSD License</td>
						<td>Jonas von Andrian</td>
						<td>http://johnny.github.io/jquery-sortable/</td>
					</tr>		
						
					<tr>
						<td>TinyMCE</td>
						<td>LGPL-2.1 License</td>
						<td>TinyMCE</td>
						<td>http://tinymce.com</td>
					</tr>
					<tr>
						<td>Bootstrap</td>
						<td>MIT License</td>
						<td>Twitter, Inc.</td>
						<td>http://getbootstrap.com</td>
					</tr>
					
					<tr>
						<td>FontAwesome</td>
						<td>OFL-1.1 AND MIT License</td>
						<td>Dave Gandy</td>
						<td>http://fontawesome.io</td>
					</tr>
				
				    <tr>
						<td>Marvel Device</td>
						<td>The MIT License (MIT)</td>
						<td>Marvelapp</td>
						<td>https://marvelapp.github.io/devices.css/</td>
					</tr>
	               
                   <tr>
						<td>Images Background</td>
						<td>Creative Commons CC0</td>
						<td>Pixabay</td>
						<td>https://pixabay.com/en/</td>
					</tr>
                    
                    <tr>
						<td>Images Avatar/Slidebox</td>
						<td>Free</td>
						<td>LampungCyberz</td>
						<td>http://www.lampungcyber.co.id</td>
					</tr>
                    
					</tbody>
					</table>
                    ';
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';

$template->demo_url = $out_path . '/www/#/';
$template->title = $template->base_title . ' | ' . 'Helper Tools -&raquo; Credits';
$template->base_desc = '';
$template->content = $content;
$template->footer = $footer;
$template->emulator = false;

?>