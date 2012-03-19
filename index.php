<?php

$startTime = microtime(true);
ob_start('ob_gzhandler');

function html($s) {
    return htmlspecialchars($s, ENT_NOQUOTES);
}
function htmlq($s) {
    return htmlspecialchars($s, ENT_QUOTES);
}

require_once 'db.inc';

?><!DOCTYPE HTML>
<html manifest="./cache.manifest">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0" />
    <title>Shopping List</title>
    <script type="text/javascript" src="jquery.min.js"></script>
    <script type="text/javascript" src="jquery-ui.min.js"></script>
    <style type="text/css">
    /*<![CDATA[*/
    body {
        font-family: sans-serif;
        padding: 0px;
        margin: 0px;
    }
    div.header {
        position: fixed;
        top: 0px;
        left: 0px;
        width: 100%;
    }
    div.headerinner {
        background-color: #aaa;
        background-image: -webkit-gradient(
            linear,
            left bottom,
            left top,
            color-stop(0.25, #aaa),
            color-stop(0.75, #ddd)
        );
        background-image: -moz-linear-gradient(
            center bottom,
            #aaa 25%,
            #ddd 75%
        );
        padding: 20px 10px 5px 10px;
        margin: -20px 20px 20px 20px;
        border-bottom-left-radius: 20px;
        border-bottom-right-radius: 20px;
        border-radius-bottomleft: 20px;
        border-radius-bottomright: 20px;
        -webkit-border-bottom-left-radius: 20px;
        -webkit-border-bottom-right-radius: 20px;
        -moz-border-radius-bottomleft: 20px;
        -moz-border-radius-bottomright: 20px;
        -webkit-box-shadow: 5px 5px 7px rgba(0, 0, 0, 0.2);
        -moz-box-shadow: 5px 5px 7px rgba(0, 0, 0, 0.2);
        box-shadow: 5px 5px 7px rgba(0, 0, 0, 0.2);
    }
    div.header h1 {
        text-align: center;
        text-shadow: #aaa 3px 3px;
    }
    div.header div.options {
        text-align: right;
    }
    div.header a {
        color: #333;
    }
    
    div.footer {
        background-color: #aaa;
        background-image: -webkit-gradient(
            linear,
            left bottom,
            left top,
            color-stop(0.25, #aaa),
            color-stop(0.75, #ddd)
        );
        background-image: -moz-linear-gradient(
            center bottom,
            #aaa 25%,
            #ddd 75%
        );
        padding: 5px 10px;
        margin: 0px 20px 0px 0px;
        text-align: center;
        font-size: 0.8em;
        position: fixed;
        bottom: 0px;
        left: 0px;
        width: 100%;
    }
    
    abbr {
        border-bottom: 1px dashed;
        cursor: help;
    }
    abbr * {
        cursor: help;
    }
    
    #nav {
        text-align: center;
    }
    body.pick button#nav-pick, body.pick button#nav-list-off, body.list button#nav-list, body.list button#nav-pick-off {
        display: none;
    }
    body.pick button#nav-pick-off, body.pick button#nav-list, body.list button#nav-list-off, body.list button#nav-pick {
        display: inline;
    }
    body.pick *.pick, body.list *.list {
        display: inline;
    }
    body.pick *.list, body.list *.pick {
        display: none;
    }
    
    body.list ul#list > li > ul > li {
        display: none;
    }
    body.list ul#list > li > ul > li.picked {
        display: list-item;
    }
    
    div.mainbody {
        padding: 0px 10px;
        margin-top: 140px;
        margin-bottom: 60px;
    }
    ul#list h1 {
        margin: 0px 0px 2px 0px;
        padding: 0px 3px 2px 3px;
        background-color: #aaa;
        border: 3px solid #777;
    }
    ul#list li {
        list-style-type: none;
    }
    ul#list > li > ul {
        margin-bottom: 10px;
    }
    ul#list, ul#list > li > ul {
        padding-left: 0px;
    }
    
    ul#list > li > ul > li {
        border: 1px solid #fff;
        padding: 3px;
    }
    body.list ul#list > li > ul > li.checked {
        opacity: 0.2
    }
    ul#list > li > ul > li > button {
        width: 3em;
        height: 3em;
    }
    
    button {
        border: 1px solid #555;
        border-radius: 6px;
        
        -webkit-box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
        -moz-box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
        
        background-color: #ccc;
        background-image: -webkit-gradient(
            linear,
            left bottom,
            left top,
            color-stop(0.25, #aaa),
            color-stop(0.75, #ddd)
        );
        background-image: -moz-linear-gradient(
            center bottom,
            #aaa 25%,
            #ddd 75%
        );
    }
    
    @media all and (min-width: 600px) {
        /* Desktop browsers */
        ul#list > li > ul > li:hover {
            border: 1px solid #555;
            border-radius: 5px;
            background-color: #ccc;
        }
        ul#list > li > ul > li > button {
            width: 2em;
            height: 2em;
        }
    }
    
    @media all and (max-width: 600px) {
        /* Mobile browsers */
        ul#list > li > ul > li.clicked {
            border: 1px solid #555;
            border-radius: 5px;
            background-color: #ccc;
        }
        ul#list > li > ul > li > button {
            width: 3em;
            height: 3em;
        }
    }
    /*]]>*/
    </style>
