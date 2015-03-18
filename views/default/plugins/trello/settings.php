<?php
/**
 * Administrator sets the settings for the trello plugin
 *
 */

?>
<div>
	<p><?php echo elgg_echo('trello:settings:trelloapikey:explanation'); ?></p>
<?php
	echo elgg_view('input/text', array(
		'name' => 'params[trelloapikey]',
		'value' => $vars['entity']->trelloapikey
		)
	);
?>
	<p><span class="elgg-subtext"><?php echo elgg_echo('trello:settings:trellosecret:hint'); ?></span></p>
</div>

