<?php /* Template Name: AP-IndividualProject */ ?>

<? get_header(); ?>

<div id="main-content" class="main-content">

<?php
if ( is_front_page() && twentyfourteen_has_featured_posts() ) {
	// Include the featured content template.
	get_template_part( 'featured-content' );
}
?>
	
	
<?php
// Set variables
	// link to MVP edit form
	$base_edit_link="https://airtable.com/tblN4ml1RFKA5dEKZ/viwXTL0bK4Oys5sJS/";
	//Get Project 
	$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 
                "https" : "http") . "://" . $_SERVER['HTTP_HOST'] .  
                $_SERVER['REQUEST_URI']; 
			$passed_project = array_slice(explode('/', $url), -2)[0];

	
//Get Project Details
$query = new AirpressQuery();
$query->setConfig("NYPLdoc1");
$query->table("Projects");
$query->filterByFormula("{Slug}='$passed_project'");
$projects = new AirpressCollection($query);
	
$this_project=$projects[0];		
$this_project_name=$this_project["Project Name"];	
	
//Get Templates from "Templates to Projects LookUp" 
// ISUES: Data below is wonky - can't get populate by related field to work corretly. Right now working wrounf this by creating "fake" duplicate fields which copy the data in a formula.
$query2 = new AirpressQuery();
$query2->setConfig("NYPLdoc1");
$query2->table("Templates to Projects LookUp");
$query2->filterByFormula("{Project}='$this_project_name'");
$templates_used = new AirpressCollection($query2);
// Connect linked template w template name
//$templates_used->populateRelatedField("Template","Templates");
//$these_templates=$templates_used[0]["Templates"];
$num_templates= count($templates_used);

?>
			
	
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
<article id="post-1" class="post-1 post type-post status-publish format-standard hentry category-uncategorized">
	
	
	<header class="entry-header">
		<h1 class="entry-title"><?php  echo $this_project["Project Name"]; ?></h1>
		<!-- .entry-meta -->
	</header><!-- .entry-header -->
	
		<div class="entry-content">	
			
			
	<?php 
			
	?>
			
			
			<strong>Project State:</strong>: <?php  echo $this_project["Project State"]; ?> <br>
			<strong>Project Description:</strong><br><span class="themsthebreaks"><?php  echo $this_project["Project Description"]; ?> </span><br>

			<?php
				echo "<br><br><hr><h2>".$num_templates." template(s) used</h2><br>";
foreach($templates_used as $e){
	//ISSUE: This is not done wellTemplate Slug Temp
	//http://themetronome.co/projects/research-now/?slug=research-now&fresh=true
	echo "<h3><a href='/templates/".$e['Template Slug Temp']."/'>".$e['Template Temp']."</a></h2><br>";
	echo "<strong>Description</strong> ".$e['Template Description'][0];
	
	//echo "<br>Template[0] ".$e['Template'][0][0];
	//echo "<br><strong>Notes</strong> ".$e['Notes'];
	echo "<br><hr>";
}
			?>
	
			
			</div>
		</article>
		</div><!-- #content -->
	</div><!-- #primary -->
	<?php get_sidebar( 'content' ); ?>
</div><!-- #main-content -->

<?php
get_sidebar();
get_footer();
