<?PHP
###############################################################
#                                                             #
# Community Applications copyright 2015-2021, Andrew Zawadzki #
#                   Licenced under GPLv2                      #
#                                                             #
###############################################################

function display_apps($pageNumber=1,$selectedApps=false,$startup=false) {
	global $caPaths, $caSettings, $sortOrder;

	if ( is_file($caPaths['repositoriesDisplayed']) ) {
		$file = readJsonFile($caPaths['repositoriesDisplayed']);
		//$startup = true;
	} else {
		if ( is_file($caPaths['community-templates-catSearchResults']) )
			$file = readJsonFile($caPaths['community-templates-catSearchResults']);
		else
			$file = readJsonFile($caPaths['community-templates-displayed']);
	}
	$communityApplications = is_array($file['community']) ? $file['community'] : array();
	$totalApplications = count($communityApplications);

	$display = ( $totalApplications ) ? my_display_apps($communityApplications,$pageNumber,$selectedApps,$startup) : "<div class='ca_NoAppsFound'>".tr("No Matching Applications Found")."</div><script>$('.multi_installDiv').hide();hideSortIcons();</script>";

	return $display;
}

function my_display_apps($file,$pageNumber=1,$selectedApps=false,$startup=false) {
	global $caPaths, $caSettings, $plugin, $displayDeprecated, $sortOrder;
	
	$info = getRunningContainers();
	$dockerUpdateStatus = readJsonFile($caPaths['dockerUpdateStatus']);

	if ( ! $selectedApps )
		$selectedApps = array();

	$dockerNotEnabled = (! $caSettings['dockerRunning'] && ! $caSettings['NoInstalls']) ? "true" : "false";
	$displayHeader = "<script>addDockerWarning($dockerNotEnabled);var dockerNotEnabled = $dockerNotEnabled;</script>";

	$pinnedApps = readJsonFile($caPaths['pinnedV2']);

	$checkedOffApps = arrayEntriesToObject(@array_merge(@array_values($selectedApps['docker']),@array_values($selectedApps['plugin'])));

/* 	$displayHeader .= getPageNavigation($pageNumber,count($file),false)."<br>";
 */
	$columnNumber = 0;
	$appCount = 0;
	$startingApp = ($pageNumber -1) * $caSettings['maxPerPage'] + 1;
	$startingAppCounter = 0;

	$displayedTemplates = array();
	foreach ($file as $template) {
		if ( $template['Blacklist'] && ! $template['NoInstall'] )
			continue;

		$startingAppCounter++;
		if ( $startingAppCounter < $startingApp ) continue;
		$displayedTemplates[] = $template;
	}



	$iconClass = "displayIcon";

	$currentServer = @file_get_contents($caPaths['currentServer']);

	# Create entries for skins.
	foreach ($displayedTemplates as $template) {
		if ( $template['RepositoryTemplate'] ) {
			$template['Icon'] = $template['icon'] ?: "/plugins/dynamix.docker.manager/images/question.png";
			$template['display_iconClickable'] = "<img class='displayIcon ca_tooltip ca_repoPopup' title='".tr("Click for more information")."' src='{$template['icon']}' data-repository='".htmlentities($template['RepoName'],ENT_QUOTES)."'></img>";
			$template['display_infoIcon'] = "<a class='appIcons ca_repoinfo ca_tooltip' title='".tr("Click for more information")."' data-repository='".htmlentities($template['RepoName'],ENT_QUOTES)."'></a>";

			if ( ! $template['bio'] )
				$template['CardDescription'] = tr("No description present");
			else
				$template['CardDescription'] = $template['bio'];
			$template['bio'] = strip_tags(markdown($template['bio']));

			$template['display_dockerName'] = $template['RepoName'];

			$template['display_DonateImage'] = $template['DonateLink'] ? "<a class='ca_tooltip donateLink donate ca_href' data-href='{$template['DonateLink']}' data-target='_blank' title='{$template['DonateText']}'>".tr("Donate")."</a>" : "";

/* 			$supportContext = array();
			if ( $template['Forum'] )
				$supportContext = [
				$template['display_faSupport'] = "<a class='ca_tooltip ca_forum appIcons ca_href' data-target='_blank' data-href='{$template['Forum']}' title='".tr("Go to the forum")."'></a>";
			if ( $template['Twitter'] )
				$template['display_twitter'] = "<a class='ca_tooltip ca_twitter appIcons ca_href' data-target='_blank' data-href='{$template['Twitter']}' title='".tr("Go to twitter")."'></a>";
			if ( $template['Reddit'] )
				$template['display_reddit'] = "<a class='ca_tooltip ca_reddit appIcons ca_href' data-target='_blank' data-href='{$template['Reddit']}' title='".tr("Go to reddit")."'></a>";
			if ( $template['Facebook'] )
				$template['display_facebook'] = "<a class='ca_tooltip ca_facebook appIcons ca_href' data-target='_blank' data-href='{$template['Facebook']}' title='".tr("Go to facebook")."'></a>";
			if ( $template['Discord'] ) {
				$template['display_discord'] = "<a class='ca_tooltip ca_discord appIcons ca_href' data-target='_blank' data-href='{$template['Discord']}' title='".tr("Go to discord")."'></a>";
			}
			if ( $template['WebPage'] )
				$template['display_webpage'] = "<a class='ca_tooltip ca_webpage appIcons ca_href' data-target='_blank' data-href='{$template['WebPage']}' title='".tr("Go to webpage")."'></a>";
		 */	if ( $template['profile'] )
				$template['display_profile'] = "<a class='ca_tooltip ca_profile appIcons ca_href' data-target='_blank' data-href='{$template['profile']}' title='".tr("Go to forum profile")."'></a>";
			$favClass = ( $caSettings['favourite'] && ($caSettings['favourite'] == $template['RepoName']) ) ? "ca_favouriteRepo" : "ca_non_favouriteRepo";
			$template['ca_fav'] = $caSettings['favourite'] && ($caSettings['favourite'] == $template['RepoName']);
			$niceRepoName = str_replace("'s Repository","",$template['RepoName']);
			$niceRepoName = str_replace("' Repository","",$niceRepoName);
			$niceRepoName = str_replace(" Repository","",$niceRepoName);
			$favMsg = ($favClass == "ca_favouriteRepo") ? tr("Click to remove favourite repository") : tr(sprintf("Click to set %s as favourite repository",$niceRepoName));

			$template['display_favouriteButton'] = "<span class='appIcons ca_tooltip $favClass ca_fav' data-repository='".htmlentities($template['RepoName'],ENT_QUOTES)."' title='$favMsg'></span>";
			$template['display_repoSearch'] = "<span class='appIcons ca_tooltip ca_repoSearch' data-repository='".htmlentities($template['RepoName'],ENT_QUOTES)."' title='".tr("Search for apps in repository")."'></span>";
			$ct .= displayCard($template);
			$count++;
			if ( $count == $caSettings['maxPerPage'] ) break;
		} else {
			$template['checked'] = $checkedOffApps[$previousAppName] ? "checked" : "";

			if ( ! $Plugin && ! $Language )
				$template['DockerInfo'] = $info[$template['Name']];

	# Entries created.  Now display it
			$ct .= displayCard($template);
			$count++;
			if ( $count == $caSettings['maxPerPage'] ) break;
		}
	}

	$ct .= getPageNavigation($pageNumber,count($file),false,true)."<br><br><br>";

	if ( $specialCategoryComment ) {
		$displayHeader .= "<span class='specialCategory'><div class='ca_center'>".tr("This display is informational ONLY.")."</div><br>";
		$displayHeader .= "<div class='ca_center'>$specialCategoryComment</div></span>";
	}

	if ( ! $count )
		$displayHeader .= "<div class='ca_NoAppsFound'>".tr("No Matching Applications Found")."</div><script>hideSortIcons();</script>";

	return "$displayHeader$ct";
}

