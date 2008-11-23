<?php
/* 
Plugin Name: IP-Checker
Version: 1.1
Plugin URI: http://www.dsltester.de/dsl/wordpress-ip-widget/
Description: Zeigt Deinem Besucher seine IP-Adresse, das Betriebssystem, den Browsertyp und die Herkunft an.
Author: DSLtester.de
Author URI: http://www.dsltester.de/
*/

define('IP_URI', 'http://www.dsltester.de/images/dsl-infos/view/');

define('IP_IMG', serialize(
	array(
		array(
			'src'			=>	'125x125',
			'width'		=>	125,
			'height'	=>	125
		),
		array(
			'src'			=>	'120x240',
			'width'		=>	120,
			'height'	=>	240
		),
		array(
			'src'			=>	'468x60',
			'width'		=>	468,
			'height'	=>	60
		)
	)
));

function widget_ipaddress($args) {
  extract($args);
  
	$options = get_option("widget_ipaddress");
  if (!is_array( $options ))
	{
		$options = array(
      'title' => 'IP Address',
      'size' => '0',
      'center' => '1'
      );
  }
  
  if($options['center'] == 1)
		$style = "text-align: center;";
  
  $avail_images = unserialize(IP_IMG);
  
  echo $before_widget;
  	echo $before_title;
    	echo $options['title'];
  	echo $after_title;
  ?>
	<ul id="ipaddress_image" style="<?=$style ?>">
		<li>
	  	<a href="http://www.dsltester.de/speedtest/" title="DSL Speedtest" target="_blank"><img src="<?=IP_URI.$avail_images[$options['size']]['src'].".gif" ?>" alt="IP-Adresse" height="<?=$avail_images[$options['size']]['height'];?>" width="<?=$avail_images[$options['size']]['width'];?>" border="0" /></a>
		</li>
	</ul>
	<?php
  echo $after_widget;
}

function ipaddress_control()
{
 $options = get_option("widget_ipaddress");

  if (!is_array( $options ))
	{
		$options = array(
      'title' => 'IP Address',
      'size' => '0',
      'center' => '1'
      );
  }      

  if ($_POST['ipaddress-Submit'])
  {
    $options['title']			= htmlspecialchars($_POST['ipaddress-WidgetTitle']);
    $options['size']			= htmlspecialchars($_POST['ipaddress-WidgetSize']);
    $options['center']		= (isset($_POST['ipaddress-WidgetCenter'])) ? "1" : "0";

    update_option("widget_ipaddress", $options);
  }
  
	  $avail_images = unserialize(IP_IMG);
?>
<table>
	<tbody>
	<tr>
		<th scope="row" style="text-align:right;"><label for="ipaddress-WidgetTitle">Widget Title</label></th>
		<td><input type="text" id="ipaddress-WidgetTitle" name="ipaddress-WidgetTitle" value="<?php echo $options['title'];?>" /></td>
	</tr>
	<tr>
		<th style="vertical-align: top; text-align:right;" scope="row"><label for="image_num">Banner</label></th>
		<td style="vertical-align: top;">
			<?php
			foreach($avail_images AS $key => $img){
				$selected = '';
				if($key == $options['size']){
					$selected = 'checked="checked"';
				}
				?>
				<input type="radio" name="ipaddress-WidgetSize" value="<?=$key?>" <?=$selected?> /> <?=$img['width'].'x'.$img['height']?><br/>
				<?php
			}
			?>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row" style="text-align:right;"><label for="speed_image_center">Position</label></th>
		<td><input type="checkbox" <? if($options['center'] == 1) echo "checked=\"checked\""; ?>" value="1" ip="ipaddress-WidgetCenter" name="ipaddress-WidgetCenter"/> Center?</td>
	</tr>
	</tbody>
</table>
<input type="hidden" id="ipaddress-Submit" name="ipaddress-Submit" value="1" />
<?php
}

function ipaddress_config_page()
{
	if (function_exists('add_options_page')) {
		add_options_page('IP Address', 'IP Address', 8, basename(__FILE__), 'ipaddress_options');
	}
}

function ipaddress_init()
{
	register_sidebar_widget('IP Address', 'widget_ipaddress');
	register_widget_control('IP Address', 'ipaddress_control', 300, 200 );
}
add_action('init', 'ipaddress_init');
//add_action('admin_menu', 'ipaddress_config_page');
?>