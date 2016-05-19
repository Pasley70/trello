<?php
$widget = $vars["entity"];

$limit = sanitise_int($widget->limit);
if (empty($limit)) {
	$limit = 5;
}

$filter = implode(',',$widget->filter);

if (!$widget->trello_board_id) {
	echo "<div class='elgg-output'>" . elgg_echo("widgets:trello:nocontent") . "</div>";
} else {

?>

<div class='elgg-output'>

<div class="boardActivity" id="widgetid-<?php echo $widget->guid; ?>" data-boardid="<?php echo $widget->trello_board_id; ?>" data-limit="<?php echo $limit; ?>" data-filter="<?php echo $filter; ?>">
</div>   

<div class="loggedIn">
	<p><?php echo elgg_echo("trello:connect:loggedinas"); ?> <span class="fullName"></span> - 
	<a id="disconnect" class=""><?php echo elgg_echo("trello:connect:revokeaccess"); ?></a></p>
</div>                  

<div class="loggedOut">
	<p><a id="connectLink" class="elgg-button elgg-button-action"><?php echo elgg_echo("trello:connect:connect"); ?> &raquo;</a></p>
</div>

</div>

<?php

if (elgg_is_xhr()) {
	?>
	<script type="text/javascript">
		drawboard($('#widgetid-<?php echo $widget->guid; ?>'));
	</script>
	<?php
}

}
?>


        