function getPageNavigation($pageNumber,$totalApps,$dockerSearch,$displayCount = true) {
	global $caSettings;

	if ( $caSettings['maxPerPage'] < 0 ) return;
	$swipeScript = "<script>";
	$my_function = $dockerSearch ? "dockerSearch" : "changePage";
	if ( $dockerSearch )
		$caSettings['maxPerPage'] = 25;
	$totalPages = ceil($totalApps / $caSettings['maxPerPage']);

	if ($totalPages == 1) return;

	$startApp = ($pageNumber - 1) * $caSettings['maxPerPage'] + 1;
	$endApp = $pageNumber * $caSettings['maxPerPage'];
	if ( $endApp > $totalApps )
		$endApp = $totalApps;

	$o = "</div><div class='ca_center'>";
	if ( ! $dockerSearch && $displayCount)
		$o .= "<span class='pageNavigation'>".sprintf(tr("Displaying %s - %s (of %s)"),$startApp,$endApp,$totalApps)."</span><br>";

	$o .= "<div class='pageNavigation'>";
	$previousPage = $pageNumber - 1;
	$o .= ( $pageNumber == 1 ) ? "<span class='pageLeft pageNumber pageNavNoClick'></span>" : "<span class='pageLeft ca_tooltip pageNumber' onclick='{$my_function}(&quot;$previousPage&quot;)'></span>";
	$swipeScript .= "data.prevpage = $previousPage;";
	$startingPage = $pageNumber - 5;
	if ($startingPage < 3 )
		$startingPage = 1;
	else
		$o .= "<a class='ca_tooltip pageNumber' onclick='{$my_function}(&quot;1&quot;);'>1</a><span class='pageNumber pageDots'></span>";

	$endingPage = $pageNumber + 5;
	if ( $endingPage > $totalPages )
		$endingPage = $totalPages;

	for ($i = $startingPage; $i <= $endingPage; $i++)
		$o .= ( $i == $pageNumber ) ? "<span class='pageNumber pageSelected'>$i</span>" : "<a class='ca_tooltip pageNumber' onclick='{$my_function}(&quot;$i&quot;);'>$i</a>";

	if ( $endingPage != $totalPages) {
		if ( ($totalPages - $pageNumber ) > 6)
			$o .= "<span class='pageNumber pageDots'></span>";

		if ( ($totalPages - $pageNumber ) >5 )
			$o .= "<a class='ca_tooltip pageNumber' onclick='{$my_function}(&quot;$totalPages&quot;);'>$totalPages</a>";
	}
	$nextPage = $pageNumber + 1;
	$o .= ( $pageNumber < $totalPages ) ? "<span class='ca_tooltip pageNumber pageRight' onclick='{$my_function}(&quot;$nextPage&quot;);'></span>" : "<span class='pageRight pageNumber pageNavNoClick'></span>";
	$swipeScript .= ( $pageNumber < $totalPages ) ? "data.nextpage = $nextPage;" : "data.nextpage = 0;";
	$swipeScript .= ( $dockerSearch ) ? "dockerSearchFlag = true;" : "dockerSearchFlag = false";
	$swipeScript .= "</script>";
	$o .= "</div></div><script>data.currentpage = $pageNumber;</script>";
	return $o.$swipeScript;
}

########################################################################################
# function used to display the navigation (page up/down buttons) for dockerHub results #
########################################################################################
function dockerNavigate($num_pages, $pageNumber) {
	return getPageNavigation($pageNumber,$num_pages * 25, true);
}

##############################################################
# function that actually displays the results from dockerHub #
##############################################################
function displaySearchResults($pageNumber) {
	global $caPaths, $caSettings, $plugin;

	$tempFile = readJsonFile($caPaths['dockerSearchResults']);
	$num_pages = $tempFile['num_pages'];
	$file = $tempFile['results'];
	$templates = readJsonFile($caPaths['community-templates-info']);

	$ct = dockerNavigate($num_pages,$pageNumber)."<br>";
	$ct .= "<div class='ca_templatesDisplay'>";

	$columnNumber = 0;
	foreach ($file as $result) {
		$result['Icon'] = "/plugins/dynamix.docker.manager/images/question.png";
		$result['display_dockerName'] = "<a class='ca_tooltip ca_applicationName' style='cursor:pointer;' onclick='mySearch(this.innerText);' title='".tr("Search for similar containers")."'>{$result['Name']}</a>";
		$result['display_author'] = "<a class='ca_tooltip ca_author' onclick='mySearch(this.innerText);' title='".sprintf(tr("Search For Containers From %s"),$result['Author'])."'>{$result['Author']}</a>";
		$result['Category'] = "Docker Hub Search";
		$result['display_iconClickable'] = "<i class='displayIcon fa fa-docker'></i>";
		$result['Description'] = $result['Description'] ?: "No description present";
		$result['display_faProject'] = "<a class='ca_tooltip ca_fa-project appIcons ca_href' title='Go to dockerHub page' data-target='_blank' data-href='{$result['DockerHub']}'></a>";
		$result['display_dockerInstallIcon'] = $caSettings['NoInstalls'] ? "" : "<a class='ca_tooltip ca_fa-install appIcons' title='".tr("Click to install")."' onclick='dockerConvert(&#39;".$result['ID']."&#39;);'></a>";
		$ct .= displayCard($result);
		$count++;
	}
	$ct .= "</div>";

	return $ct.dockerNavigate($num_pages,$pageNumber);
}

