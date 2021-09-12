<?php
###############################################################
#                                                             #
# Community Applications copyright 2015-2021, Andrew Zawadzki #
#                   Licenced under GPLv2                      #
#                                                             #
###############################################################

header("Content-type: text/css; charset: UTF-8");

$docroot = $docroot ?? $_SERVER['DOCUMENT_ROOT'] ?: "/usr/local/emhttp";

require_once "$docroot/plugins/dynamix/include/Wrappers.php";

$dynamix = parse_plugin_cfg("dynamix");

$theme = $dynamix['theme'];

$unRaidSettings = parse_ini_file("/etc/unraid-version");

$unRaid66color = "#FF8C2F";
$linkColor = "#486dba";
$startupColor = "#FF8C2F";
switch ($theme) {
	case 'black':
		$donateBackground = "#f2f2f2";
		$donateText = "#1c1b1b";
		$templateBackground = "#0f0f0f";
		$templateFavourite = "#333333";
		$hrColor = "#2b2b2b";
		$borderColor = "#2b2b2b";
		$watermarkColor = "rgba(43, 43, 43, 0.4)";
		$aColor = "#00b8d6";
		$sidebarBackground = "#000000";
		$sidebarText = "#f2f2f2";
		$sidebarPos = "4rem;";
		$sidebarPosition ="absolute";
		$betaPopupOffset = "0";
		$supportPopupText = "#000000";
		$supportPopupBackground = "#ffffff";

		break;
	case 'white':
		$donateBackground = "#1c1b1b";
		$donateText = "#f2f2f2";
		$templateBackground = "#ffffff";
		$templateFavourite = "#d0d0d0";
		$hrColor = "lightgrey";
		$borderColor = "lightgrey";
		$watermarkColor = "rgba(211, 211, 211, 0.8)";
		$aColor = "#486dba";
		$sidebarBackground = "#dddddd";
		$sidebarText = "#000000";
		$sidebarPos = "4rem;";
		$sidebarPosition = "absolute";
		$betaPopupOffset = "0";
		$supportPopupText = "#020202";
		$supportPopupBackground = "#ffffff";

		break;
	case 'azure':
		$donateBackground = "#606e7f";
		$donateText = "#e4e2e4";
		$templateBackground = "#ffffff";
		$templateFavourite = "#e0e0e0";
		$hrColor = "#606e7f";
		$border = "#606e7f";
		$watermarkColor = "rgba(96, 110, 127, 0.1)";
		$aColor = "#486dba";
		$sidebarBackground = "#edeaef";
		$sidebarText = "#f2f2f2";	
		$sidebarPos = "-1.5rem;";
		$sidebarPosition = "fixed";
		$betaPopupOffset = "1.5rem;";		
		$supportPopupText = "#1b1d1b";
		$supportPopupBackground = "#ffffff";

		break;
	case 'gray':
		$donateBackground = "#606e7f";
		$donateText = "#1b1d1b";
		$templateBackground = "#121212";
		$templateFavourite = "#2b2b2b";
		$hrColor = "#606e7f";
		$border = "#606e7f";
		$watermarkColor = "rgba(96, 110, 127, 0.1)";
		$aColor = "#00b8d6";
		$sidebarBackground = "#0f0f0f";
		$sidebarText = "#f2f2f2";	
		$sidebarPos = "-1.5rem;";
		$sidebarPosition = "fixed";
		$betaPopupOffset = "1.5rem;";
		$supportPopupText = "#1b1d1b";
		$supportPopupBackground = "#ffffff";
		
		break;
// Use settings for black as a fallback
	default:
		$donateBackground = "#f2f2f2";
		$donateText = "#1c1b1b";
		$templateBackground = "#0f0f0f";
		$templateFavourite = "#333333";
		$hrColor = "#2b2b2b";
		$borderColor = "#2b2b2b";
		$watermarkColor = "rgba(43, 43, 43, 0.4)";
		$aColor = "#00b8d6";
		$sidebarBackground = "#000000";
		$sidebarText = "#f2f2f2";		
		$sidebarPos = "4rem;";
		$sidebarPosition ="absolute";
		$betaPopupOffset = "0";
		$supportPopupText = "#000000";
		$supportPopupBackground = "#ffffff";
		break;
}
?>
a {color:<?=$aColor?>;}






