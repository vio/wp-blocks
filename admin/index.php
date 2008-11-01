<?php 

// prepare links 
$_link_add = "<a href=\"" . WTBADMIN . "&act=add\">add new</a>";

// include file 
$_include_file = "";

// get/set actions : add/edit/delete insert/update
$_act = $_GET["act"];


// normalize block name
function wpb_normalize( $name ) {
	return str_replace( ' ', '_', strtolower(trim( $name )) );
}


$wpb_errors = array();
// simple validation form for not blank fields
function wpb_validation( $args ) {
	global $wpb_errors;	
	
	foreach( $args as $_field ):
		if( !$_field ): 
			$wpb_errors[] = 'Field can\'t be blank!'; 
		endif;
	endforeach;

}


// checking for errors
function wpb_has_errors() {
	global $wpb_errors;
	return sizeof($wpb_errors);
	}


switch( $_act ) :

	// add a new block
	case "add" :
		$_heading		= "/ Add block";
		$_info			= "Add a new block of text and/or markup.";
		$_include_file	= "block-add";
	
		break;


	// sql insert a new block	
	case "insert" :
		
		// getting variables from POST
		$_id			= $_POST[ "block_id" ];
		$_name			= wpb_normalize( $_POST[ "block_name" ] );
		$_description	= $_POST[ "block_description" ];
		$_content		= $_POST[ "block_content" ]	;
		$_repeated		= $_POST[ "_REPEATED" ] ;

		wpb_validation( array( $_name) );

		// do sql only if not repeated, else just load as index
		if( !$_repeated && !wpb_has_errors() ) :

			// create & run sql statement 
			$_insert = $wpdb->query( " INSERT INTO " . WTB_TABLE . " 
				(block_name, block_description, block_content ) 
				values(\"$_name\", \"$_description\", \"$_content\")" );	

			// check if sql was inserted without errors
			if( $_insert ) :
				// insert true 
				$_msg			= "New block <strong>added</strong>!";
			else : 
				$wtb_errors[]	= 'SQL Error. Please check your data again';

			endif; 
		elseif( wpb_has_errors() ):
			
			// render form again
			$_heading		= "/ Add block";
			$_info			= "Add a new block of text and/or markup.";
			$_include_file	= "block-add";
			
		endif;
		
		break;


	// edit a block
	case "edit" :
		$_heading = "/ Edit block";
		$_info			= "Edit block.";
		$_include_file = "block-edit";

		break;


	// update block
	case "update" :

		// get id from POST  
		$_id			= $_POST[ "block_id" ];
		$_name			= wpb_normalize( $_POST[ "block_name" ] );
		$_description	= $_POST[ "block_description" ];
		$_content		= $_POST[ "block_content" ]	;
		$_repeated		= $_POST[ "_REPEATED" ] ;

		wpb_validation( array($_name) );
		
		// create sql statement 
		$_update = $wpdb->query( "UPDATE " . $wpdb->prefix . "blocks SET 
			block_name = \"$_name\",
			block_description = \"$_description\",
			block_content = \"$_content\" 
			 WHERE block_id = $_id
			" );


		if( $_update && !wpb_has_errors() ) :
			$_msg = "Block was <strong>updated</strong>!";
			
		elseif ( !$_update && $wpdb->last_error ) :
			
			$wpb_errors[] = "Error! <br /> Wordpress db error message:" . $wpdb->last_error ;

		elseif( wpb_has_errors() ):
				
			// render again edit form
			$_heading = "/ Edit block";
			$_info			= "Edit block.";
			$_include_file = "block-edit";
			
		endif;

		break;


	// delete block
	case "delete" :

		if ( $_GET["block_id"] != 0 ) :
			$_id = $_GET["block_id"];

			// run delete sql
			$_deleted = $wpdb->query( "DELETE FROM " . $wpdb->prefix. "blocks WHERE block_id=$_id");
			
			// check status
			if( $_deleted ) :
				$_msg = "Block was <strong>deleted</strong> !";
			else :
				$wpb_errors[] = "Block was not <strong>deleted</strong> !" ;
			endif;
			
		else:
			$wpb_errors[] = "Nothing to delete ?!" ;
		endif;

		break;

	default :

endswitch;		

// prepare heading  & messages

	// if no heading we have to show default one 
	if( !$_heading ) : $_heading = "( $_link_add )" ; endif; 
	$_heading = "Manage blocks " . $_heading;

	// if no info we will show the default one
	if ( !$_info ) : $_info = "Manage snipetts of text and/or markup."; endif;
	$_info = "<p>" . $_info . "</p>";

?>

<div id="wpbody">
	<div class="wrap">


		<?php

		// show WP style update message if any 
		if ( $_msg ) :  echo "<div class=\"updated fade\"><p>$_msg</p></div>"; endif;
		
		// show WP style error message if any 
		if ( $_errors_count = wpb_has_errors() ) : ?>
		<div class="error">
			<p>
				<?php if( $_errors_count > 1 ):
				echo 'Following errors occoured:';
					foreach($wpb_errors as $_error ):
						echo '<br/>' . $_error;
					endforeach;
				else: echo $wpb_errors[0]; endif; ?>
			</p>
		</div>
		<?
		endif;
		?>

		<h2><?php echo $_heading ?></h2>
		<?php echo $_info ?>
		
		<?php 
		// if we have a file to include will do so 
		if( $_include_file ) : include( $_include_file . ".php" );	endif; 
		?>

		<?php include( "list.php" ); ?>	
	</div>
</div>