######################################
# Generate the display for the popup #
######################################
function getPopupDescriptionSkin($appNumber) {
	global $caSettings, $caPaths, $language;

	$unRaidVars = parse_ini_file($caPaths['unRaidVars']);
	$dockerVars = parse_ini_file($caPaths['docker_cfg']);
	$caSettings = parse_plugin_cfg("community.applications");
	$csrf_token = $unRaidVars['csrf_token'];
	$tabMode = '_parent';

	$allRepositories = readJsonFile($caPaths['repositoryList']);
	$pinnedApps = readJsonFile($caPaths['pinnedV2']);

	if ( is_file("/var/run/dockerd.pid") && is_dir("/proc/".@file_get_contents("/var/run/dockerd.pid")) ) {
		$caSettings['dockerRunning'] = "true";
		$DockerTemplates = new DockerTemplates();
		$DockerClient = new DockerClient();
		$info = $DockerTemplates->getAllInfo();
		$dockerRunning = $DockerClient->getDockerContainers();
		$dockerUpdateStatus = readJsonFile($caPaths['dockerUpdateStatus']);
	} else {
		unset($caSettings['dockerRunning']);
		$info = array();
		$dockerRunning = array();
		$dockerUpdateStatus = array();
	}
	if ( ! is_file($caPaths['warningAccepted']) )
		$caSettings['NoInstalls'] = true;

	# $appNumber is actually the path to the template.  It's pretty much always going to be the same even if the database is out of sync.
	if ( is_file($caPaths['community-templates-allSearchResults']) )
		$displayed = readJsonFile($caPaths['community-templates-allSearchResults']);
	else
		$displayed = readJsonFile($caPaths['community-templates-displayed']);

	foreach ($displayed as $file) {
		$index = searchArray($file,"Path",$appNumber);
		if ( $index === false ) {
			continue;
		} else {
			$template = $file[$index];
			$Displayed = true;
			break;
		}
	}
	# handle case where the app being asked to display isn't on the most recent displayed list (ie: multiple browser tabs open)
	if ( ! $template ) {
		$file = readJsonFile($caPaths['community-templates-info']);
		$index = searchArray($file,"Path",$appNumber);

		if ( $index === false ) {
			echo json_encode(array("description"=>tr("Something really wrong happened.  Reloading the Apps tab will probably fix the problem")));
			return;
		}
		$template = $file[$index];
		$Displayed = false;
	}
	$template['Displayed'] = $Displayed;
	$currentServer = file_get_contents($caPaths['currentServer']);

	if ( $currentServer == "Primary Server" && $template['IconHTTPS'])
		$template['Icon'] = $template['IconHTTPS'];

	$ID = $template['ID'];

	$template['Profile'] = $allRepositories[$template['RepoName']]['profile'];
	$template['ProfileIcon'] = $allRepositories[$template['RepoName']]['icon'];

	// Hack the system so that language's popups always appear in the appropriate language
	if ( $template['Language'] ) {
		$countryCode = $template['LanguageDefault'] ? "en_US" : $template['LanguagePack'];
		if ( $countryCode !== "en_US" ) {
			if ( ! is_file("{$caPaths['tempFiles']}/CA_language-$countryCode") ) {
				download_url("{$caPaths['CA_languageBase']}$countryCode","{$caPaths['tempFiles']}/CA_language-$countryCode");
			}
			$language = is_file("{$caPaths['tempFiles']}/CA_language-$countryCode") ? @parse_lang_file("{$caPaths['tempFiles']}/CA_language-$countryCode") : [];
		} else {
			$language = [];
		}
	}

	$donatelink = $template['DonateLink'];
	if ( $donatelink ) {
		$donatetext = $template['DonateText'];
	}

	if ( ! $template['Plugin'] ) {
		if ( ! strpos($template['Repository'],"/") ) {
			$template['Repository'] = "library/{$template['Repository']}";
		}
		foreach ($dockerRunning as $testDocker) {
			$templateRepo = explode(":",$template['Repository']);
			$testRepo = explode(":",$testDocker['Image']);
			if ($templateRepo[0] == $testRepo[0]) {
				$selected = true;
				$name = $testDocker['Name'];
				break;
			}
		}
	} else
		$pluginName = basename($template['PluginURL']);

	if ( $template['trending'] ) {
		$allApps = readJsonFile($caPaths['community-templates-info']);

		$allTrends = array_unique(array_column($allApps,"trending"));
		rsort($allTrends);
		$trendRank = array_search($template['trending'],$allTrends) + 1;
	}

	$template['Category'] = categoryList($template['Category'],true);
	$template['Icon'] = $template['Icon'] ? $template['Icon'] : "/plugins/dynamix.docker.manager/images/question.png";
	if ( $template['Overview'] )
		$ovr = $template['OriginalOverview'] ?: $template['Overview'];
	if ( ! $ovr )
		$ovr = $template['OriginalDescription'] ?: $template['Description'];
	$ovr = html_entity_decode($ovr);
//	$ovr = str_replace("&#xD","<br>",$ovr);
	$ovr = str_replace(["[","]"],["<",">"],$ovr);
//	$ovr = str_replace("<br>","\n",$ovr);
	$ovr = str_replace("\n","<br>",$ovr);
	$ovr = str_replace("    ","&nbsp;&nbsp;&nbsp;&nbsp;",$ovr);
	$ovr = markdown(strip_tags($ovr,"<br>"));
	$template['display_ovr'] = $ovr;
	
	$template['ModeratorComment'] .= $template['CAComment'];

	if ( $template['Plugin'] ) {
		$templateURL = $template['PluginURL'];
		download_url($templateURL,$caPaths['pluginTempDownload']);
		$template['Changes'] = @plugin("changes",$caPaths['pluginTempDownload']);

		$template['pluginVersion'] = @plugin("version",$caPaths['pluginTempDownload']) ?: $template['pluginVersion'];
		
	} else {
		if ( ! $template['Changes'] && $template['ChangeLogPresent']) {
			$templateURL = $template['caTemplateURL'] ?: $template['TemplateURL'];
			download_url($templateURL,$caPaths['pluginTempDownload']);
			$xml = readXmlFile($caPaths['pluginTempDownload']);
			$template['Changes'] = $xml['Changes'];
			
		}
	}
	@unlink($caPaths['pluginTempDownload']);
	$template['Changes'] = str_replace("    ","&nbsp;&nbsp;&nbsp;&nbsp;",$template['Changes']); // Prevent inadvertent code blocks
	$template['Changes'] = Markdown(strip_tags(str_replace(["[","]"],["<",">"],$template['Changes']),"<br>"));
	if ( trim($template['Changes']) )
		$template['display_changes'] = trim($template['Changes']);
	
	if ( $template['IconFA'] ) {
		$template['IconFA'] = $template['IconFA'] ?: $template['Icon'];
		$templateIcon = startsWith($template['IconFA'],"icon-") ? "{$template['IconFA']} unraidIcon" : "fa fa-{$template['IconFA']}";
		$template['display_icon'] = "<i class='$templateIcon popupIcon'></i>";
	} else
		$template['display_icon'] = "<img class='popupIcon' src='{$template['Icon']}' onerror='this.src=&quot;/plugins/dynamix.docker.manager/images/question.png&quot;'>";

	if ( $template['Requires'] ) {
		$template['Requires'] = Markdown(strip_tags(str_replace(["\r","\n","&#xD;"],["","<br>",""],trim($template['Requires'])),"<br>"));
	}

	$actionsContext = [];
	if ( ! $template['Language'] ) {
		if ( $Displayed && ! $template['NoInstall'] && ! $caSettings['NoInstalls']) {
			if ( ! $template['Plugin'] ) {
				if ( $caSettings['dockerRunning'] ) {
					if ( $selected ) {
						if ( $info[$name]['url'] && $info[$name]['running'] ) {
							$actionsContext[] = array("icon"=>"ca_fa-globe","text"=>"WebUI","action"=>"openNewWindow('{$info[$name]['url']}','_blank');");
						}
						$tmpRepo = strpos($template['Repository'],":") ? $template['Repository'] : $template['Repository'].":latest";
						$tmpRepo = strpos($tmpRepo,"/") ? $tmpRepo : "library/$tmpRepo";
						if ( ! filter_var($dockerUpdateStatus[$tmpRepo]['status'],FILTER_VALIDATE_BOOLEAN) ) {
							$actionsContext[] = array("icon"=>"ca_fa-update","text"=>tr("Update"),"action"=>"updateDocker('$name');");
						}
						if ( $caSettings['defaultReinstall'] == "true" ) {
							if ( $template['BranchID'] )
								$actionsContext[] = array("icon"=>"ca_fa-install","text"=>tr("Install second instance"),"action"=>"displayTags('{$template['ID']}');");
							else
								$actionsContext[] = array("icon"=>"ca_fa-install","text"=>tr("Install second instance"),"action"=>"popupInstallXML('".addslashes($template['Path'])."','second');");
						}
						$actionsContext[] = array("icon"=>"ca_fa-edit","text"=>tr("Edit"),"action"=>"popupInstallXML('".addslashes($info[$name]['template'])."','edit');");
						$actionsContext[] = array("divider"=>true);
						$actionsContext[] = array("icon"=>"ca_fa-delete","text"=>"<span class='ca_red'>".tr("Uninstall")."</span>","action"=>"uninstallDocker('".addslashes($info[$name]['template'])."','{$template['Name']}');");
			
					} else {
						if ( $template['InstallPath'] ) {
							$actionsContext[] = array("icon"=>"ca_fa-install","text"=>tr("Reinstall"),"action"=>"popupInstallXML('".addslashes($template['InstallPath'])."','user');");
							$actionsContext[] = array("divider"=>true);
							$actionsContext[] = array("icon"=>"ca_fa-delete","text"=>"<span class='ca_red'>".tr("Remove from Previous Apps")."</span>","action"=>"removeApp('{$template['InstallPath']}','{$template['Name']}');");
						}
						else {
							if ( ! $template['BranchID'] ) {
								$template['newInstallAction'] = "popupInstallXML('".addslashes($template['Path'])."','default');";
							} else {
								$template['newInstallAction'] = "displayTags('{$template['ID']}');";
							}
						}
					}
				}
			} else {
				if ( file_exists("/var/log/plugins/$pluginName") ) {
					if ( plugin("version","/var/log/plugins/$pluginName") != $template['pluginVersion'] ) {
						@copy($caPaths['pluginTempDownload'],"/tmp/plugins/$pluginName");
						$actionsContext[] = array("icon"=>"ca_fa-update","text"=>tr("Update"),"action"=>"installPlugin('$pluginName',true);");
					}
					$pluginSettings = $pluginName == "community.applications.plg" ? "ca_settings" : plugin("launch","/var/log/plugins/$pluginName");
					if ( $pluginSettings ) {
						$actionsContext[] = array("icon"=>"ca_fa-pluginSettings","text"=>tr("Settings"),"action"=>"openNewWindow('/Apps/$pluginSettings');");
					}
					if ( ! empty($actionsContext) )
						$actionsContext[] = array("divider"=>true);
					$actionsContext[] = array("icon"=>"ca_fa-delete","text"=>"<span class='ca_red'>".tr("Uninstall")."</span>","action"=>"uninstallApp('/var/log/plugins/$pluginName','{$template['Name']}');");
				} else {
					$buttonTitle = $template['InstallPath'] ? tr("Reinstall") : tr("Install");
					$actionsContext[] = array("icon"=>"ca_fa-install","text"=>$buttonTitle,"action"=>"installPlugin('{$template['PluginURL']}');");
					if ( $template['InstallPath'] ) {
						if ( ! empty($actionsContext) )	
							$actionsContext[] = array("divider"=>true);						
						$actionsContext[] = array("icon"=>"ca_fa-delete","text"=>"<span class='ca_red'>".tr("Remove from Previous Apps")."</span>","action"=>"removeApp('{$template['InstallPath']}','$pluginName');");
					}
					if ( count($actionsContext) == 1 ) {
						$template['newInstallAction'] = "installPlugin('{$template['PluginURL']}')";
						unset($actionsContext);
					}
				}
			}
		}
	}
	if ( $template['Language'] ) {
		$dynamixSettings = parse_ini_file($caPaths['dynamixSettings'],true);
		$currentLanguage = $dynamixSettings['display']['locale'] ?: "en_US";
		$installedLanguages = array_diff(scandir("/usr/local/emhttp/languages"),array(".",".."));
		$installedLanguages = array_filter($installedLanguages,function($v) {
			return is_dir("/usr/local/emhttp/languages/$v");
		});
		$installedLanguages[] = "en_US";
		$currentLanguage = (is_dir("/usr/local/emhttp/languages/$currentLanguage") ) ? $currentLanguage : "en_US";
		if ( in_array($countryCode,$installedLanguages) ) {
			if ( $currentLanguage != $countryCode ) {
				$actionsContext[] = array("icon"=>"ca_fa-switchto","text"=>$template['SwitchLanguage'],"action"=>"CAswitchLanguage('$countryCode');");
			}
		} else {
			$actionsContext[] = array("icon"=>"ca_fa-install","text"=>$template['InstallLanguage'],"action"=>"installLanguage('{$template['TemplateURL']}','$countryCode');");
		}
		if ( file_exists("/var/log/plugins/lang-$countryCode.xml") ) {
			if ( languageCheck($template) ) {
				$actionsContext[] = array("icon"=>"ca_fa-update","text"=>$template['UpdateLanguage'],"action"=>"updateLanguage('$countryCode');");
			}
			if ( $currentLanguage != $countryCode ) {
				if ( ! empty($actionsContext) )
					$actionsContext[] = array("divider"=>true);
				$actionsContext[] = array("icon"=>"ca_fa-delete","text"=>"<span class='ca_red'>".tr("Remove Language Pack")."</span>","action"=>"removeLanguage('$countryCode');");
			}
		}
		if ( $countryCode !== "en_US" ) {
			$template['Changes'] = "<center><a href='https://github.com/unraid/lang-$countryCode/commits/master' target='_blank'>".tr("Click here to view the language changelog")."</a></center>";
		} else {
			unset($template['Changes']);
		}
	}

/* 	$installLine .= "<div><a class='appIconsPopUp ca_repository ca_repoFromPopUp' data-repository='".htmlentities($template['RepoName'],ENT_QUOTES)."'> ".tr("Profile")."</a></div>";
	$installLine .= "</div>";
 */
	$supportContext = array();
	if ( $template['Support'] ) 
		$supportContext[] = array("icon"=>"ca_fa-support","link"=>$template['Support'],"text"=> $template['SupportLanguage'] ?: tr("Support"));
	if ( $template['Project'] )
		$supportContext[] = array("icon"=>"ca_fa-project","link"=>$template['Project'],"text"=> tr("Project"));
	if ( $template['Registry'] )
		$supportContext[] = array("icon"=>"ca_fa-docker","link"=>$template['Registry'],"text"=> tr("Registry"));
	if ( $dockerVars['DOCKER_AUTHORING_MODE'] == "yes" )
		$supportContext[] = array("link"=> $template['caTemplateURL'] ?: $template['TemplateURL'],"text"=>tr("Application Template"));

	$author = $template['PluginURL'] ? $template['PluginAuthor'] : $template['SortAuthor'];

/* 	$templateDescription .= "<tr><td>".tr("Repository:")."</td><td>";
	$templateDescription .= "<a class='popUpLink ca_repoSearchPopUp' data-repository='".htmlentities($template['RepoName'],ENT_QUOTES)."'> ";
	$templateDescription .= str_ireplace("Repository","",$template['RepoName']).tr("Repository")."</a>";
	if ( ($template['Repo'] == str_replace("*","'",$caSettings['favourite'])) && $caSettings['favourite'] )
		$templateDescription .= "&nbsp;<span class='ca_favourite' title='".tr("Favourite Repository")."'></span>";

	$templateDescription .= "</td></tr>";
	$templateDescription .= ($template['Private'] == "true") ? "<tr><td></td><td><span class='modComment'>Private Repository</span></td></tr>" : "";
	$templateDescription .= ( $dockerVars['DOCKER_AUTHORING_MODE'] == "yes"  && $templateURL) ? "<tr><td></td><td><a class='popUpLink' href='$templateURL' target='_blank'>".tr("Application Template")."</a></td></tr>" : "";
	if ( $template['Category'] ) {
		$templateDescription .= "<tr><td>".tr("Categories:")."</td><td>".$template['Category'];
		$templateDescription .= "</td></tr>";
	}
	if ( $template['Language'] ) {
		$templateDescription .= "<tr><td>".tr("Language").":</td><td>{$template['Language']}";
		if ( $template['LanguageLocal'] )
			$templateDescription .= " - {$template['LanguageLocal']}";
		$templateDescription .= "</td></tr>";
		$templateDescription .= "<tr><td>".tr("Country Code:")."</td><td>$countryCode</td></tr>";
		if ( ! $countryCode || $countryCode == "en_US" )
			$templateDescription .= "<tr><td></td><td>&nbsp;</td></tr>";
	}
	if ( filter_var($template['multiLanguage'],FILTER_VALIDATE_BOOLEAN) )
		$templateDescription .= "<tr><td>".tr("Multi Language Support")."</td><td>".tr("Yes")."</td></tr>";

	$templateDescription .= $template['stars'] ? "<tr><td nowrap>".tr("DockerHub Stars:")."</td><td><span class='dockerHubStar'></span> ".$template['stars']."</td></tr>" : "";

	if ( $template['FirstSeen'] > 1 && $template['Name'] != "Community Applications" && $countryCode != "en_US")
		$templateDescription .= "<tr><td>".tr("Added to CA:")."</td><td>".tr(date("F",$template['FirstSeen']),0).date(" j, Y",$template['FirstSeen'])."</td></tr>";

	# In this day and age with auto-updating apps, NO ONE keeps up to date with the date updated.  Remove from docker containers to avoid confusion
	if ( $template['Date'] && $template['Plugin'] ) {
		$niceDate = tr(date("F",$template['Date']),0).date(" j, Y",$template['Date']);
		$templateDescription .= "<tr><td nowrap>".tr("Date Updated:")."</td><td>$niceDate</td></tr>";
	}
	if ( $template['Plugin'] ) {
		$template['pluginVersion'] = $template['pluginVersion'] ?: tr("unknown");
		$templateDescription .= "<tr><td nowrap>".tr("Current Version:")."</td><td>{$template['pluginVersion']}</td></tr>";
	}
	if ($template['Language'] && $template['LanguageURL']) {
		$templateDescription .= "<tr><td nowrap>".tr("Current Version:")."</td><td>{$template['Version']}</td></tr>";
		if ( is_file("{$caPaths['installedLanguages']}/dynamix.$countryCode.xml") ) {
			$installedVersion = exec("/usr/local/emhttp/plugins/dynamix.plugin.manager/scripts/language Version /var/log/plugins/lang-$countryCode.xml");
			$templateDescription .= "<tr><td nowrap>".tr("Installed Version:")."</td><td>$installedVersion</td></tr>";
		}
	}
 */
/* 	$unraidVersion = parse_ini_file($caPaths['unRaidVersion']);
	$templateDescription .= ( $template['MinVer'] > "6.4.0" ) ? "<tr><td nowrap>".tr("Minimum OS:")."</td><td>Unraid v".$template['MinVer']."</td></tr>" : "";

	$template['MaxVer'] = $template['MaxVer'] ?: $template['DeprecatedMaxVer'];
	$templateDescription .= $template['MaxVer'] ? "<tr><td nowrap>".tr("Max OS:")."</td><td>Unraid v".$template['MaxVer']."</td></tr>" : "";

	$downloads = getDownloads($template['downloads']);

	$templateDescription .= $template['Licence'] ? "<tr><td>".tr("Licence:")."</td><td>".$template['Licence']."</td></tr>" : "";
	if ( $template['trending'] ) {
		$templateDescription .= "<tr><td>".tr("30 Day Trend:")."</td><td>".sprintf(tr("Ranked #%s"),$trendRank);
		if (is_array($template['trends']) && (count($template['trends']) > 1) ){
			$templateDescription .= "  ".sprintf(tr("Trending %s"), (end($template['trends']) > $template['trends'][count($template['trends'])-2]) ? " <span class='trendingUp'></span>" : " <span class='trendingDown'></span>");
		}
		$templateDescription .= "<tr><td></td><td>".sprintf(tr("(As of %s)"),tr(date("F",$template['LastUpdateScan']),0).date(" j, Y  g:i a",$template['LastUpdateScan']),0)."</td></tr>";
		$templateDescription .= "</td></tr>";
	}
	$templateDescription .= "</table>";

	$templateDescription .= "<div class='ca_center'><span class='popUpDeprecated'>";
	if ($template['Blacklist'])
		$templateDescription .= tr("This application / template has been blacklisted")."<br>";

	if ($template['Deprecated'])
		$templateDescription .= tr("This application / template has been deprecated")."<br>";

	if ( !$template['Compatible'] )
		$templateDescription .= tr("This application is not compatible with your version of Unraid")."<br>";

	$templateDescription .= "</span></div>";
	$templateDescription .= "<div class='ca_hr'></div>";
	if ( ! $Displayed )
		$templateDescription .= "<div><span class='ca_fa-warning warning-yellow'></span>&nbsp; <font size='1'>".tr("Another browser tab or device has updated the displayed templates.  Some actions are not available")."</font></div>";


	$templateDescription .= $template['ModeratorComment'] ? "<br><br><span class='ca_bold modComment'>".tr("Moderator Comments:")."</span> ".$template['ModeratorComment'] : "";
	$templateDescription .= "</p><br><div class='ca_center'>";

 
/* 	$templateDescription .= "</div>";
	if ($template['Plugin']) {
		$dupeList = readJsonFile($caPaths['pluginDupes']);
		if ( $dupeList[basename($template['Repository'])] == 1 ){
			$allTemplates = readJsonFile($caPaths['community-templates-info']);
			foreach ($allTemplates as $testTemplate) {
				if ($testTemplate['Repository'] == $template['Repository']) continue;

				if ($testTemplate['Plugin'] && (basename($testTemplate['Repository']) == basename($template['Repository'])))
					$duplicated .= $testTemplate['Author']." - ".$testTemplate['Name'];
			}
			$templateDescription .= "<br>".sprintf(tr("This plugin has a duplicated name from another plugin %s.  This will impact your ability to install both plugins simultaneously"),$duplicated)."<br>";
		}
	} */
	if (is_array($template['trends']) && (count($template['trends']) > 1) ){
		if ( $template['downloadtrend'] ) {
			$templateDescription .= "<div><canvas id='trendChart{$template['ID']}' class='caChart' height=1 width=3></canvas></div>";
			$templateDescription .= "<div><canvas id='downloadChart{$template['ID']}' class='caChart' height=1 width=3></canvas></div>";
			$templateDescription .= "<div><canvas id='totalDownloadChart{$template['ID']}' class='caChart' height=1 width=3></canvas></div>";
		}
	}
	if ( ! $countryCode ) {
		$changeLogMessage = "Note: not all ";
		$changeLogMessage .= $template['PluginURL'] || $template['Language'] ? "authors" : "maintainers";
		$changeLogMessage .= " keep up to date on change logs<br>";
		$template['display_changelogMessage'] = tr($changeLogMessage);
	}
	
	if (is_array($template['trendsDate']) ) {
		array_walk($template['trendsDate'],function(&$entry) {
			$entry = tr(date("M",$entry),0).date(" j",$entry);
		});
	}

	if ( is_array($template['trends']) ) {
		if ( count($template['trends']) < count($template['downloadtrend']) )
			array_shift($template['downloadtrend']);

		$chartLabel = $template['trendsDate'];
		if ( is_array($template['downloadtrend']) ) {
			#get what the previous download value would have been based upon the trend
			$minDownload = intval(  ((100 - $template['trends'][0]) / 100)  * ($template['downloadtrend'][0]) );
			foreach ($template['downloadtrend'] as $download) {
				$totalDown[] = $download;
				$down[] = intval($download - $minDownload);
				$minDownload = $download;
			}
			$downloadLabel = $template['trendsDate'];
		}
		$down = is_array($down) ? $down : array();
	}
	
	if ( $pinnedApps["{$template['Repository']}&{$template['SortName']}"] ) {
		$template['pinned'] = "pinned";
		$template['pinnedTitle'] = tr("Click to unpin this application");
	} else {
		$template['pinned'] = "unpinned";
		$template['pinnedTitle'] = tr("Click to pin this application");
	}
	$template['actionsContext'] = $actionsContext;
	$template['supportContext'] = $supportContext;
/* 	$templateDescription = "<div class='popupHolder'>$templateDescription<br><br><br><br><br><br><br><br><br></div>";
 */	@unlink($caPaths['pluginTempDownload']);
/* 	return array("description"=>$templateDescription,;
 */
 return array("description"=>displayPopup($template),"trendData"=>$template['trends'],"trendLabel"=>$chartLabel,"downloadtrend"=>$down,"downloadLabel"=>$downloadLabel,"totaldown"=>$totalDown,"totaldownLabel"=>$downloadLabel,"supportContext"=>$supportContext,"actionsContext"=>$actionsContext);
}

