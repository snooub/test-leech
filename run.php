<title>Hello</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<div id="content"></div>

<script src="prototype.js"></script>
<script>
function getCode(){
	new Ajax.Updater('content', 'code.php', {
		onSuccess: function() { window.setTimeout(getCode, 300); }
	});
}
getCode();
</script>
