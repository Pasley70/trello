<?php
/**
 * Trello integration
 *
 */

elgg_register_event_handler('init', 'system', 'trello_init');

/**
 * Trello initialization
 */
function trello_init() {

	$trelloapikey = elgg_get_plugin_setting('trelloapikey', 'trello');

	if(!empty($trelloapikey)){
		
		elgg_register_js('trelloclient','https://api.trello.com/1/client.js?key='.$trelloapikey);
		elgg_load_js('trelloclient');
		
		// js
		elgg_extend_view('js/elgg', 'trello/js');

		// css
		elgg_extend_view("css/elgg", "trello/css");
	
		// elgg_register_widget_type("trello", elgg_echo("widgets:trello:title"), elgg_echo("widgets:trello:description"), array("groups"), false);
		elgg_register_widget_type("trello", elgg_echo("widgets:trello:title"), elgg_echo("widgets:trello:description"), array("groups","dashboard","index"), true);

	}

}