#####################################
# Generate the display for the repo #
#####################################
function getRepoDescriptionSkin($repository) {
	global $caSettings, $caPaths, $language;

	$dockerVars = parse_ini_file($caPaths['docker_cfg']);
	$repositories = readJsonFile($caPaths['repositoryList']);
	$templates = readJsonFile($caPaths['community-templates-info']);
	$repo = $repositories[$repository];
	$repo['icon'] = $repo['icon'] ?: "/plugins/dynamix.docker.manager/images/question.png";

	$t .= "<div class='popUpClose'>".tr("CLOSE")."</div>";
	$t .= "<div class='popUpBack'>".tr("BACK")."</div>";
	$t .= "<div class='popupTitle'>$repository</div>";
	$t .= "<div class='ca_hr'></div>";
	$t .= "<div class='popupIconArea ca_center'><img class='popupIcon' src='{$repo['icon']}' onerror='this.src=&quot;/plugins/dynamix.docker.manager/images/question.png&quot;'></div>";
	$repo['bio'] = $repo['bio'] ? markdown($repo['bio']) : "<br><center>".tr("No description present");
	$t .= "<div class='popupDescriptionArea ca_center'><br>".strip_tags($repo['bio'])."</div>";

	if ( $repo['DonateLink'] ) {
		$t .= "<div style='float:right;text-align:right;'><font size=0.75rem;>$donateText</font>&nbsp;&nbsp;<a class='popup-donate donateLink' href='{$repo['DonateLink']}' target='_blank'>".tr("Donate")."</a></div><br><br>";
	} else {
		$t .= "<br><br>";
	}

	$t .= "<div class='ca_hr'></div>";

	if ( $caSettings['favourite'] == $repository )
		$t .= "<div class='ca_center'><span class='ca_favouriteRepo appIconsPopUp'> ".tr("Favourite Repository")."</span></div>";
	else
		$t .= "<div id='favMsg' class='ca_center'><span class='ca_non_favouriteRepo appIconsPopUp favPopup' data-repository='".htmlentities($repository,ENT_QUOTES)."'> ".tr("Set as favourite repository")."</span></div>";

	$installLine = "<div style='display:flex;flex-wrap:wrap;justify-content:center;width:90%;margin-left:5%;'>";
	$installLine .= "<div><a class='appIconsPopUp ca_repoSearchPopUp ca_showRepo' data-repository='".htmlentities($repository,ENT_QUOTES)."'> Search Apps</a></div>";
	if ( $repo['WebPage'] )
		$installLine .= "<div><a class='appIconsPopUp ca_webpage' href='{$repo['WebPage']}' target='_blank'> ".tr("Web Page")."</a></div>";
	if ( $repo['Forum'] )
		$installLine .= "<div><a class='appIconsPopUp ca_forum' href='{$repo['Forum']}' target='_blank'> ".tr("Forum")."</a></div>";
	if ( $repo['profile'] )
		$installLine .= "<div><a class='appIconsPopUp ca_profile' href='{$repo['profile']}' target='_blank'> ".tr("Forum Profile")."</a></div>";
	if ( $repo['Facebook'] )
		$installLine .= "<div><a class='appIconsPopUp ca_facebook' href='{$repo['Facebook']}' target='_blank'> ".tr("Facebook")."</a></div>";
	if ( $repo['Reddit'] )
		$installLine .= "<div><a class='appIconsPopUp ca_reddit' href='{$repo['Reddit']}' target='_blank'> ".tr("Reddit")."</a></div>";
	if ( $repo['Twitter'] )
		$installLine .= "<div><a class='appIconsPopUp ca_twitter' href='{$repo['Twitter']}' target='_blank'> ".tr("Twitter")."</a></div>";
	if ( $repo['Discord'] ) {
			$installLine .= "<div><a class='appIconsPopUp ca_discord_popup' target='_blank' href='{$repo['Discord']}' target='_blank'> ".tr("Discord")."</a></div>";
	}

	
	$t .= "$installLine</div>";

	$totalApps = $totalPlugins = $totalDocker = $totalDownloads = 0;
	foreach ($templates as $template) {
		if ( $template['RepoName'] !== $repository ) continue;
		if ( $template['BranchID'] ) continue;

		if ( $template['Blacklist'] ) continue;
		if ( $template['Deprecated'] && $caSettings['hideDeprecated'] !== "false" ) continue;
		if ( ! $template['Compatible'] && $caSettings['hideIncompatible'] !== "false" ) continue;

		if ( $template['Registry'] ) {
			$totalDocker++;
			if ( $template['downloads'] ) {
				$totalDownloads = $totalDownloads + $template['downloads'];
				$downloadDockerCount++;
			}
		}
		if ( $template['PluginURL'] ) {
			$totalPlugins++;
		}
		if ( $template['Language'] ) {
			$totalLanguage++;
		}

		$totalApps++;
	}
	$t .= "<div class='ca_hr'></div>";
	$t .= "<div>";
	$t .= "<table style='margin-top:15px;width:60%;margin-left:105px;'>";
	if ( $repo['FirstSeen'] > 1 )
	$t .= "<tr><td style='width:50%;'>".tr("Added to CA")."</td><td style='width:30%;text-align:right;'>".date("F j, Y",$repo['FirstSeen'])."</td></tr>";
	$t .= "<tr><td style='width:50%;'>".tr("Total Docker Applications")."</td><td style='width:30%;text-align:right;'>$totalDocker</td></tr>";
	$t .= "<tr><td style='width:50%;'>".tr("Total Plugin Applications")."</td><td style='width:30%;text-align:right;'>$totalPlugins</td></tr>";
	if ( $totalLanguage )
		$t .= "<tr><td style='width:50%;'>".tr("Total Languages")."</td><td style='width:30%;text-align:right;'>$totalLanguage</td></tr>";
	if ($dockerVars['DOCKER_AUTHORING_MODE'] == "yes")
		$t .= "<tr><td style='width:50%;'><a class='popUpLink' href='{$repo['url']}' target='_blank'>".tr("Repository URL")."</a></td></tr>";

	$t .= "<tr><td style='width:50%;'>".tr("Total Applications")."</td><td style='width:30%;text-align:right;'>$totalApps</td></tr>";

	if ( $downloadDockerCount && $totalDownloads ) {
		$avgDownloads = intval($totalDownloads / $downloadDockerCount);
		$t .= "<tr><td>".tr("Total Known Downloads")."</td><td style='text-align:right;'>".number_format($totalDownloads)."</td></tr>";
		$t .= "<tr><td>".tr("Average Downloads Per App")."</td><td style='text-align:right;'>".number_format($avgDownloads)."</td></tr>";
	}
	$t .= "</table>";
	$t .= "</div>";



	$t = "<div class='popupHolder'>$t</div>";
	return array("description"=>$t);
}