.actionsPopup a {text-decoration:none;color:<?=$supportPopupText?>;cursor:pointer;}
.actionsPopup {font-size:1.5rem;line-height:2rem;cursor:pointer;display:inline-block;color:<?=$supportPopupText?>!important;background: <?=$supportPopupBackground?>;background: -webkit-linear-gradient(top, transparent 0%, rgba(0,0,0,0.4) 100%),-webkit-linear-gradient(left, lighten(<?=$donateBackground?>, 15%) 0%, <?=$donateBackground?> 50%, lighten(<?=$donateBackground?>, 15%) 100%);  background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.4) 100%),linear-gradient(to right, lighten(#E68321, 15%) 0%, #E68321 50%, lighten(#E68321, 15%) 100%);  background-position: 0 0;  background-size: 100% 100%;  border-radius: 15px;  color: #fff;  padding: 1px 10px 1px 10px;}
.actionsPopup:hover{background-color:<?=$unRaid66color?>;}
.additionalRequirements {margin-left:2rem;}
.additionalRequirementsHeader {font-size:1.5rem;color:#d67777;font-weight:bold;}
.appIcons {font-size:2.3rem;color:inherit;cursor:pointer;padding-left:.5rem;padding-right:.5rem;}
.appIcons:hover {text-decoration:none;color:<?=$unRaid66color?> ! important;}
a.appIcons {text-decoration:none;}
.appIconsPopUp {font-size:2rem !important;cursor:pointer;padding-left:.5rem;padding-right:.5rem;color:default;}
.appIconsPopUp:hover {text-decoration:none;color:<?=$unRaid66color?>;}
a.appIconsPopUp { text-decoration:none;color:inherit;}
.betaPopupBackground{clip-path: polygon(0 0,100% 0, 100% 100%);background-color: #FF8C2F;top:<?=$betaPopupOffset?>;height:9rem;width:9rem;position: absolute;right: 0;}
.betaPopupText{position:absolute;transform:rotate(45deg);-webkit-transform:rotate(45deg);-moz-transform:rotate(45deg);-o-transform: rotate(45deg);color:white;font-size:2rem;position:absolute;top:1.3rem;right:1rem;}
body.stop-scrolling{height:70%;overflow:inherit;}  /* disable SweetAlert killing the scroll bar ( stops the wiggle ) */
.caChangeLog {cursor:pointer;}
.caChart {display:none;border:none;}
.caHelpIconSpacing {display:inline-block;width:7rem;height:3rem;}
.caHighlight {color:#d27676;}
.caInstallLinePopUp {display:flex;flex-wrap:wrap;justify-content:space-around;}
.caMenuDisabled {cursor:default;opacity:0.5;}
.caMenuEnabled {cursor:pointer;opacity:1;}
.cardDescription {cursor:pointer;}
.categoryLine {margin-left:10px;font-size:1.5rem;font-weight:normal;color:#6363ca;}
.ca_applicationInfo {display:inline-block;position:absolute;width:25rem;}
.ca_applicationName {font-size:1.6rem;font-weight:medium;}
a.ca_applicationName {text-decoration:none;color:inherit;}
a.ca_appPopup {text-decoration:none;cursor:pointer;}
.ca_author {cursor:pointer;font-size:1rem;font-style:italic;}
a.ca_author {text-decoration:none;color:inherit;}
.ca_bold {font-weight:bold;}
.ca_bottomLine {display:block;position:relative;padding-top:9.5rem;margin-left:1.5rem;}
.ca_bottomRight {float:right;margin-right:2rem;padding-top:0.5rem;}
.ca_categories {font-size:1rem;font-style:italic;}
a.ca_categories {text-decoration:none;color:inherit;}
.ca_categoryLink {color:<?=$linkColor?>;font-weight:normal;}
a.ca_categoryLink {text-decoration:none;color:inherit;}
.ca_center {margin:auto;text-align:center;}
.ca_credit { padding:.5rem 0 1rem 0; font-size:1.5rem;line-height:2rem; font-style:italic;}
.ca_creditheader { font-size:2rem; padding-top:1rem;}
.ca_dateUpdatedDate {font-weight:normal;}
.ca_description {color:#505E6F;}
.ca_descriptionArea {margin:1rem;width:auto;max-height:6rem;position:relative;margin-top:-11rem;}
.ca_descriptionArea:hover span {filter: invert(100%);}
.ca_descriptionArea:hover {color:<?=$unRaid66color?>;}
.ca_descriptionAreaRepository {margin:1rem;width:auto;max-height:6rem;position:relative;margin-top:-12rem;}
.ca_descriptionAreaRepository:hover span {filter: invert(100%);}
.ca_descriptionAreaRepository:hover {color:<?=$unRaid66color?>;}
.ca_discord::before{content:"\e988";font-family:Unraid;font-size:2.8rem;vertical-align:bottom;}
.ca_discord_popup::before{content:"\e988";font-family:Unraid;font-size:2.2rem;vertical-align:middle;}
.ca_display_beta {font-size:1rem;color:#FF8C2F;}
.ca_display_beta::after{content:"(BETA)"}
.ca_donate {position:relative;margin-left:18rem;}
.ca_fa-delete {color:#882626;}
.ca_fa-delete::before {content:"\f00d";font-family:fontAwesome;}
a.ca_fa-delete{text-decoration:none;margin-left:1rem;}
.ca_fa-edit::before {content:"\f044";font-family:fontAwesome;}
.ca_fa-globe::before {content:"\f0ac";font-family:fontAwesome;}
.ca_fa-info::before {content:"\f05a";font-family:fontAwesome;}
.ca_fa-install::before {content:"\f019";font-family:fontAwesome;}
.ca_fa-pluginSettings::before {content:"\f013";font-family:fontAwesome;}
.ca_fa-project::before {content:"\e953";font-family:Unraid;}
.ca_fa-support::before {content:"\f059";font-family:fontAwesome;}
.ca_fa-switchto::before {content:"\e982";font-family:Unraid;}
.ca_fa-uninstall::before {content:"\e92f";font-family:Unraid;}
.ca_fa-update::before {content:"\f0ed";font-family:fontAwesome;}
.ca_fa-warning::before {content:"\f071";font-family:fontAwesome;}
.ca_facebook::before {content:"\f09a";font-family:fontAwesome;}
.ca_favourite {cursor:default !important;}
.ca_favourite::before {content:"\f2be";font-family:fontAwesome;color:#1fa67a;}
.ca_favouriteRepo {font-size:2rem;cursor:pointer;margin-left:2.5rem !important;padding-right:.5rem;cursor:pointer;color:#1fa67a !important;padding:.3rem;}
.ca_favouriteRepo::before {content:"\f2be";font-family:fontAwesome;}
.ca_forum::before {content:"\f1cd";font-family:fontAwesome;}
.ca_holder {background-color:<?=$templateBackground?>;display:inline-block;float:left;height:23rem;min-width:24rem;max-width:24rem;flex-grow:1;flex-basis:24rem;overflow:hidden;padding:20px;margin-left:0px;margin-top:0px;margin-bottom:1rem;margin-right:1rem;border:1px solid;border-color:<?=$borderColor?>;border-radius:10px 10px 10px 10px;}
/* .ca_holder::before{position:relative;float:right;margin-top:.5rem;margin-right:3rem;font-family:'Unraid';content:'\e90b';font-size:9rem;color:<?=$watermarkColor?>;}
 */.ca_holderFav {background-color:<?=$templateFavourite?> !important;}
.ca_holderRepository {background-color:<?=$templateBackground?>;display:inline-block;float:left;height:24rem;min-width:37rem;max-width:50rem;flex-grow:1;flex-basis:37rem;overflow:hidden;padding:0px;margin-left:0px;margin-top:0px;margin-bottom:1rem;margin-right:1rem;border:1px solid;border-color:<?=$borderColor?>;border-radius:10px 10px 10px 10px;}
/* .ca_holderRepository::before{position:relative;float:right;margin-top:1.5rem;margin-right:3rem;margin-bottom:2rem;font-family:'fontAwesome';content:'\f2be';font-size:7rem;color:<?=$watermarkColor?>;} */
.ca_hr {margin-left:10px;margin-right:10px;border:1px; border-color:<?=$hrColor?>; border-top-style:solid;border-right-style:none;border-bottom-style:none;border-left-style:none;}
.ca_href {cursor:pointer;}
.ca_icon {width:6.4rem;height:6.4rem;padding-left:0.5rem;}
.ca_iconArea {width:100%;height:6.4rem;margin:1rem;}
.ca_infoArea {height:10rem;margin:1rem;display:inline-block;position:absolute;width:auto;}
.ca_italic {font-style:italic;}
.ca_LanguageDisclaimer {cursor:pointer;font-size:.9rem;}
.ca_LanguageDisclaimer:hover {color:<?=$linkColor?>;}
a.ca_LanguageDisclaimer {text-decoration:none;}
.ca_large {font-size:2rem;}
ul.caMenu {list-style-type: none;margin:0px 0px 20px 0px;padding: 0;font-size:1.5rem;}
li.caMenuItem {padding:0px 0px 5px 0px;}
.ca_multiselect {cursor:pointer;padding-right:5rem;}
.ca_NoAppsFound {font-size:3rem;margin:auto;text-align:center;}
.ca_NoDockerAppsFound {font-size:3rem;margin:auto;text-align:center;}
.ca_non_favouriteRepo {font-size:2rem;cursor:pointer;margin-left:2.5rem !important;padding-right:.5rem;cursor:pointer;padding:.3rem;}
.ca_non_favouriteRepo::before {content:"\f2be";font-family:fontAwesome;}
ul.nonselectMenu {list-style-type: none;margin:0px 0px 20px 0px;padding: 0;font-size:1.5rem;}
.ca_normal {font-size:1.4rem !important;}
.ca_private::after {content:"\f069";font-family:fontAwesome;}
.ca_private{color:#882626;}
.ca_profile::before {content:"\f2bb";font-family:fontAwesome;}
.ca_readmore {font-size:1.5rem !important;cursor:pointer;padding-left:.5rem;padding-right:.5rem;padding-top:1rem;}
.ca_readmore:hover {text-decoration:none;color:<?=$unRaid66color?>;}
.ca_reddit::before {content:"\f281";font-family:fontAwesome;}
.ca_red{color:#882626;}
.ca_repoinfo::before {content:"\f05a";font-family:fontAwesome;}
.ca_repoPopup {text-decoration:none!important;cursor:pointer;color:inherit;}
a.ca_repoPopup:hover {color:<?=$unRaid66color?>;}
.ca_repoSearch {font-size:2rem;cursor:pointer;padding-left:.5rem;padding-right:.5rem;cursor:pointer;padding:.3rem;}
.ca_repoSearch::after {content:"\f002";font-family:fontAwesome;}
.ca_repoSearchPopup {font-size:2rem;cursor:pointer;padding-left:.5rem;padding-right:.5rem;cursor:pointer;padding:.3rem;}
.ca_repository::before {content:"\f2be";font-family:fontAwesome;}
.ca_serverWarning {color:#cecc31}
.ca_showRepo::before {content:"\f002";font-family:fontAwesome;}
.ca_stat {color:coral; font-size:1.5rem;line-height:1.7rem;}
.ca_table { padding:.5rem 2rem .5rem 0; font-size:1.5rem;}
.ca_template {color:#606E7F;border-radius:0px 0px 2rem 2rem;display:inline-block;text-align:left;overflow:auto;height:27rem;width:36rem;padding-left:.5rem;padding-right:.5rem; background-color:#DDDADF;}
.ca_templatesDisplay {display:flex;flex-wrap:wrap;justify-content:center;overflow-x:hidden;}
.ca_template_icon {color:#606E7F;width:37rem;float:left;display:inline-block;background-color: #C7C5CB;margin:0px 0px 0px 0px;height:15rem;padding-top:1rem;}
.ca_toolsView {font-size:2.3rem; position:relative;top:-0.2rem;}
.ca_topRightArea {display:block;position:relative;margin-top:.5rem;margin-right:2rem;z-index:9999;float:right;}
.ca_twitter::before {content:"\f099";font-family:fontAwesome;}
.ca_webpage::before {content:"\f0ac";font-family:fontAwesome;}
.ca_wide_info {display: inline-block;float:left;text-align:left;margin-left:1rem;margin-top:1.5rem;width:20rem;}
.changelogMessage{font-size:1rem;line-height:1rem;margin-top:1rem;}
.changelogTitle{font-size:2rem;line-height:2rem;margin-top:2rem;font-weight:normal;}
.changelog{font-size:1.2rem;line-height:1.4rem;margin-top:1.5rem;}
.chartMenu{padding-left:2rem;cursor:pointer;}
.chartMenu:hover{color:<?=$unRaid66color?>;}
.charts{font-size:1.5rem;}
.chartTitle{margin-top:1.5rem;font-size:2rem;font-weight:bold;}
li.debugging {cursor:pointer;}
.disabledIcon {color:#040404;font-size:2.5rem;}
i.displayIcon {font-size:5.5rem;color:#626868;padding-top:0.25rem;}
img.displayIcon {height:6.4rem;width:6.4rem;border-radius:1rem 1rem 1rem 1rem;}
#cookieWarning {display:none;}

.displayBeta {margin-left:2rem;cursor:pointer;}
.display_beta {color:#FF8C2F;}
.docker::after{font-family:'Unraid';content:'\e90b';font-size:2.5rem;}
.dockerDisabled {display:none;}
.dockerHubStar {font-size:1rem;}
.dockerHubStar::before{content:"\e95a";font-family:UnRaid;}
.donate {background: <?=$donateBackground?>;background: -webkit-linear-gradient(top, transparent 0%, rgba(0,0,0,0.4) 100%),-webkit-linear-gradient(left, lighten(<?=$donateBackground?>, 15%) 0%, <?=$donateBackground?> 50%, lighten(<?=$donateBackground?>, 15%) 100%);  background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.4) 100%),linear-gradient(to right, lighten(#E68321, 15%) 0%, #E68321 50%, lighten(#E68321, 15%) 100%);  background-position: 0 0;  background-size: 200% 100%;  border-radius: 15px;  color: #fff;  padding: 1px 10px 1px 10px;  text-shadow: 1px 1px 5px #666;}
.donate:hover {text-decoration:none;background-color:<?=$unRaid66color?>;}
a.donate {text-decoration:none;font-style:italic;color:<?=$donateText?>;}
.donateLink {font-size:1.2rem;}
.enabledIcon {cursor:pointer;color:<?=$unRaid66color?>;}
.graphLink {cursor:pointer;text-decoration:none;}
.hoverMenu {color:<?=$unRaid66color?>;}
.infoButton {line-height:2rem;cursor:pointer;display:inline-block;color:<?=$supportPopupText?>!important;background: <?=$supportPopupBackground?>;background: -webkit-linear-gradient(top, transparent 0%, rgba(0,0,0,0.4) 100%),-webkit-linear-gradient(left, lighten(<?=$donateBackground?>, 15%) 0%, <?=$donateBackground?> 50%, lighten(<?=$donateBackground?>, 15%) 100%);  background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.4) 100%),linear-gradient(to right, lighten(#E68321, 15%) 0%, #E68321 50%, lighten(#E68321, 15%) 100%);  background-position: 0 0;  background-size: 100% 100%;  border-radius: 15px;  color: #fff;  padding: 1px 10px 1px 10px;}

input[type=checkbox] {width:2rem;height:2rem;margin-right:1rem;margin-top:-.5rem;margin-left:0rem;}
.mainArea {position:absolute;left:18.5rem;right:0px;top:2rem;display:block;overflow-x:hidden;min-height:90vh;}
.menuHeader { font-size:2rem; margin-bottom:1rem;margin-top:1rem;}
.menuItems {position:absolute;top:2rem;left:0px;width:14rem;height:auto;}
.modComment {color:#d67777;}
.moderatorCommentHeader {color:#d67777;font-size:2rem;font-weight:normal;}
.moderatorCommentHeader:before{content:"\f071";font-family:fontAwesome;}
.moderatorComment {font-size:1.2rem;font-style:italic;line-height:1.5rem;}
.moderationLink {color:<?=$linkColor?>;font-weight:normal;}
.multi_installDiv {width:100%; display:none;padding-bottom:20px;}
.myReadmoreButton {color:#6363ca;}
.newApp {color:red;font-size:1.5rem;cursor:pointer;}
.notice.shift {margin-top:0px;}
p {margin:auto;text-align:left;margin-bottom:10px;} /* override dynamix styling for popup */
.pageDots::after {content:"...";}
.pageDots{color:grey;cursor:default;}
.pageLeft::after {content:"\f137";font-family:fontAwesome;font-weight:bold;}
.pageNavigation {font-size:2.0rem;}
.pageNavNoClick {font-size:2.0rem;color:grey;cursor:default;}
.pageNumber{margin-left:1rem;margin-right:1rem;cursor:pointer;text-decoration:none !important;}
.pageRight::after {content:"\f138";font-family:fontAwesome;font-weight:bold;}
.pageSelected {cursor:default;}
.pinned {font-size:2rem;cursor:pointer;padding-left:.5rem;padding-right:.5rem;cursor:pointer;color:#1fa67a;padding:.3rem;}
.pinned::after {content:"\f08d";font-family:fontAwesome;}
.pinned:hover {text-decoration:none;color:<?=$unRaid66color?>;}
.plugin::after {font-family:'Unraid';content:'\e986';font-size:2.5rem;}
.popup-donate {background:white;background: -webkit-linear-gradient(top, transparent 0%, rgba(0,0,0,0.4) 100%),-webkit-linear-gradient(left, lighten(<?=$donateBackground?>, 15%) 0%, <?=$donateBackground?> 50%, lighten(<?=$donateBackground?>, 15%) 100%);  background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.4) 100%),              linear-gradient(to right, lighten(#E68321, 15%) 0%, #E68321 50%, lighten(#E68321, 15%) 100%);  background-position: 0 0;  background-size: 200% 100%;  border-radius: 15px;  color: #fff;  padding: 1px 10px 1px 10px;  text-shadow: 1px 1px 5px #666;}
.popupAuthor{;font-size:1.2rem;line-height:1.5rem;}
.popupCategory{font-size:1rem;line-height:1rem;}
.popUpClose {top:-2rem;font-size:1.5rem;color:#f34646;font-weight:bold;cursor:pointer;}
.popUpClose:hover {color:<?=$unRaid66color?>;}
.popUpDeprecated {color:#FF8C2F;}
.popupDescriptionArea{display:block;font-size:1.5rem;color:<?=$sidebarText?>;}
.popupDescription{font-size:1.5rem;line-height:1.7rem;margin-top:1rem;margin-left:1rem;margin-right:1rem;margin-bottom:0px;}
.popupHolder,.tooltipster-box {max-height:500px;}
.popupIcon {display:inline-block;}
i.popupIcon {color:#626868;font-size:14.4rem;padding-left:1rem;}
img.popupIcon {width:14.4rem;height:14.4rem;padding:0.3rem;border-radius:1rem 1rem 1rem 1rem;}
.popupInfo{position:absolute;top:10rem;left:22rem;}
.popUpLink {cursor:pointer;color:<?$aColor?>;}
a.popUpLink {text-decoration:none;}
a.popUpLink:hover {color:<?=$unRaid66color?>;}
.popupName{display:inline-block;font-size:2rem;line-height:3rem;font-weight:bold;}
.popupTable td {width:30%;text-align:left;}
.popupTable{font-size:1.5rem;width:55rem;margin-top:0px;margin-left:auto;}
.popupTitle{margin:auto;text-align:center;font-weight:bold;font-size:2rem;line-height}
.popup{margin:1.5rem;margin-bottom:15rem;margin-top:-2rem;}
a.popup-donate {text-decoration:none;font-style:italic;color:black;font-size:1.5rem;}
a.popup-donate:hover {color:<?=$donateText?>;background-color:<?=$unRaid66color?>}
.readmore-js-collapsed{-webkit-mask-image: -webkit-gradient(linear, left top, left bottom, from(rgba(0,0,0,1)), to(rgba(0,0,0,0.1)));}
.searchArea {z-index:2;width:auto;position:static;}
#searchBox{top:-0.6rem;padding:0.6rem;}
.searchSubmit{height:3.4rem;}
<?if ( $theme == "azure" ):?>
.searchSubmit{font-family:'FontAwesome';width:2.9rem;height:2.9rem;border:.1rem solid #dadada;border-radius:4px 4px 4px 4px;font-size:1.1rem;position:relative; top:-.7rem;padding:0px .2rem;background:transparent;border:none;cursor:pointer;}
#searchBox{margin-left:1rem;margin-right:0;position:relative;top:-.6rem;border:none;}
<?endif;?>
<?if ( $theme == "black" ):?>
.searchSubmit{font-family:'FontAwesome';width:2.9rem;height:3.4rem;border:1px solid #dadada;border-radius:4px 4px 4px 4px;font-size:1.1rem;position:relative; top:-6px;padding:0px 2px;background:transparent;border:none;cursor:pointer;}
#searchBox{margin-left:1rem;margin-right:0;position:relative;top:-.6rem;border:none;padding:0.6rem;background-color:#262626;}
<?endif;?>
<?if ( $theme == "gray" ):?>
.searchSubmit{font-family:'FontAwesome';width:2.9rem;height:2.9rem;border:.1rem solid #dadada;border-radius:4px 4px 4px 4px;font-size:1.1rem;position:relative; top:-.7rem;padding:0px .2rem;background:transparent;border:none;cursor:pointer;}
#searchBox{margin-left:1rem;margin-right:0;position:relative;top:-.6rem;border:none;}
<?endif;?>
<?if ( $theme == "white" ):?>
.searchSubmit{font-family:'FontAwesome';width:2.9rem;height:3.4rem;border:1px; solid #dadada;border-radius:4px 4px 4px 4px;font-size:1.1rem;position:relative; top:-6px;padding:0px 2px;background:transparent;border:none;cursor:pointer;}
#searchBox{margin-left:1rem;margin-right:0;position:relative;top:-.6rem;border:none;padding:0.6rem;}
<?endif;?>
.selectedMenu {color:<?=$unRaid66color?>;font-weight:bold;}
.showCharts:hover{color:<?=$unRaid66color?>;}
.showCharts{cursor:pointer;}
.sidenavHide{width:0px;}
.sidenavShow{width:700px;}
.sidenav{position:<?=$sidebarPosition?>;z-index:999;top:<?=$sidebarPos?>;right:0;background-color:<?=$sidebarBackground?>;color:<?=$sidebarText?>;overflow-x:hidden;transition:0.5s;padding-top:60px;opacity:0.95;}
.sortIcons {font-size:1.2rem;margin-right:20px;cursor:pointer;text-decoration:none !important;}
.specialCategory {font-size:1.5rem;}
.startup-icon {color:lightblue;font-size:1.5rem;cursor:pointer;}
.startupMessage2{font-size:1rem;}
.startupMessage{font-size:2.5rem;}
ul.subCategory {list-style-type:none;margin-left:2rem;padding:0px;cursor:pointer;display:none;}
.supportLink {color:inherit;padding-left:.5rem;padding-right:.5rem;}
.supportPopup a {text-decoration:none;color:<?=$supportPopupText?>;cursor:pointer;}
.supportPopup {font-size:1.5rem;line-height:2rem;cursor:pointer;display:inline-block;color:<?=$supportPopupText?>!important;background: <?=$supportPopupBackground?>;background: -webkit-linear-gradient(top, transparent 0%, rgba(0,0,0,0.4) 100%),-webkit-linear-gradient(left, lighten(<?=$donateBackground?>, 15%) 0%, <?=$donateBackground?> 50%, lighten(<?=$donateBackground?>, 15%) 100%);  background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.4) 100%),linear-gradient(to right, lighten(#E68321, 15%) 0%, #E68321 50%, lighten(#E68321, 15%) 100%);  background-position: 0 0;  background-size: 100% 100%;  border-radius: 15px;  color: #fff;  padding: 1px 10px 1px 10px;}
.supportPopup:hover{background-color:<?=$unRaid66color?>;}
.sweet-alert table{margin-top:0px}
.sweet-overlay{background-color:rgba(0, 0, 0, 0) !important;} /* don't dim if spinner is displayed */
table tbody td {line-height:1.8rem;}
table {background-color:transparent;}
#templates_content {overflow-x:hidden;}
.trendingDown::before {content:"\f063";font-family:fontAwesome;}
.trendingUp::before {content:"\f062";font-family:fontAwesome;}
.unpinned {font-size:2rem;cursor:pointer;padding-left:.5rem;padding-right:.5rem;cursor:pointer;padding:.3rem;}
.unpinned::after {content:"\f08d";font-family:fontAwesome;display:inline-block;-webkit-transform: rotate(20deg);-moz-transform: rotate(20deg);-ms-transform: rotate(20deg); -o-transform: rotate(20deg);  transform: rotate(20deg);}
.unpinned:hover {text-decoration:none;color:<?=$unRaid66color?>;}
.unraidIcon {margin-top:4rem;}
.warning-red {color:#882626;}
.warning-yellow {color:#FF8C2F;}
#warningNotAccepted {display:none;}




.awesomplete [hidden] {display: none;}
.awesomplete .visually-hidden {position: absolute;clip: rect(0, 0, 0, 0);}
.awesomplete {display: inline-block;position: relative;color: red;}
.awesomplete > input {display: block;}
.awesomplete > ul {position: absolute;left: 0;z-index: 1;min-width: 100%;box-sizing: border-box;list-style: none;padding: 0;margin: 0;background: #fff;}
.awesomplete > ul:empty {display: none;}
.awesomplete > ul {border-radius: .3em;margin: .2em 0 0;background: hsla(0,0%,100%);background: linear-gradient(to bottom right, white, hsla(0,0%,100%));border: 1px solid rgba(0,0,0,.3);box-shadow: .05em .2em .6em rgba(0,0,0,.2);text-shadow: none;}
@supports (transform: scale(0)) {.awesomplete > ul {transition: .3s cubic-bezier(.4,.2,.5,1.4);transform-origin: 1.43em -.43em;}
	.awesomplete > ul[hidden],.awesomplete > ul:empty {opacity: 0;transform: scale(0);display: block;transition-timing-function: ease;}
}
/* Pointer */
.awesomplete > ul:before {content: "";position: absolute;top: -.43em;left: 1em;width: 0; height: 0;padding: .4em;background: white;border: inherit;border-right: 0;border-bottom: 0;-webkit-transform: rotate(45deg);transform: rotate(45deg);}
.awesomplete > ul > li {position: relative;padding: .2em .5em;cursor: pointer;}
.awesomplete > ul > li:hover {background: hsl(200, 40%, 80%);color: black;}
.awesomplete > ul > li[aria-selected="true"] {background: hsl(205, 40%, 40%);color: white;}
.awesomplete mark {background: hsl(65, 100%, 50%);}
.awesomplete li:hover mark {background: hsl(68, 100%, 41%);}
.awesomplete li[aria-selected="true"] mark {background: hsl(86, 100%, 21%);color: inherit;}