</head>
<body class="list">

<div class="header">
    <div class="headerinner">
        <h1>Shopping List</h1>
        
        <div id="nav">
            <button id="nav-pick">Pick</button>
            <button id="nav-pick-off" disabled="disabled">Pick</button>
            
            <button id="nav-list">List</button>
            <button id="nav-list-off" disabled="disabled">List</button>
        </div>
    </div>
</div>

<div class="mainbody">
    <ul id="list">
        <? foreach ($listItems as $categoryName => $categoryItems) { ?>
            <li>
                <h1><?=html($categoryName)?></h1>
                <ul>
                    <? foreach ($categoryItems as $itemName) { ?>
                        <li>
                            <button class="checkbox list" data-checked="0">&nbsp;</button>
                            <button class="checkbox pick" data-checked="0">&nbsp;</button>
                            <span class="itemname"><?=html($itemName)?></span>
                        </li>
                    <? } ?>
                </ul>
            </li>
        <? } ?>
    </ul>
</div>

<div class="footer">
    <button id="checkout">Checkout</button>
</div>

<script type="text/javascript">
//<![CDATA[
try {
    $('body');
} catch (e) {
    alert("jQuery appears to not be loaded!");
}

function hideEmptyCategories() {
    $('#list > li').each(function() {
        $(this).show();
        $(this).toggle( ($(this).find('li:visible').length) >= 1 );
    });
}
hideEmptyCategories();

$('button#nav-pick').click(function(){
    $('body').addClass('pick').removeClass('list');
    hideEmptyCategories();
});
$('button#nav-list').click(function(){
    $('body').addClass('list').removeClass('pick');
    hideEmptyCategories();
});

function boxCheck(box) {
    $(box).data('checked', 1).html('∕').closest('li');
    if ($(box).is('.pick')) {
        $(box).closest('li').addClass('picked');
    } else {
        $(box).closest('li').addClass('checked');
    }
    saveCookie();
}
function boxUncheck(box) {
    $(box).data('checked', 0).html('&nbsp;').closest('li');
    if ($(box).is('.pick')) {
        $(box).closest('li').removeClass('picked');
    } else {
        $(box).closest('li').removeClass('checked');
    }
    saveCookie();
}
$('#checkout').click(function(){
    if (confirm("Are you sure you wish to check out?")) {
        $('#list > li > ul > li').each(function(){
            if ($(this).find('button.checkbox.list').data('checked')) {
                boxUncheck($(this).find('button.checkbox.pick'));
            }
        });
        hideEmptyCategories();
    }
});

$('button.checkbox').click(function(ev){
    if ($(this).data('checked')) {
        boxUncheck(this);
    } else {
        boxCheck(this);
    }
    return false;
});

$('#list > li > ul > li').click(function(ev){
    var li = $(this);
    li.find('button:visible').each(function(){
        $(this).click();
    });
    li.addClass('clicked');
    window.setTimeout(function(){
        li.removeClass('clicked');
    }, 250);
    ev.preventDefault();
    return false;
});
$(document).dblclick(function(ev){
    //Unfortunately, this doesn't work :(
    ev.preventDefault();
    return false;
});

var loadingFromCookies = false;
function loadFromCookie() {
    //Set this variable so that our box-checking doesn't trigger more cooking-saving
    loadingFromCookies = true;
    
    var pickedCookie = localStorage['slist-picked'];
    var boughtCookie = localStorage['slist-bought'];
    
    if (pickedCookie) {
        var picked = pickedCookie.split('¦');
        $(picked).each(function(){
            var itemname = this + '';
            itemname = itemname.replace(/(["\\])/g, '\\$1');
            $('span.itemname:contains("' + itemname + '")').siblings('button.pick').each(function(){
                boxCheck(this);
            });
        });
    }
    if (boughtCookie) {
        var bought = boughtCookie.split('¦');
        $(bought).each(function(){
            var itemname = this + '';
            itemname = itemname.replace(/(["\\])/g, '\\$1');
            $('span.itemname:contains("' + itemname + '")').siblings('button.list').each(function(){
                boxCheck(this);
            });
        });
    }
    
    loadingFromCookies = false;
    hideEmptyCategories();
}
function saveCookie() {
    if (loadingFromCookies) {
        //Currently loading; don't update storage
        return;
    }
    
    var picked=[], bought=[];
    
    $('#list > li > ul > li button.pick').filter(function(){
        return $(this).data('checked') == 1;
    }).siblings('span.itemname').each(function(){
        picked.push($(this).html());
    });
    $('#list > li > ul > li button.list').filter(function(){
        return $(this).data('checked') == 1;
    }).siblings('span.itemname').each(function(){
        bought.push($(this).html());
    });
    
    if (picked.length) {
        var pickedValue = picked.join('¦');
        localStorage['slist-picked'] = pickedValue;
    }
    if (bought.length) {
        var boughtValue = bought.join('¦');
        localStorage['slist-bought'] = boughtValue;
    }
}
loadFromCookie();

if (document.documentElement.clientWidth > 600) {
    // Desktop browsers
    
} else {
    // Mobile browsers
}
//]]>
</script>

</body>
</html>