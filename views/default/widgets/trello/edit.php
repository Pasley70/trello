<?php

$trelloapikey = elgg_get_plugin_setting('trelloapikey', 'trello');
$widget = $vars["entity"];

$limit = sanitise_int($widget->limit);
if (empty($limit)) {
	$limit = 5;
}

$filter_options = array(
	elgg_echo("trello:filter:commentCard")=> "commentCard",
	elgg_echo("trello:filter:createCard") => "createCard",
	elgg_echo("trello:filter:updateCard") => "updateCard",
);

$filter = $widget->filter;

?>

<div>
	<p><?php echo elgg_echo("trello:edit:chooseboard"); ?></p>
	<div id="boardList"></div>   
</div>

<div class="loggedIn">
	<p><?php echo elgg_echo("trello:connect:loggedinas"); ?> <span class="fullName"></span> - 
	<a id="disconnect" class=""><?php echo elgg_echo("trello:connect:revokeaccess"); ?></a></p>
</div>                  

<div class="loggedOut">
	<p><a id="connectLink" class="elgg-button elgg-button-action"><?php elgg_echo("trello:connect:connect"); ?> &raquo;</a></p>
</div>

<div>
	<?php echo elgg_echo("trello:edit:filter"); ?><br />
	<?php
		echo elgg_view("input/checkboxes", array(
			"name" => "params[filter]",
			"options" => $filter_options,
			"value" => $filter));
	?>
</div>

<div>
	<?php echo elgg_echo("trello:edit:limit"); ?><br />
	<?php echo elgg_view("input/dropdown", array("name" => "params[limit]", "options" => range(1,10), "value" => $limit)); ?>
</div>

<script type='text/javascript'>

$(document).ready(function(){

	$('.loggedIn').hide();

	var getBoards = function (){
		updateLoggedIn();
		
		$("#boardList").empty();

		Trello.members.get("me", function(member){
			$(".fullName").text(member.fullName);
	
			var $boardList = $('<div class="selectboard">')
				.text("Loading Boards...")
				.appendTo("#boardList");

			// Output a list of all of the boards that the member 

			Trello.get("members/me/boards", {filter: "open"}, function(boards) {
				$boardList.empty();
				var output = '<select name="params[trello_board_id]" class="elgg-input-dropdown">';
				output += '<option value="">No Board</option>';
				$.each(boards, function(ix, board) {
					output += '<option value= "'+board.id+'"';
					if(board.id == '<?php echo $widget->trello_board_id; ?>') {
						output += ' selected="selected"';
					}
					output += '>'+board.name+'</option>';

				}); 
				output +='</select>';
				$boardList.html(output);
			}, function(error){
				$boardActivity.empty();
				$boardActivity.html('<p>Error</p>');
			});
			
			
		});
		
	}

	var updateLoggedIn = function() {
		var isLoggedIn = Trello.authorized();
		if (isLoggedIn){
			$(".loggedIn").show();     
			$(".loggedOut").hide();   
		} else {
			$(".loggedIn").hide();
			$(".loggedOut").show();
		}   
	};

	var getDateStamp = function(){
		var d = new Date();
		var year = d.getFullYear();
		var month = d.getMonth() + 1;
		var day = d.getDate();
		return year+'-'+month+'-'+day;
	};
	
	var logout = function() {
		Trello.deauthorize();
		updateLoggedIn();
	};
	
		  
	Trello.authorize({
		interactive:false,
		success: getBoards
	});

	$("#connectLink")
	.click(function(){
		Trello.authorize({
			type: "popup",
			success: getBoards,
			name: 'Trellista'
		})
	});

	$("#showLink").click(function(){
		Trello.authorize({
			interactive:false,
			success: getBoards
		});	
	});
	
	$("#disconnect").click(logout);

});
</script>