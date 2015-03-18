<?php
$widget = $vars["entity"];
$boardid = $widget->trello_board_id;
$limit = sanitise_int($widget->limit);
if (empty($limit)) {
	$limit = 5;
}
$filter = $widget->filter;

if (empty($boardid)) {
	echo "<div class='elgg-output'>" . elgg_echo("widgets:trello:nocontent") . "</div>";
} else {

?>
<div class='elgg-output'>

<div id="boardActivity">
</div>   

<div class="loggedIn">
	<p><?php echo elgg_echo("trello:connect:loggedinas"); ?> <span class="fullName"></span> - 
	<a id="disconnect" class=""><?php echo elgg_echo("trello:connect:revokeaccess"); ?></a></p>
</div>                  

<div class="loggedOut">
	<p><a id="connectLink" class="elgg-button elgg-button-action"><?php echo elgg_echo("trello:connect:connect"); ?> &raquo;</a></p>
</div>

</div>

<script type='text/javascript'>

$(document).ready(function(){

	$('.loggedIn').hide();

	var getBoards = function (){
		updateLoggedIn();
		
		$("#boardList").empty();

		Trello.members.get("me", function(member){
			$(".fullName").text(member.fullName);

			
			var $boardActivity = $('<div>')
				.text("Loading Activity...")
				.appendTo("#boardActivity");

			// Output Board Activity 

			var output = '';
				
			Trello.get("boards/<?php echo $boardid; ?>/actions", {
					limit:"<?php echo $limit; ?>"
					<?php if ($filter) echo ',filter:"',implode(',',$filter),'"'; ?>
				}, function(actions) {
					
					/*
					for (key in actions) {
						console.log(actions[key]);
					}				
					*/
					
					output += '<p><strong><a href="https://trello.com/b/'+actions[0].data.board.shortLink+'" target="_blank">'+actions[0].data.board.name+'</a></strong></p>';

					$.each(actions, function(ix, ac) {
						
						var acdateiso = new Date(ac.date);
						var acdateutc = acdateiso.toUTCString()
					
						output += '<div class="card '+ac.type+'">';

						if(typeof ac.data.card != "undefined"){
							output += '<p><strong><a href="https://trello.com/c/'+ac.data.card.shortLink+'" target="_blank">'+ac.data.card.name+'</a></strong><br />';
						}
						
						output += '<span class="elgg-subtext mbn">'+acdateutc+',<br/>'+ac.memberCreator.fullName+' ('+ac.type+')</span><br />';
						output += '</p>';
						if(typeof ac.data.text != "undefined"){
							output += '<p><i>'+ac.data.text+'</i></p>';
						}
						
						output += '</div>';

					}); 

					output += '<p>';

					output += '</div>';

					$boardActivity.empty();
					$boardActivity.html(output);

				}, function(error) {
					$boardActivity.empty();
					$boardActivity.html('<p>Error: '+error.responseText+'</p>');
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


<?php
}
?>


        