###########################
# Generate the app's card #
###########################
function displayCard($template) {
	global $caSettings;

	$appName = str_replace("-"," ",$template['display_dockerName']);

	if ( $template['ca_fav'] )
		$holder .= " ca_holderFav";


	$popupType = $template['RepositoryTemplate'] ? "ca_repoPopup" : "ca_appPopup";
	if ( $template['Category'] == "Docker Hub Search" )
		unset($popupType);

	if ($template['Language']) {
		$language = "{$template['Language']}";
		$language .= $template['LanguageLocal'] ? " - {$template['LanguageLocal']}" : "";
		$template['Category'] = "";
	}

	extract($template);
	
	$appType = $Plugin ? "appPlugin" : "appDocker";
	$appType = $Language ? "appLanguage": $appType;
	$appType = (strpos($Category,"Drivers") !== false) && $Plugin ? "appDriver" : $appType;	
	$appType = $RepositoryTemplate ? "appRepository" : $appType;
	
	$Category = explode(" ",$Category)[0];
	$Category = explode(":",$Category)[0];

	$author = $RepoShort ?: $RepoName;
	if ( $Plugin )
		$author = $Author;
	if ( $Language )
		$author = "Unraid";
	

	if ( !$RepositoryTemplate ) {
		$cardClass = "ca_appPopup";
		$supportContext = array();
		if ( $template['Support'] ) 
			$supportContext[] = array("icon"=>"ca_fa-support","link"=>$template['Support'],"text"=> $template['SupportLanguage'] ?: tr("Support"));
		if ( $template['Project'] )
			$supportContext[] = array("icon"=>"ca_fa-project","link"=>$template['Project'],"text"=> tr("Project"));

/* 		if ( $supportContext && count($supportContext) == 1 ) {
			$supportLink = $supportContext[0]['link'];
			$supportText = $supportContext[0]['text'];
		} */
	} else {
		$cardClass = "ca_repoinfo";
		$ID = $RepoName;
		$supportContext = array();
		if ( $profile ) 
			$supportContext[] = array("icon"=>"ca_profile","link"=>$profile,"text"=>tr("Profile"));
		if ( $Forum )
			$supportContext[] = array("icon"=>"ca_forum","link"=>$Forum,"text"=>tr("Forum"));
		if ( $Twitter )
			$supportContext[] = array("icon"=>"ca_twitter","link"=>$Twitter,"text"=>tr("Twitter"));
		if ( $Reddit )
			$supportContext[] = array("icon"=>"ca_reddit","link"=>$Reddit,"text"=>tr("Reddit"));
		if ( $Facebook )
			$supportContext[] = array("icon"=>"ca_facebook","link"=>$Facebook,"text"=>tr("Facebook"));
		if ( $Discord ) 
			$supportContext[] = array("icon"=>"ca_discord","link"=>$Discord,"text"=>tr("Discord"));
		if ( $WebPage )
			$supportContext[] = array("icon"=>"ca_webpage","link"=>$WebPage,"text"=>tr("Web Page"));
	}
	
	$display_repoName = str_replace("' Repository","",str_replace("'s Repository","",$display_repoName));
	
	$card .= "
		<div class='ca_holder'>
		<div class='ca_bottomLine'>
				<span class='infoButton $cardClass' data-apppath='$Path' data-appname='$Name' data-repository='".htmlentities($RepoName,ENT_QUOTES)."'>".tr("Info")."</span>
		";
	
	if ( count($supportContext) == 1)
		$card .= "<span class='supportButton'><a href='{$supportContext[0]['link']}' target='_blank'>{$supportContext[0]['text']}</a></span>";
	elseif (!empty($supportContext))
		$card .= "
			<span class='supportButton supportButtonCardContext' id='support$ID' data-context='".json_encode($supportContext)."'>".tr("Support")."</span>";
	
	$card .= "
			<span class='$appType'></span>
	";
	
	if ($Removable && !$DockerInfo) {
		$previousAppName = $Plugin ? $PluginURL : $Name;
		$type = ($appType == "appDocker") ? "docker" : "plugin";
		$card .= "<input class='ca_multiselect ca_tooltip' title='".tr("Check off to select multiple reinstalls")."' type='checkbox' data-name='$previousAppName' data-humanName='$Name' data-type='$type' data-deletepath='$InstallPath' $checked>";
	}
	$card .= "</div>";
	$card .= "<div class='$cardClass ca_backgroundClickable' data-apppath='$Path' data-appname='$Name' data-repository='".htmlentities($RepoName,ENT_QUOTES)."'>";
	$card .= "<div class='ca_iconArea'>";
	if ( ! $IconFA ) 
		$card .= "
			<img class='ca_displayIcon'src='$Icon'></img>
		";
	else {
		$displayIcon = $template['IconFA'] ?: $template['Icon'];
		$displayIconClass = startsWith($displayIcon,"icon-") ? $displayIcon : "fa fa-$displayIcon";
		$card  .= "<i class='ca_appPopup $displayIconClass displayIcon' data-apppath='$Path' data-appname='$Name'></i>";
	}
	$card .= "</div>";


	$card .= "
				<div class='ca_applicationName'>$Name</div>
				<div class='ca_author'>$author</div>
				<div class='cardCategory'>$Category</div>
	";

	$card .= "
		</div>
		";
	$card .= "</div>";
	if ( $Beta || $RecommendedDate ) {
		$card .= "<div class='betaCardBackground'>";
		if ( $Beta ) 
			$card .= "<div class='betaPopupText'>".tr("BETA")."</div>";
		else
			$card .= "<div class='spotlightPopupText'></div>";
		$card .= "</div>";
	}
	return str_replace(["\t","\n"],"",$card);
}

