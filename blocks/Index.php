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
	<script async src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
	<link href="/assets/css/style.css" rel="stylesheet" />
	<script async src="/assets/js/main.js"></script>
</head>
<body>
<header>
    <div class="banner">
        <div class="container">
            <div class="label label-default pull-right" id="count-down"></div>
            <div class="pull-left">
                Voľby do Národnej rady Slovenskej republiky 5. marca 2016
            </div>

            <div class="clearfix"></div>
        </div>
    </div>

    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                    <span class="sr-only">Ukázať/skryť hlavné menu</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">
                    <img alt="Brand" src="/img/brand-logo.png" style="display: inline-block">
                </a>
            </div>

            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="/Cat:VBydlisku/">Voľby v mieste bydliska</a>
                    </li>
                    <li>
                        <a href="/Cat:MimoBydliska/">Voľby mimo miesta bydliska</a>
                    </li>
                    <li>
                        <a href="/Cat:PostouS/">Poštou zo zahraničia s trvalým pobytom</a>
                    </li>
                    <li>
                        <a href="/Cat:PostouBez/">Poštou zo zahraničia bez trvalého pobytu</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
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