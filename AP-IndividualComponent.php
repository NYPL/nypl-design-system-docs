<?php /* Template Name: AP-IndividualComponent */ ?>

<? get_header(); ?>

<div id="main-content" class="main-content">

    <?php
    if (is_front_page() && twentyfourteen_has_featured_posts()) {
        // Include the featured content template.
        get_template_part('featured-content');
    }
    ?>


    <?php
    //////// VARIABLES
    // Config
    define("CONFIG_NAME", "NYPLdoc1");
    // Templates folder link
    $base_folder_link = "/components/";
    // Link to MVP edit form
    $base_edit_link = "https://airtable.com/tblYWtfeJcUcaW92U/viw8LSUoCBYaxOX1N/";
    //Get Template Slug from URL
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ?  "https" : "http") . "://" . $_SERVER['HTTP_HOST'] .  $_SERVER['REQUEST_URI'];
    $passed_component = array_slice(explode('/', $url), -2)[0];

    ?>

    <?php

    //////// GET COMPONENT DETAILS
    // usage: AirPressQuery($tableName, CONFIG_ID or CONFIG_NAME)
    $query = new AirpressQuery("Components", CONFIG_NAME);
    $query->filterByFormula("{Slug}='$passed_component'");
    $components = new AirpressCollection($query);
    $components->populateRelatedField("IA", "UX Status");
    $components->populateRelatedField("Design", "UX Status");
    $components->populateRelatedField("Design Tech", "UX Status");
    $components->populateRelatedField("Overall Status", "UX Status");
    $components->populateRelatedField("Accessibility Status", "UX Status");


    $C_Record_ID = $components[0]["Record ID"];

    $C_Description = $components[0]["ComponentDescription"];
    $C_Primary_Func_Specs = $components[0]["FunctionalSpecs"];
    $C_Primary_Accessibility = $components[0]["Accessibility"];

    $C_Primary_Attributes_Notes = $components[0]["Attributes Notes"];
    $C_Primary_Attributes_Notes = make_markdown($C_Primary_Attributes_Notes);

    /// Links to code
    $not_available = "NA";

    $C_Twig_Code = "Code " . $not_available;
    if (!empty($components[0]["Twig Code Link"])) {
        $C_Twig_Code = "<a href='" . $components[0]["Twig Code Link"] . "' target='new'>Code</a>";
    }
    $C_Twig_Storybook = "Storybook " . $not_available;
    if (!empty($components[0]["Twig Storybook iFrame URL"])) {
        $C_Twig_Storybook = "<a href='" . $components[0]["Twig Storybook iFrame URL"] . "' target='new'>Storybook</a>";
    }

    $React_Code = "Code " . $not_available;
    if (!empty($components[0]["React Code Link"])) {
        $React_Code = "<a href='" . $components[0]["React Code Link"] . "' target='new'>Code</a>";
    }
    $C_React_Storybook = "Storybook " . $not_available;
    if (!empty($components[0]["React Storybook iFrame URL"])) {
        $C_React_Storybook = "<a href='" . $components[0]["React Storybook iFrame URL"] . "' target='new'>Storybook</a>";
    }
    /////

    $C_Jira_Link = "<a href='" . $components[0]["Jira"] . "'>Jira Ticket</a>";
    $C_DT_Link = "<a href='" . $components[0]["Emulsify Link"] . "'>Code</a>";

    /// Status

    $IA_Status = $components[0]["IA"][0]["Name"];
    $Design_Status = $components[0]["Design"][0]["Name"];
    $Design_Tech_Status = $components[0]["Design Tech"][0]["Name"];
    $Accessibility_Status = $components[0]["Accessibility Status"][0]["Name"];
    $Overall_Status = $components[0]["Overall Status"][0]["Name"];

    $C_Primary_Record_ID = $components[0]["Record ID"];



    $these_parameters = return_my_parameters($components[0]);

    //$C_Primary_Details=$components[0]["Details"];

    //$C_Primary_Definition=return_component_definition("Primary", $C_Primary_Attributes_Notes, $C_Primary_Func_Specs,$C_Primary_Accessibility, $C_Primary_Details, $C_Primary_States, $C_Primary_Parameters, $C_Primary_Record_ID, $C_Primary_Max_Char, $C_Primary_Max_Char_Other, $Text_Format);


    ?>

    <div id="primary" class="content-area">
        <div id="content" class="site-content" role="main">
            <article id="post-1" class="post-1 post type-post status-publish format-standard hentry category-uncategorized">

                <header class="entry-header">
                    <?php
                    $temp_title = $components[0]["ComponentName"];
                    $C_Figma_Link = $components[0]["Figma Link"];

                    //$temp_header=return_display_item_header("Component", $temp_title, $C_Record_ID, $C_Description, $IA_Status, $Design_Status,$Design_Tech_Status, $Accessibility_Status, $Overall_Status, $C_Jira_Link, $C_DT_Link , $C_Primary_Func_Specs, $C_Primary_Accessibility, $these_parameters, $C_Figma_Link);

                    $temp_header = return_display_item_header("Component", $temp_title, $C_Record_ID, $C_Description, $IA_Status, $Design_Status, $Design_Tech_Status, $Accessibility_Status, $Overall_Status, $C_Jira_Link, $C_React_Storybook, $React_Code, $C_Twig_Storybook, $C_Twig_Code, $C_Primary_Func_Specs, $C_Primary_Accessibility, $these_parameters, $C_Figma_Link);

                    echo $temp_header;
                    ?>
                    <!-- .entry-meta -->
                </header><!-- .entry-header -->

                <div class="entry-content">

                    <?php

                    //////// CONNECTIONS BETWEEN TEMPLATES AND COMPONENTS

                    //////// WHAT TEMPLATES AM I USED IN?
                    //////// There should be a better way to do this
                    //////// See "What Projects am I used in" in the Individual Template page

                    $temp_title = $components[0]["ComponentName"];

                    // usage: AirPressQuery($tableName, CONFIG_ID or CONFIG_NAME)
                    $query2 = new AirpressQuery("Component to Template LookUp", CONFIG_NAME);
                    $query2->filterByFormula("{Component}='$temp_title'");
                    $used_in_templates = new AirpressCollection($query2);
                    $used_in_templates->populateRelatedField("Template", "Templates");

                    //////// Display Templates Used In
                    $C_Used_In_Templates = "This component is not currently used by any templates";

                    if (!is_airpress_empty($used_in_templates)) {
                        $num_templates = count($used_in_templates);
                        if (!empty($used_in_templates)) {
                            $C_Used_In_Templates = "Used in $num_templates template(s): ";
                            foreach ($used_in_templates as $e) {
                                $T_Name = $e["Template"][0]["Template Name"];
                                $T_Slug = $e["Template"][0]["Slug"];
                                $T_link = "<a href='" . $GLOBALS['templates_base_folder'] . $T_Slug . "/?fresh=true'>$T_Name</a>";
                                $C_Used_In_Templates .= $T_link . " | ";
                            }
                        }
                    }

                    //////// WHAT COMPONENTS AM I USED IN?	
                    // usage: AirPressQuery($tableName, CONFIG_ID or CONFIG_NAME)
                    $query3 = new AirpressQuery("Component to Component LookUp", CONFIG_NAME);
                    $query3->filterByFormula("{Placed Component}='$temp_title'");
                    $used_in_components = new AirpressCollection($query3);
                    $used_in_components->populateRelatedField("Container Component", "Components");

                    if (!is_airpress_empty($used_in_components)) {
                        $num_components = count($used_in_components);


                        if (!empty($used_in_components)) {
                            $C_Used_In_Components_Array = array();
                            foreach ($used_in_components as $e) {
                                $T_Name = $e["Container Component"][0]["ComponentName"];
                                $T_Slug = $e["Container Component"][0]["Slug"];

                                $T_link = "<a href=" . $GLOBALS['components_base_folder'] . $T_Slug . "/?fresh=true>$T_Name</a>";
                                if (!in_array($T_link, $C_Used_In_Components_Array)) {
                                    $C_Used_In_Components_Array[] = $T_link;
                                }
                            }
                        }
                    }




                    //////// DISPLAY COMPONENT DETAILS

                    // We need $T_Name for later, a little sloppy
                    $T_Name = "";
                    // same
                    $C_Open_Issues_Resolved;

                    if (!is_airpress_empty($components)) {


                        foreach ($components as $e) {
                            $C_Name = $e["ComponentName"];


                            //////// GET OPEN ISSUES FOR MAIN COMPONENT
                            $query_oi = new AirpressQuery("Open Issues", CONFIG_NAME);
                            $query_oi->filterByFormula("{ComponentName}='$C_Name'");
                            // why can't  make AND {Resolved}!='1' work?
                            // AND {Resolved}!=TRUE()
                            $query_oi->sort("Date Created", "asc");
                            $P_C_Open_Issues = new AirpressCollection($query_oi);



                            echo "<table>";

                            /// List my Open Issues
                            // FIX need to get number of RESOLVED vs UNRESOLVED open issues - annoying I can't figure out how to set query
                            $Unresolved_open_issues = "";
                            $C_Open_Issues_Resolved = "";
                            $Num_Open_Issues = 0;
                            // link to add an open issue
                            $oi_link = "<a href='https://airtable.com/shrsJ8X84s0V1RAHV?prefill_ComponentName=" . $C_Name . "' target=new>" . $GLOBALS['icon_add'] . "</a>";

                            foreach ($P_C_Open_Issues as $oi) {
                                $oi_edit_link = "<a href='https://airtable.com/tbl401gXwwvqhjlEY/viwAdqTthL6WmQHpy/" . $oi["Record ID"] . "?blocks=hide' target='new'>" . $GLOBALS['icon_edit'] . "</a>";
                                $oi_resolve_link = "<a href='https://airtable.com/tbl401gXwwvqhjlEY/viwAdqTthL6WmQHpy/" . $oi["Record ID"] . "?blocks=hide'  target='new'>" . $GLOBALS['icon_resolve'] . "</a>";
                                $t_oi = "<strong>" . $oi["Type"] . "</strong> " . $oi['Open Issue'];
                                $t_oi = make_markdown($t_oi);
                                if ($oi["Resolved"] != 1) {
                                    $t_oi .= $oi_edit_link . "   " . $oi_resolve_link;
                                    $Unresolved_open_issues .= "<tr><td colspan=5>" . $t_oi . "</td></tr>";
                                    $Num_Open_Issues += 1;
                                } else {
                                    // if resolved, display at bottom of page. This is passed through $T_Open_Issues_Resolved
                                    $t_solution = make_markdown($oi["Solution"]);;
                                    $C_Open_Issues_Resolved .= "<tr><td>" . $t_oi . " " . $oi_edit_link . "</td><td>" . $t_solution . "</td></tr>";
                                }
                            }
                            $Unresolved_open_issues = "<tr><td colspan=5><br><span class='urgent_message'>$Num_Open_Issues Open Issues</span> | $oi_link</td></tr>" . $Unresolved_open_issues;

                            echo $Unresolved_open_issues;

                            echo "</table><hr>";
                        }
                    } else {
                        echo "No Details Found";
                    }

                    //////// GET PLACED COMPONENTS 
                    /// Get component from lookup table 
                    // usage: AirPressQuery($tableName, CONFIG_ID or CONFIG_NAME)
                    $query = new AirpressQuery("Component to Component LookUp", CONFIG_NAME);
                    $query->filterByFormula("{Container Component}='$C_Name'");
                    $query->sort("Order", "asc");
                    $placed_components = new AirpressCollection($query);
                    // connect related fields (Column, Table)
                    $placed_components->populateRelatedField("Placed Component", "Components");
                    // Breaks my brain
                    $placed_components->populateRelatedField("Placed Component|IA", "UX Status");
                    $placed_components->populateRelatedField("Placed Component|Design", "UX Status");
                    $placed_components->populateRelatedField("Placed Component|Design Tech", "UX Status");
                    $placed_components->populateRelatedField("Placed Component|Accessibility Status", "UX Status");
                    $placed_components->populateRelatedField("Placed Component|Overall Status", "UX Status");



                    $Num_Components = count($placed_components);
                    $add_a_component_link = "<a href='https://airtable.com/shrrxePAAcL1ERXqx?prefill_Container%20Component=" . $C_Name . "' target=new>" . $GLOBALS['icon_add'] . "</a>";

                    echo "<strong>" . $Num_Components . " Component(s) Used " . $add_a_component_link . "</strong><p><br>";
                    //https://airtable.com/shrUc3dtBvnhfavnN

                    $base_folder_link = "/components/";
                    $base_edit_link = "https://airtable.com/tblYWtfeJcUcaW92U/viw8LSUoCBYaxOX1N/";

                    //////// DISPLAY COMPONENTS 
                    if ($Num_Components > 0) {
                        echo "<table class='cleantable'>";

                        foreach ($placed_components as $e) {
                            // Get attibutes based on my type
                            $basetype = $e["Placed Component"][0]["Base Type"];
                            $attr_lu = $e["C_T_ID"];
                            $basedetails = "<span class='urgent_message'>Component doesn't have a type assigned</span>";





                            //// My Core Attributes
                            $C_Sub_Attributes_Notes = $e["Placed Component"][0]["Attributes Notes"];
                            $C_Sub_Func_Specs = $e["Placed Component"][0]["FunctionalSpecs"];
                            $C_Sub_Accessibility = $e["Placed Component"][0]["Accessibility"];
                            $C_Sub_Details = $e["Placed Component"][0]["Details"];
                            $C_Sub_States = $e["Placed Component"][0]["States"];
                            $C_Sub_Parameters = $e["Placed Component"][0]["Parameters"];
                            $C_Sub_Record_ID = $e["Placed Component"][0]["Record ID"];
                            $C_Sub_Max_Char = $e["Placed Component"][0]["TEXT: Max Character Count"];
                            $C_Sub_Max_Char_Other = $e["Placed Component"][0]["TEXT: Max Character Count Other"];

                            $C_Sub_Text_Format = $e["Placed Component"][0]["TEXT: Format"];

                            //$C_Sub_Definition=return_component_definition("Sub", $C_Sub_Attributes_Notes, $C_Sub_Func_Specs,$C_Sub_Accessibility, $C_Sub_Details, $C_Sub_States, $C_Sub_Parameters, $C_Sub_Record_ID, $C_Sub_Max_Char, $C_Sub_Max_Char_Other, $C_Sub_Text_Format );

                            //My Placement Details
                            $Source = $e["Source"];
                            $Source_Other = $e["Source Other"];
                            $Placed_Accessibility = $e["Placed Accessibility"];
                            $Placed_Functionality = $e["Placed Functionality"];
                            $Placement_Description = $e["Placement Description"];
                            $Placement_Optional = $e["Optional"];
                            $Placement_Parameters = $e["Placed Parameters"];
                            $Placement_Details = $e["Details"];


                            // send my CtoC ID
                            //$C_Placement_Details=return_placement_details($e["Record ID"], $Source, $Source_Other, $Placed_Accessibility, $Placed_Functionality, $Placement_Description, $Placement_Optional, $Placement_Parameters, $Placement_Details);

                            $C_Manual_or_Auto = $e["Manual or Auto"];
                            if (!empty($C_Manual_or_Auto)) {
                                if ($C_Manual_or_Auto == "Manual") {
                                    $C_Manual_or_Auto = "M";
                                } else {
                                    $C_Manual_or_Auto = "A";
                                }
                            }

                            $placement_id = $e["Placed Component"][0]["Record ID"];
                            $Placement_Description = $e["Placement Description"];
                            $Placement_Rules = $e["Placement Rules"];


                            $IA_Status = $e["Placed Component"][0]["IA"][0]["Name"];
                            $Design_Status = $e["Placed Component"][0]["Design"][0]["Name"];
                            $Design_Tech_Status = $e["Placed Component"][0]["Design Tech"][0]["Name"];
                            $Accessibility_Status = $e["Placed Component"][0]["Accessibility Status"][0]["Name"];
                            $Overall_Status = $e["Placed Component"][0]["Overall Status"][0]["Name"];

                            $C_Name = $e["Placed Component"][0]["ComponentName"];
                            $C_Slug = $e["Placed Component"][0]["Slug"];
                            $component_description = $e["Placed Component"][0]["ComponentDescription"];

                            $C_Sub_Func_Specs = $e["Placed Component"][0]["FunctionalSpecs"];
                            $C_Sub_Accessibility = $e["Placed Component"][0]["Accessibility"];


                            $C_Optional = $e["Optional"];

                            $these_parameters = return_my_parameters($e["Placed Component"][0]);
                            $these_details = return_placed_component_details("Component", $e["Record ID"], $placement_id, $e["Order"], $C_Manual_or_Auto, $Placement_Description, $Placement_Rules, $IA_Status, $Design_Status, $Design_Tech_Status, $Accessibility_Status, $Overall_Status, $C_Name, $C_Slug, $component_description, $C_Sub_Accessibility, $C_Sub_Func_Specs, $these_parameters, $C_Optional);

                            //echo $these_details;
                            echo $these_details;
                        }
                        echo "</table>";
                    }
                    ////// NOTES ON USAGE
                    echo "<hr>";
                    echo $C_Used_In_Templates . "<br>";
                    if (empty($C_Used_In_Components_Array)) {
                        echo "Not currently used in any components";
                    } else {
                        echo "This component is used in " . count($C_Used_In_Components_Array) . " component(s) ";
                        foreach ($C_Used_In_Components_Array as $e) {
                            echo $e . "  |  ";
                        }
                    }




                    // NOTES ON RESOLVED OPEN ISSUES
                    echo "<hr>";
                    echo $GLOBALS['icon_resolve'] . "  Resolved Open Issues";
                    echo "<table>" . $C_Open_Issues_Resolved . "</table>";

                    ?>

                </div>
            </article>
        </div><!-- #content -->
    </div><!-- #primary -->
    <?php get_sidebar('content'); ?>
</div><!-- #main-content -->

<?php
get_sidebar();
get_footer();
