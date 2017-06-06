<?php
if(!isset($_GET['id']))
{
	header("location:index.php");
}

require_once($_SERVER['DOCUMENT_ROOT'].'/tandl/elearning/exemplars/_publicBootstrap.php');
require_once('_exemplar_controller.php');

$select = $ex->select()->published()->joinedRefs()->byPrimary($_GET['id']);
$row = $ex->fetchRow($select);

if(!$row) header("location:".BASE."/exemplars/");

$schools = $row->getSchools();
$themes = $row->getThemes();
$services = $row->getServices();
$courses = $row->getCourses();

$artefacts = $row->getArtefacts();


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><!-- InstanceBegin template="/Templates/page-content-twocolumn-leftsearch.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1" />
	<meta name="DC.Relation.isVersionOf" content="Templates_Version_1.2" />
	<link rel="SHORTCUT ICON" href="http://www.manchester.ac.uk/medialibrary/images/corporate/favicon.ico" />
	<link href="../../css/_base.css" type="text/css" rel="stylesheet" media="screen" />
	<link href="../../css/_print.css" type="text/css" rel="stylesheet" media="print" />
	<style type="text/css" media="screen">@import url("../../css/_import.css");</style>
	<style type="text/css" media="screen">@import url("../../css/_devolved.css");</style>
    <link rel="stylesheet" type="text/css" href="http://assets.manchester.ac.uk/phase1/patch/css/header-patch-1.0.css"/> 
	<script src="http://www.manchester.ac.uk/medialibrary/javascript/navigation.js" type="text/javascript"></script>
	<!-- InstanceBeginEditable name="page-title" -->
	<title>Humanities eLearning - Blended Learning Examples</title>
    <script type="text/javascript" src="/tandl/js/dojo/dojo/dojo.js" djConfig="parseOnLoad:true" ></script>
	<script src="/tandl/js/functions.js" type="text/javascript"></script>
    <script src="exemplar.js" type="text/javascript"></script>
    
	<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="page-meta-description" -->
	<meta name="Description" content="" />
	<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="page-meta-keywords" -->
	<meta name="Keywords" content="eLearning, exemplars, examples, blended learning" />
	<!-- InstanceEndEditable -->
	<!-- BEGIN SECTION SELECT OPTIONAL AREAS TAGS-->	
	<!-- InstanceParam name="nav-primary-selected-ithelp" type="boolean" value="false" -->
	<!-- InstanceParam name="nav-primary-selected-undergraduate" type="boolean" value="false" -->
	<!-- InstanceParam name="nav-primary-selected-postgraduate" type="boolean" value="false" -->
	<!-- InstanceParam name="nav-primary-selected-qanda" type="boolean" value="false" -->
	<!-- InstanceParam name="nav-primary-selected-elearning" type="boolean" value="true" -->
	<!-- InstanceParam name="nav-primary-selected-news" type="boolean" value="false" -->
	<!-- InstanceParam name="nav-primary-selected-funding" type="boolean" value="false" -->
	<!-- InstanceParam name="nav-primary-selected-atoz" type="boolean" value="false" -->
	<!-- InstanceParam name="nav-primary-selected-hnap" type="boolean" value="false" -->
	<!-- InstanceParam name="nav-primary-selected-gta" type="boolean" value="false" -->
	<!-- InstanceParam name="nav-primary-selected-resources" type="boolean" value="false" -->
	<!-- END OPTIONAL AREAS TAGS-->
</head>
<body class="page-content-twocolumn" id="ictoffice">

	<div><a name="pagetop"></a></div>	
	
