<?php

class Index extends \AsyncWeb\Frontend\Block{
	public static $USE_BLOCK = true;
	protected function initTemplate(){
		$this->template = '<!DOCTYPE html>
<html lang="{{LANG}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generátor vzoru pre prihlášku hlasovania poštou</title>
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet">
	<script src="//code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <script async type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
	<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
</head>
<body>
{{{Header}}}
<div id="wrapper">
{{{SideBar}}}
<div class="container page-content-wrapper">
{{{Cat}}}
</div>
</div>

<script type="text/javascript">
$(function() {
  $("select").select2();
});
</script>
</body>
</html>';
	}
}