function displayPopup($template) {
	extract($template);
		
	$RepoName = str_replace("' Repository","",str_replace("'s Repository","",$Repo));
	if ( $RepoShort ) $RepoName = $RepoShort;
	
	$FirstSeen = ($FirstSeen < 1433649600 ) ? 1433000000 : $FirstSeen;
	$DateAdded = date("M j, Y",$FirstSeen);
	
	$card = "
		<div class='popup'>
		<div><span class='popUpClose'>".tr("CLOSE")."</span></div>
		<div class='ca_popupIconArea'>
			<div class='popupIcon'>$display_icon</div>
			<div class='popupInfo'>
				<div class='popupName'>$Name</div>
		";
		if ( ! $Language )
			$card .= "<div class='popupAuthorMain'>$Author</div>";
		
		if ( $actionsContext ) {
			$card .= "
				<div class='actionsPopup' id='actionsPopup'>".tr("Actions")."</div>
			";
		}
		if ( $newInstallAction ) {
			$card .= "
				<div class='actionsPopup'><span onclick=$newInstallAction><span class='ca_fa-install'> ".tr("Install")."</span></span></div>
			";
		}
		if ( count($supportContext) == 1 )
			$card .= "<div class='supportPopup'><a href='{$supportContext[0]['link']}' target='_blank'><span class='{$supportContext[0]['icon']}'>{$supportContext[0]['text']}</span></a></div>";
		else
			$card.= "<div class='supportPopup' id='supportPopup'><span class='ca_fa-support'> ".tr("Support")."</div>";

		$card .= $LanguagePack != "en_US" ? "<div class='$pinned' style='display:inline-block' title='$pinnedTitle' data-repository='$Repository' data-name='$SortName'></div>" : "";
		$card .= "
			</div>
		</div>
		<div class='popupDescription popup_readmore'>$display_ovr</div>
	";
	if ( $Requires ) 
		$card .= "<div class='additionalRequirementsHeader'>".tr("Additional Requirements")."</div><div class='additionalRequirements'>{$template['Requires']}</div>";
	
	if ( $Deprecated ) 
		$ModeratorComment .= "<br>".tr("This application template has been deprecated");
	if ( ! $Compatible && ! $UnknownCompatible )
		$ModeratorComment .= "<br>".tr("This application is not compatible with your version of Unraid");
	if ( $Blacklist )
		$ModeratorComment .= "<br>".tr("This application template has been blacklisted");
	if ( ! $Displayed )
		$ModeratorComment .= "<br>".tr("Another browser tab or device has updated the displayed templates.  Some actions are not available");
	$ModeratorComment .= $caComment;
	if ( $Language && $countryCode !== "en_US" ) {
		$ModeratorComment .= "<a href='$disclaimLineLink' target='_blank'>$disclaimLine1</a>";
	}

	if ( $ModeratorComment ) {
		$card .= "<div class='modComment'><div class='moderatorCommentHeader'> ".tr("Attention:")."</div><div class='moderatorComment'>$ModeratorComment</div></div>";
	}
	
	if ( $RecommendedReason) {
		$RecommendedLanguage = $_SESSION['locale'] ?: "en_US";
		if ( ! $RecommendedReason[$RecommendedLanguage] )
			$RecommendedLanguage = "en_US";
		
		if ( ! $RecommendedWho ) $RecommendedWho = tr("Unraid Staff");
		$card .= "
			<div class='spotlightPopup'>
				<div class='spotlightIconArea'>
					<div><img class='spotlightIcon' src='https://craftassets.unraid.net/uploads/logos/unraid-stacked-dark.svg'></img></div>
				</div>
				<div class='spotlightInfoArea'>
					<div class='spotlightHeader'>".sprintf(tr("Application Spotlight %s"),tr(date("F, Y",$RecommendedDate),0))."</div>
					<div class='spotlightWhy'>".tr("Why we picked it")."</div>
					<div class='spotlightMessage'>{$RecommendedReason[$RecommendedLanguage]}</div>
					<div class='spotlightWho'>- $RecommendedWho</div>
				</div>
			</div>
		";
	}
	$card .= "
		<div>
		<div class='popupInfoSection'>
			<div class='popupInfoLeft'>
			<div class='rightTitle'>".tr("Details")."</div>
			<table style='display:initial;'>
				<tr><td class='popupTableLeft'>".tr("Categories")."</td><td class='popupTableRight'>$Category</td></tr>
				<tr><td class='popupTableLeft'>".tr("Added")."</td><td class='popupTableRight'>$DateAdded</td></tr>
	";
	if ($downloadText)
		$card .= "<tr><td class='popupTableLeft'>".tr("Downloads")."</td><td class='popupTableRight'>$downloadText</td></tr>";
	if (!$Plugin && !$LanguagePack)
		$card .= "<tr><td class='popupTableLeft'>".tr("Repository")."</td><td class='popupTableRight'>$Repository</td></tr>";
	if ( $Plugin ) {
		if ( $MinVer )
			$card .= "<tr><td class='popupTableLeft'>".tr("Min OS")."</td><td class='popupTableRight'>$MinVer</td></tr>";
		if ( $MaxVer )
			$card .= "<tr><td class='popupTableLeft'>".tr("Max OS")."</td><td class='popupTableRight'>$MaxVer</td></tr>";
	}
	$card .=
	"
				</table>
	";
	$card .= "
		</div>
		<div class='popupInfoRight'>
				<div class='popupAuthorTitle'>".($Plugin ? tr("Author") : tr("Maintainer"))."</div>
				<div><div class='popupAuthor'>".($Plugin ? $Author : $RepoName)."</div>
				<div class='popupAuthorIcon'><img class='popupAuthorIcon' src='$ProfileIcon' onerror='this.src=&quot;/plugins/dynamix.docker.manager/images/question.png&quot;'></img></div>
				</div>
				<div class='ca_repoSearchPopUp popupProfile' data-repository='".htmlentities($Repo,ENT_QUOTES)."'>".tr("All Apps")."</div>
				<div class='repoPopup' data-repository='".htmlentities($Repo,ENT_QUOTES)."'>".tr("Profile")."</div>
	";

	if ( $DonateLink ) {
		$card .= "
			<div class='donateText'>$DonateText</div>
			<div class='donateDiv'><span class='donate'><a href='$DonateLink' target='_blank'>".tr("Donate")."</a></span></div>
		";
	}
	$downloadText = getDownloads($downloads);
	$card .= "
			</div>
		</div>
		</div>
	";
				
	if (is_array($trends) && (count($trends) > 1) ){
		if ( $downloadtrend ) {
			$card .= "
				<div class='charts chartTitle'>Trends</div>
				<div><span class='charts'>Show: <span class='chartMenu selectedMenu' data-chart='trendChart'>".tr("Trend Per Month")."</span><span class='chartMenu' data-chart='downloadChart'>".tr("Downloads Per Month")."</span><span class='chartMenu' data-chart='totalDownloadChart'>".tr("Total Downloads")."</span></div>
				<div>
				<div><canvas id='trendChart' class='caChart' height=1 width=3></canvas></div>
				<div><canvas id='downloadChart' class='caChart' style='display:none;' height=1 width=3></canvas></div>
				<div><canvas id='totalDownloadChart' class='caChart' style='display:none;' height=1 width=3></canvas></div>
				</div>
			";
		}
	}
	if ( $display_changes ) {
		$card .= "
			<div class='changelogTitle'>".tr("Change Log")."</div>
			<div class='changelogMessage'>$display_changelogMessage</div>
			<div class='changelog popup_readmore'>$display_changes</div>
		";
	}
	if ( $Beta || $Recommended) {
		$card .= "
			<div class='betaPopupBackground'>
		";
		if ( $Beta )
			$card .= "<div class='betaPopupText'>".tr("BETA")."</div></div>";
		else
			$card .= "<div class='spotlightPopupText'></div>";
		
	}
	$card .= "</div>";

	return $card;
}
?>