<!-- #BeginLibraryItem "/Library/nav-skiplinks.lbi" --><div class="nav-skiplinks"><a href="#nav-skiplink-target-content">Skip to main content</a>&nbsp;<a href="#nav-skiplink-target-navigation">Skip to navigation</a></div><!-- #EndLibraryItem --><!-- #BeginLibraryItem "/Library/nav-logo.lbi" -->
<div id="logo"><a href="http://www.manchester.ac.uk/"><img src="http://assets.manchester.ac.uk/logos/university-1.png" alt="The University of Manchester, established in 1824."/></a></div>
<!-- #EndLibraryItem --><!-- #BeginLibraryItem "/Library/page-toolbar.lbi" --><div id="toolbar"><p class="universityhome">[<a href="http://www.manchester.ac.uk/" title="The University of Manchester's home page">University&nbsp;home</a>]</p>
<form id="quicklinks" action="http://www.manchester.ac.uk/cgi-bin/jumpto.pl" method="post">
<p>&nbsp;<label class="hidden" for="selectquicklinks">Quicklinks</label>
  <select id="selectquicklinks" name="jumpto">
    <option value="http://www.humanities.manchester.ac.uk/" selected>Choose a Quick Link</option>
    <option value="http://www.humanities.manchester.ac.uk/aboutus/staff/">Who's who</option>
    <option value="http://www.humanities.manchester.ac.uk/aboutus/maps/">Maps and travel</option>
    <option value="http://ict.humanities.manchester.ac.uk/">Humanities ICT Office</option>
    <option value="http://www.humanities.manchester.ac.uk/humnet/">HumNet</option>
    <option value="http://www.humanities.manchester.ac.uk/tandl/">Humanities Teaching &amp; Learning</option>
  </select>
  </select>
<input type="submit" id="quicklinksgo" value="Go" alt="Quick links" title="Quick links" />
</p>
</form>
<form action="/tandl/resources/search/index.php" method="get" id="search">
<p class="googlesearch">&nbsp;<a name="nav-skiplink-target-search"></a><label class="hidden" for="q">Search</label>
<input type="text" value="Search Humanities" id="q" name="q" onfocus="CheckOnEnter(this,'Search ICT Office')" onblur="CheckOnLeave(this,'Search Humanities')" />
<input type="submit" value="Go" id="searchgo" alt="Search Humanities" title="Search Humanities" />
<input type="hidden" name="client" value="HUM" />
<input type="hidden" name="site" value="HUM" />
</p></form></div><!-- #EndLibraryItem --><div id="sitename"><!-- #BeginLibraryItem "/Library/page-sitename.lbi" --><h1><a href="http://www.manchester.ac.uk/humanities/tandl/" title="Teaching and learning Home Page">Faculty of Humanities Teaching &amp; Learning Office</a></h1>
<!-- #EndLibraryItem --><span></span></div>
	<div class="nav-skiplink-target"><a name="nav-skiplink-target-navigation"></a></div>

	<div id="nav-primary" class="elearning"><!-- #BeginLibraryItem "/Library/nav-primary.lbi" --><ul>
	<li id="policy&procedure"><a href="http://www.humanities.manchester.ac.uk/tandl/policyandprocedure/" title="Policy & Procedure">Policy &amp; Procedure </a></li>
	<li id="quality_assurance"><a href="http://www.humanities.manchester.ac.uk/tandl/qa/" title="Quality Assurance ">Quality Assurance </a></li>
	<li id="elearning"><a href="http://www.humanities.manchester.ac.uk/tandl/elearning/" title="eLearning developments, guidance and resources">eLearning</a></li>
	<li id="news"><a href="http://www.humanities.manchester.ac.uk/tandl/news/" title="News and Events">News &amp; Events</a></li>

  <li id="resources"><a href="http://www.humanities.manchester.ac.uk/tandl/resources/" title="Teaching and learning resources">Resources</a></li>
    <li id="hnap"><a href="http://www.humanities.manchester.ac.uk/tandl/hnap/" title="Humanities New Academics Programme">New Academics</a></li>
<li id="gta"><a href="http://www.humanities.manchester.ac.uk/tandl/gta/" title="Graduate Teaching Assistants">GTAs</a></li>
	<li id="about_us"><a href="http://www.humanities.manchester.ac.uk/tandl/about_us/t&l_team/" title="About Us">About Us</a></li>
</ul>
<!-- #EndLibraryItem --></div>
	
	
	<div id="main">
		
	  <div id="nav-secondary"><!-- InstanceBeginEditable name="nav-sectionmenu" --><!-- #BeginLibraryItem "/Library/nav-sectionmenu-tandl-ele.lbi" --><ul>
  <li class="nav-secondary-breadcrumb"><a href="/tandl/">Teaching and Learning Office</a></li>
  <li class="nav-secondary-sectionheader"><a href="/tandl/elearning/">eLearning</a>
	  <ul class="nav-secondary-sectionmenu">
      <li><a href="/tandl/elearning/intro.html">What is eLearning?</a></li>
          <li><a href="/tandl/elearning/blackboard_9/">Blackboard 9</a></li>
      <li><a href="/tandl/elearning/training/">Training and Development</a></li>
    <!--  <li><a href="/tandl/elearning/essentials/">eLearning Essentials</a></li> -->
      <li><a href="/tandl/elearning/exemplars/">Blended Learning Examples</a></li>
      <li><a href="/tandl/elearning/services/">Support and Services</a></li>
     <li><a href="/tandl/elearning/resources/">eLearning Resources</a></li>
      <li><a href="/tandl/elearning/team/">About the Team</a></li>
      <li><a href="/tandl/elearning/contact.html" >Contact us</a></li>
	  </ul>
  </li>
</ul>
<!-- #EndLibraryItem --><!-- InstanceEndEditable -->


<h3><!-- InstanceBeginEditable name="nav-search-title" -->Search Examples:<!-- InstanceEndEditable --></h3>
<!-- InstanceBeginEditable name="nav-search-form" -->
	<div class="nav-search-form" >
		<?php echo $searchForm; ?>
	</div>
<!-- InstanceEndEditable -->
</div>
	<div class="nav-skiplink-target"><a name="nav-skiplink-target-content"></a></div>
	<div id="content">
	<h1><!-- InstanceBeginEditable name="page-header" -->Blended Learning Examples<!-- InstanceEndEditable --></h1>
		<!-- InstanceBeginEditable name="page-content" -->
		<?php $image = $row->getImage(); ?>

		<div class="exemplar-thumb" style="float:left; margin-top:20px; width:110px;" >
			<?php if($image->hasImage()) { ?>
			<a href="<?php echo $image->getWeb(); ?>" target="_blank" ><img src="<?php echo $image->getThumb(); ?>" alt="<?php echo $row->getTitle(); ?>" /></a>
			<?php } else { ?>
			<img src="<?php echo $image->getThumb(); ?>" alt="<?php echo $row->getTitle(); ?>" />
			<?php } ?>
		</div>

		<div class="exemplar-detail" style="margin:0; margin-left:120px; " >
			<h2><?php echo $row->getTitle(); ?></h2>
			
			<?php if(count($artefacts) > 0) { ?>
			<div class="right-assets-box" id="exemplar-artefacts" >
				<h3 class="heading" >See it in action:</h3>
				<ul class="micro-thumb-list" >
				<?php foreach($artefacts as $artefact) { ?>
					<li class="exemplar-artefact" >
						<?php echo $view->ArtefactRender($artefact); ?>
					</li>
				<?php } ?>
				</ul>
			</div>
			<?php } ?>
            
            <div class="exemplar-info" >
				<dl>
				<?php
                if(count($schools) > 0) { ?>
					<dt><?php echo count($schools) > 1 ? 'Schools' : 'School'; ?></dt>
					<dd><?php
						$schoolsStringArray = array();
						foreach($schools as $school)
						{
							$schoolsStringArray[] = "<a href=\"index.php?{$searchFlag}school={$school->getID()}\" >{$school->getTitle()}</a>";
						}
					echo implode(', ', $schoolsStringArray);
					?>
					</dd>
				<?php } ?>
                
                <?php
                if(count($courses) > 0) { ?>
					<dt><?php echo count($courses) > 1 ? 'Courses' : 'Course'; ?></dt>
					<dd><?php
						$courseStringArray = array();
						foreach($courses as $course)
						{
							$courseStringArray[] = $course->getTitle();
						}
					echo implode(', ', $courseStringArray);
					?>
					</dd>
				<?php } ?>
                
				<?php
				if(count($themes) > 0) { ?>
					<dt><?php echo count($themes) > 1 ? 'Themes' : 'Theme'; ?></dt>
					<dd><?php
					$themesStringsArray = array();
					foreach($themes as $theme)
					{
						$themesStringsArray[] = "<a href=\"index.php?{$searchFlag}theme={$theme->getID()}\" >{$theme->getTitle()}</a>";
					}
					echo implode(', ', $themesStringsArray);
					?>
					</dd>
					<?php } ?>
					<?php
					if(count($services) > 0) { ?>
					<dt><?php echo count($services) > 1 ? 'Services' : 'Service'; ?></dt>
					<dd><?php
					$serviceStringsArray = array();
					foreach($services as $service)
					{
						$serviceStringsArray[] = "<a href=\"".BASE."/services/service.php?id={$service->getID()}\" >{$service->getTitle()}</a>";
					}
					echo implode(', ', $serviceStringsArray);
					?>
					</dd>
					<?php } ?>
				</dl>
			</div>
			
			<div class="exemplar-text"  >
				<?php echo $row->exemplarDescription; ?>
				<?php if(strlen($row->exemplarBenefits) > 2) { ?>
				<h3>Benefits:</h3>
				<?php echo $row->exemplarBenefits; ?>
				<?php } ?>
			</div>
		</div>

		<!-- InstanceEndEditable -->
		</div>
</div>

<div id="footer"><!-- #BeginLibraryItem "/Library/nav-footer.lbi" --><div class="nav-footer">
	<p><a href="http://www.manchester.ac.uk/disclaimer/" title="Disclaimer">Disclaimer</a> | <a href="http://www.manchester.ac.uk/privacy/" title="Privacy">Privacy</a> | <a href="http://www.manchester.ac.uk/copyright/" title="Copyright Notice">Copyright notice</a> | <a href="http://www.manchester.ac.uk/accessibility/" title="Accessibility">Accessibility</a> | <a href="http://www.manchester.ac.uk/foi/" title="Freedom of information">Freedom of information</a></p>
</div><!-- #EndLibraryItem --><!-- #BeginLibraryItem "/Library/page-feedback.lbi" --><div class="feedback">
<p>Humanities Teaching and Learning Office, The University of Manchester, Oxford Road, Manchester UK M13 9PL | <a href="http://www.humanities.manchester.ac.uk/aboutus/contact/" title="Address, telephone and email contacts for the Humanities T&amp;L Office">Contact details</a> | <a href="http://www.humanities.manchester.ac.uk/aboutus/feedback/" title="Feedback form for comments on the Humanities T&amp;L Office website">Feedback</a></p>
<p>The Humanities T&amp;L Office is part of the <a href="http://www.humanities.manchester.ac.uk">Faculty of Humanities </a></p>
</div><!-- #EndLibraryItem --></div>

<!-- #BeginLibraryItem "/Library/nav-skiplinks.lbi" --><div class="nav-skiplinks"><a href="#nav-skiplink-target-content">Skip to main content</a>&nbsp;<a href="#nav-skiplink-target-navigation">Skip to navigation</a></div><!-- #EndLibraryItem --><!-- #BeginLibraryItem "/Library/page-webstandardsmessage.lbi" --><p class="hidden">This website will look much better in a web browser that supports <a title="Supports Web Standards" href="http://www.webstandards.org/">web standards</a>, but it is accessible to any browser or Internet device.</p>
<script type="text/javascript" src="http://www.google-analytics.com/ga.js">
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-4721519-8");
pageTracker._trackPageview();
} catch(err) {}</script><!-- #EndLibraryItem --></body>
<!-- InstanceEnd --></html>