<?php

/*
Plugin Name: Quiz Admin Student Readout
Plugin URI: https://github.com/gskriotor/WP-Quiz-Admin-Plug
Description: Administrate practice quiz results. Output info and results for each student that took the quiz
Version: 0.0.19
Author Gus Spencer
Author URI: https://gusspencer.com
Text Domain: education
*/

//git test

function finder_form() {

global $wpdb;
$school_select = $wpdb->get_results( "SELECT DISTINCT meta_key, meta_value FROM {$wpdb->prefix}usermeta", ARRAY_A);
$exam_select = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}watupro_master", ARRAY_A);

   echo '
      <form class="fStyle" action="'.$_SERVER['REQUEST_URI'].'" method="POST">
      <div class="fField">
         <label>School Selector</label><br>
         <select class="fSelect" name="school">';

            foreach($school_select as $school_selects) {

               if($school_selects['meta_key'] == 'select_school') {
                  echo '<option class="fOption" value="'.$school_selects['meta_value'].'">'.$school_selects['meta_value'].'</option><br>';
               }

            }

   echo '</select>
      </div>
      <div class="fField">
         <label>Exam Selector</label><br>
         <select class="fSelect" name="exam">';

            foreach($exam_select as $exam_selects) {

               echo '<option class="fOption" value="'.$exam_selects['name'].'">'.$exam_selects['name'].'</option><br>';

            }

   echo '</select>
      </div>
      <button class="fButton" type="submit" name="submit">Submit</button><?php

/*
Plugin Name: Quiz Admin Student Readout
Plugin URI: https://github.com/gskriotor/WP-Quiz-Admin-Plug
Description: Administrate practice quiz results. Output info and results for each student that took the quiz
Version: 0.0.19
Author Gus Spencer
Author URI: https://gusspencer.com
Text Domain: education
*/

//git test

function finder_form() {

global $wpdb;
$school_select = $wpdb->get_results( "SELECT DISTINCT meta_key, meta_value FROM {$wpdb->prefix}usermeta", ARRAY_A);
$exam_select = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}watupro_master", ARRAY_A);

   echo '
      <form class="fStyle" action="'.$_SERVER['REQUEST_URI'].'" method="POST">
      <div class="fField">
         <label>School Selector</label><br>
         <select class="fSelect" name="school">';

            foreach($school_select as $school_selects) {

               if($school_selects['meta_key'] == 'select_school') {
                  echo '<option class="fOption" value="'.$school_selects['meta_value'].'">'.$school_selects['meta_value'].'</option><br>';
               }

            }

   echo '</select>
      </div>
      <div class="fField">
         <label>Exam Selector</label><br>
         <select class="fSelect" name="exam">';

            foreach($exam_select as $exam_selects) {

               echo '<option class="fOption" value="'.$exam_selects['name'].'">'.$exam_selects['name'].'</option><br>';

            }

   echo '</select>
      </div>
      <button class="fButton" type="submit" name="submit">Submit</button>
      <button class="fButton" type="submit" name="post_results">
         post results
      </button>
      </form>
   ';
}


function get_studs() {

$pName = $_POST['exam'];

   global $wpdb;

   $exam_sel = $wpdb->get_results( "SELECT ID FROM {$wpdb->prefix}watupro_master WHERE name = '$pName'", ARRAY_A);

   $x_id = (int)$exam_sel[0]['ID'];

   $results = $wpdb->get_results( "SELECT date FROM {$wpdb->prefix}watupro_taken_exams WHERE exam_id = $x_id", ARRAY_A);
   $topScore = $wpdb->get_results( "SELECT MAX(points) FROM {$wpdb->prefix}watupro_taken_exams WHERE exam_id = $x_id", ARRAY_A);
   $minScore = $wpdb->get_results( "SELECT MIN(points) FROM {$wpdb->prefix}watupro_taken_exams WHERE exam_id = $x_id", ARRAY_A);
   $maxScore = $wpdb->get_results( "SELECT MAX(max_points) FROM {$wpdb->prefix}watupro_taken_exams WHERE exam_id = $x_id", ARRAY_A);
   $quest = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}watupro_question WHERE exam_id = $x_id", ARRAY_A);
   $sanswc = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}watupro_student_answers WHERE exam_id = $x_id AND is_correct = 1", ARRAY_A);
   $sanswt = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}watupro_student_answers WHERE exam_id = $x_id", ARRAY_A);
   $answ = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}watupro_answer", ARRAY_A);

   $examTakes = $wpdb->get_results( "SELECT user_id FROM {$wpdb->prefix}watupro_taken_exams WHERE exam_id = $x_id", ARRAY_A);


//Top 10 students sql query
   $topStudents = $wpdb->get_results( "SELECT DISTINCT user_id FROM {$wpdb->prefix}watupro_taken_exams WHERE exam_id = $x_id ORDER BY percent_points DESC", ARRAY_A);

   $maxPoints = $maxScore[0]['MAX(max_points)'];

   $highScorePerc = ($topScore[0]['MAX(points)'] * 100) / $maxPoints;

   $highScorePercCl = number_format($highScorePerc, 2);

   $lowScorePerc = ($lowScore[0]['MIN(points)'] * 100) / $maxPoints;

   $lowScorePercCl = number_format($lowScorePerc, 2);
echo 'ready to print';

echo '
<style type="text/css">
	.TFtable{
		width:100%;
		border-collapse:collapse;
	}
	.TFtable td{
		padding:7px; border:#4e95f4 1px solid;
	}
	/* provide some minimal visual accomodation for IE8 and below */
	.TFtable tr{
		background: #b8d1f3;
	}
	/*  Define the background color for all the ODD background rows  */
	.TFtable tr:nth-child(odd){
		background: #b8d1f3;
	}
	/*  Define the background color for all the EVEN background rows  */
	.TFtable tr:nth-child(even){
		background: #dae5f4;
	}

</style>
';

echo '<h1>'.$pName.'</h1>';

echo '<table class="TFtable">';
echo '<td><strong>Student Name</strong></td><td><strong>Percent Corrent</strong></td><td><strong>Login Name</strong></td>';
   //Run query for student list with info and stat
   foreach($topStudents as $studs) {
     $studID = $studs['user_id'];
     $studentInfo = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}users WHERE ID = $studID", ARRAY_A);
     $studMetaFn = $wpdb->get_results( "SELECT meta_value FROM {$wpdb->prefix}usermeta WHERE user_id = $studID AND meta_key = 'first_name'", ARRAY_A);
     $studMetaLn = $wpdb->get_results( "SELECT meta_value FROM {$wpdb->prefix}usermeta WHERE user_id = $studID AND meta_key = 'last_name'", ARRAY_A);
     $topStudScore = $wpdb->get_results( "SELECT percent_correct FROM {$wpdb->prefix}watupro_taken_exams WHERE user_id = $studID", ARRAY_A);
echo '<div>';

   echo '

         <tr>
   ';
     echo '<td>'.$studMetaFn[0]['meta_value'].' ';
     echo $studMetaLn[0]['meta_value'].'</td>';
     echo '<td>'.$topStudScore[0]['percent_correct'].'%</td>';
     echo '<td>'.$studentInfo[0]['user_login'].'</td>';
   echo '

      </tr>
   ';

echo '</div>';
   }

echo '</table>';

?>

<!--start section to print to page -->
<div id="divToPrint" style="display:none;">
  <div style="

      /* print styles */

	  .TFtable{
		width:100%;
		border-collapse:collapse;
	  }
	  .TFtable td{
		padding:7px; border:#4e95f4 1px solid;
	  }
	  /* provide some minimal visual accomodation for IE8 and below */
	  .TFtable tr{
		background: #b8d1f3;
	  }
	  /*  Define the background color for all the ODD background rows  */
	  .TFtable tr:nth-child(odd){
		background: #b8d1f3;
	  }
	  /*  Define the background color for all the EVEN background rows  */
	  .TFtable tr:nth-child(even){
		background: #dae5f4;
	  }

  ">
<?php

echo '<h1>'.$pName.'</h1>';
echo '<table class="TFtable">';
   //Run query for student list with info and stat
   foreach($topStudents as $studs) {
     $studID = $studs['user_id'];
     $studentInfo = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}users WHERE ID = $studID", ARRAY_A);
     $studMetaFn = $wpdb->get_results( "SELECT meta_value FROM {$wpdb->prefix}usermeta WHERE user_id = $studID AND meta_key = 'first_name'", ARRAY_A);
     $studMetaLn = $wpdb->get_results( "SELECT meta_value FROM {$wpdb->prefix}usermeta WHERE user_id = $studID AND meta_key = 'last_name'", ARRAY_A);
     $topStudScore = $wpdb->get_results( "SELECT percent_correct FROM {$wpdb->prefix}watupro_taken_exams WHERE user_id = $studID", ARRAY_A);
echo '<div>';

   echo '

         <tr>
   ';
     echo '<td><strong>Student Name: </strong>'.$studMetaFn[0]['meta_value'].' ';
     echo $studMetaLn[0]['meta_value'].'</td>';
     echo '<td><strong>Correct:</strong>'.$topStudScore[0]['percent_correct'].'%</td>';
     echo '<td><strong>Login Name: </strong>'.$studentInfo[0]['user_login'].'</td>';
   echo '</tr> ';

echo '</div>';
   }
echo '</table>';


?>

<!-- end section to print page -->
  </div>
</div>

<?php
}

function post_results() {

$pName = $_POST['exam'];

   global $wpdb;

   $exam_sel = $wpdb->get_results( "SELECT ID FROM {$wpdb->prefix}watupro_master WHERE name = '$pName'", ARRAY_A);

   $x_id = (int)$exam_sel[0]['ID'];

   $results = $wpdb->get_results( "SELECT date FROM {$wpdb->prefix}watupro_taken_exams WHERE exam_id = $x_id", ARRAY_A);
   $topScore = $wpdb->get_results( "SELECT MAX(points) FROM {$wpdb->prefix}watupro_taken_exams WHERE exam_id = $x_id", ARRAY_A);
   $minScore = $wpdb->get_results( "SELECT MIN(points) FROM {$wpdb->prefix}watupro_taken_exams WHERE exam_id = $x_id", ARRAY_A);
   $maxScore = $wpdb->get_results( "SELECT MAX(max_points) FROM {$wpdb->prefix}watupro_taken_exams WHERE exam_id = $x_id", ARRAY_A);
   $quest = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}watupro_question WHERE exam_id = $x_id", ARRAY_A);
   $sanswc = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}watupro_student_answers WHERE exam_id = $x_id AND is_correct = 1", ARRAY_A);
   $sanswt = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}watupro_student_answers WHERE exam_id = $x_id", ARRAY_A);
   $answ = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}watupro_answer", ARRAY_A);

   $examTakes = $wpdb->get_results( "SELECT user_id FROM {$wpdb->prefix}watupro_taken_exams WHERE exam_id = $x_id", ARRAY_A);


//Top 10 students sql query
   $topStudents = $wpdb->get_results( "SELECT DISTINCT user_id FROM {$wpdb->prefix}watupro_taken_exams WHERE exam_id = $x_id ORDER BY percent_points DESC", ARRAY_A);

   $maxPoints = $maxScore[0]['MAX(max_points)'];

   $highScorePerc = ($topScore[0]['MAX(points)'] * 100) / $maxPoints;

   $highScorePercCl = number_format($highScorePerc, 2);

   $lowScorePerc = ($lowScore[0]['MIN(points)'] * 100) / $maxPoints;

   $lowScorePercCl = number_format($lowScorePerc, 2);
echo 'ready to print';

echo '
<style type="text/css">
	.TFtable{
		width:100%;
		border-collapse:collapse;
	}
	.TFtable td{
		padding:7px; border:#4e95f4 1px solid;
	}
	/* provide some minimal visual accomodation for IE8 and below */
	.TFtable tr{
		background: #b8d1f3;
	}
	/*  Define the background color for all the ODD background rows  */
	.TFtable tr:nth-child(odd){
		background: #b8d1f3;
	}
	/*  Define the background color for all the EVEN background rows  */
	.TFtable tr:nth-child(even){
		background: #dae5f4;
	}

</style>
';

echo '<h1>'.$pName.'</h1>';

echo '<table class="TFtable">';
echo '<td><strong>Student Name</strong></td><td><strong>Percent Correct</strong></td><td><strong>Login Name</strong></td>';
   //Run query for student list with info and stat
   foreach($topStudents as $studs) {
     $studID = $studs['user_id'];
     $studentInfo = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}users WHERE ID = $studID", ARRAY_A);
     $studMetaFn = $wpdb->get_results( "SELECT meta_value FROM {$wpdb->prefix}usermeta WHERE user_id = $studID AND meta_key = 'first_name'", ARRAY_A);
     $studMetaLn = $wpdb->get_results( "SELECT meta_value FROM {$wpdb->prefix}usermeta WHERE user_id = $studID AND meta_key = 'last_name'", ARRAY_A);
     $topStudScore = $wpdb->get_results( "SELECT percent_correct FROM {$wpdb->prefix}watupro_taken_exams WHERE user_id = $studID", ARRAY_A);
echo '<div>';

   echo '

         <tr>
   ';
     echo '<td>'.$studMetaFn[0]['meta_value'].' ';
     echo $studMetaLn[0]['meta_value'].'</td>';
     echo '<td>'.$topStudScore[0]['percent_correct'].'%</td>';
     echo '<td>'.$studentInfo[0]['user_login'].'</td>';
   echo '

      </tr>
   ';

echo '</div>';
   }

echo '</table>';


   //Run query for student list with info and stat
   foreach($topStudents as $studs) {
     $studID = $studs['user_id'];
     $studentInfo = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}users WHERE ID = $studID", ARRAY_A);
     $studMetaFn = $wpdb->get_results( "SELECT meta_value FROM {$wpdb->prefix}usermeta WHERE user_id = $studID AND meta_key = 'first_name'", ARRAY_A);
     $studMetaLn = $wpdb->get_results( "SELECT meta_value FROM {$wpdb->prefix}usermeta WHERE user_id = $studID AND meta_key = 'last_name'", ARRAY_A);
     $topStudScore = $wpdb->get_results( "SELECT percent_correct FROM {$wpdb->prefix}watupro_taken_exams WHERE user_id = $studID", ARRAY_A);
$result_content .= '
   <div>
         <tr>
            <td>'.$studMetaFn[0]['meta_value'].' '.$studMetaLn[0]['meta_value'].'</td>
            <td>'.$topStudScore[0]['percent_correct'].'%</td>
            <td>'.$studentInfo[0]['user_login'].'</td>
         </tr> 
   </div>';
   }

      $check_post = get_page_by_title($post_title, 'OBJECT', 'post');
      if(empty($_POST['exam'])) {
           global $user_ID;
           $new_post = [
              'post_title' => $_POST['exam'].'(RESULTS)',
              'post_content' => '
                 <table class="TFtable">
                 <td><strong>Student Name</strong></td><td><strong>Percent Correct</strong></td><td><strong>Login Name</strong></td>
              '.$result_content.'</table>',
              'post_status' => 'publish',
              'post_date' => date('Y-m-d H:i:s123'),
              'post_author' => $user_ID,
              'post_type' => 'post',
              'post_category' => [0]
          ];
          $post_id = wp_insert_post($new_post);
      }
      else {

        $update_post = [
          'ID' => $check_post->ID,
          'post_title' => $_POST['exam'].'(RESULTS)',
          'post_content' => 'new test content',
          'post_status' => 'publish',
          'post_author' => $user_ID,
          'post_category' => [0]
        ];
         wp_update_post($update_post);
      }

echo 'this result has already been posted';
}

function result_finder() {

   finder_form();
   if(isset($_POST['post_results'])) {
     post_results();
   }
   if(isset($_POST['submit'])) {
      get_studs();
   }
}

function qAdmin_shortcode() {

   ob_start();

      result_finder();

   return ob_get_clean();
}

add_shortcode( 's_exam', 'qAdmin_shortcode' );

?>


      <button class="fButton" type="submit" name="post_results">
         post results
      </button>
      </form>
   ';
}


function get_studs() {

$pName = $_POST['exam'];

   global $wpdb;

   $exam_sel = $wpdb->get_results( "SELECT ID FROM {$wpdb->prefix}watupro_master WHERE name = '$pName'", ARRAY_A);

   $x_id = (int)$exam_sel[0]['ID'];

   $results = $wpdb->get_results( "SELECT date FROM {$wpdb->prefix}watupro_taken_exams WHERE exam_id = $x_id", ARRAY_A);
   $topScore = $wpdb->get_results( "SELECT MAX(points) FROM {$wpdb->prefix}watupro_taken_exams WHERE exam_id = $x_id", ARRAY_A);
   $minScore = $wpdb->get_results( "SELECT MIN(points) FROM {$wpdb->prefix}watupro_taken_exams WHERE exam_id = $x_id", ARRAY_A);
   $maxScore = $wpdb->get_results( "SELECT MAX(max_points) FROM {$wpdb->prefix}watupro_taken_exams WHERE exam_id = $x_id", ARRAY_A);
   $quest = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}watupro_question WHERE exam_id = $x_id", ARRAY_A);
   $sanswc = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}watupro_student_answers WHERE exam_id = $x_id AND is_correct = 1", ARRAY_A);
   $sanswt = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}watupro_student_answers WHERE exam_id = $x_id", ARRAY_A);
   $answ = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}watupro_answer", ARRAY_A);

   $examTakes = $wpdb->get_results( "SELECT user_id FROM {$wpdb->prefix}watupro_taken_exams WHERE exam_id = $x_id", ARRAY_A);


//Top 10 students sql query
   $topStudents = $wpdb->get_results( "SELECT DISTINCT user_id FROM {$wpdb->prefix}watupro_taken_exams WHERE exam_id = $x_id ORDER BY percent_points DESC", ARRAY_A);

   $maxPoints = $maxScore[0]['MAX(max_points)'];

   $highScorePerc = ($topScore[0]['MAX(points)'] * 100) / $maxPoints;

   $highScorePercCl = number_format($highScorePerc, 2);

   $lowScorePerc = ($lowScore[0]['MIN(points)'] * 100) / $maxPoints;

   $lowScorePercCl = number_format($lowScorePerc, 2);
echo 'ready to print';

echo '
<style type="text/css">
	.TFtable{
		width:100%;
		border-collapse:collapse;
	}
	.TFtable td{
		padding:7px; border:#4e95f4 1px solid;
	}
	/* provide some minimal visual accomodation for IE8 and below */
	.TFtable tr{
		background: #b8d1f3;
	}
	/*  Define the background color for all the ODD background rows  */
	.TFtable tr:nth-child(odd){
		background: #b8d1f3;
	}
	/*  Define the background color for all the EVEN background rows  */
	.TFtable tr:nth-child(even){
		background: #dae5f4;
	}

</style>
';

echo '<h1>'.$pName.'</h1>';
echo '<div>
  <input type="button" value="print" onclick="PrintDiv();" />
</div>';
echo '<table class="TFtable">';
   //Run query for student list with info and stat
   foreach($topStudents as $studs) {
     $studID = $studs['user_id'];
     $studentInfo = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}users WHERE ID = $studID", ARRAY_A);
     $studMetaFn = $wpdb->get_results( "SELECT meta_value FROM {$wpdb->prefix}usermeta WHERE user_id = $studID AND meta_key = 'first_name'", ARRAY_A);
     $studMetaLn = $wpdb->get_results( "SELECT meta_value FROM {$wpdb->prefix}usermeta WHERE user_id = $studID AND meta_key = 'last_name'", ARRAY_A);
     $topStudScore = $wpdb->get_results( "SELECT percent_correct FROM {$wpdb->prefix}watupro_taken_exams WHERE user_id = $studID", ARRAY_A);
echo '<div>';

   echo '

         <tr>
   ';
     echo '<td><strong>Student Name: </strong>'.$studMetaFn[0]['meta_value'].' ';
     echo $studMetaLn[0]['meta_value'].'</td>';
     echo '<td><strong>Correct: </strong>'.$topStudScore[0]['percent_correct'].'%</td>';
     echo '<td><strong>Login Name: </strong>'.$studentInfo[0]['user_login'].'</td>';
   echo '

      </tr>
   ';

echo '</div>';
   }

echo '</table>';

?>

<!--start section to print to page -->
<div id="divToPrint" style="display:none;">
  <div style="

      /* print styles */

	  .TFtable{
		width:100%;
		border-collapse:collapse;
	  }
	  .TFtable td{
		padding:7px; border:#4e95f4 1px solid;
	  }
	  /* provide some minimal visual accomodation for IE8 and below */
	  .TFtable tr{
		background: #b8d1f3;
	  }
	  /*  Define the background color for all the ODD background rows  */
	  .TFtable tr:nth-child(odd){
		background: #b8d1f3;
	  }
	  /*  Define the background color for all the EVEN background rows  */
	  .TFtable tr:nth-child(even){
		background: #dae5f4;
	  }

  ">
<?php

echo '<h1>'.$pName.'</h1>';
echo '<table class="TFtable">';
   //Run query for student list with info and stat
   foreach($topStudents as $studs) {
     $studID = $studs['user_id'];
     $studentInfo = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}users WHERE ID = $studID", ARRAY_A);
     $studMetaFn = $wpdb->get_results( "SELECT meta_value FROM {$wpdb->prefix}usermeta WHERE user_id = $studID AND meta_key = 'first_name'", ARRAY_A);
     $studMetaLn = $wpdb->get_results( "SELECT meta_value FROM {$wpdb->prefix}usermeta WHERE user_id = $studID AND meta_key = 'last_name'", ARRAY_A);
     $topStudScore = $wpdb->get_results( "SELECT percent_correct FROM {$wpdb->prefix}watupro_taken_exams WHERE user_id = $studID", ARRAY_A);
echo '<div>';

   echo '

         <tr>
   ';
     echo '<td><strong>Student Name: </strong>'.$studMetaFn[0]['meta_value'].' ';
     echo $studMetaLn[0]['meta_value'].'</td>';
     echo '<td><strong>Correct:</strong>'.$topStudScore[0]['percent_correct'].'%</td>';
     echo '<td><strong>Login Name: </strong>'.$studentInfo[0]['user_login'].'</td>';
   echo '</tr> ';

echo '</div>';
   }
echo '</table>';


?>

<!-- end section to print page -->
  </div>
</div>
<div>
  <input type="button" value="print" onclick="PrintDiv();" />
</div>
<?php
}

function post_results() {

$pName = $_POST['exam'];

   global $wpdb;

   $exam_sel = $wpdb->get_results( "SELECT ID FROM {$wpdb->prefix}watupro_master WHERE name = '$pName'", ARRAY_A);

   $x_id = (int)$exam_sel[0]['ID'];

   $results = $wpdb->get_results( "SELECT date FROM {$wpdb->prefix}watupro_taken_exams WHERE exam_id = $x_id", ARRAY_A);
   $topScore = $wpdb->get_results( "SELECT MAX(points) FROM {$wpdb->prefix}watupro_taken_exams WHERE exam_id = $x_id", ARRAY_A);
   $minScore = $wpdb->get_results( "SELECT MIN(points) FROM {$wpdb->prefix}watupro_taken_exams WHERE exam_id = $x_id", ARRAY_A);
   $maxScore = $wpdb->get_results( "SELECT MAX(max_points) FROM {$wpdb->prefix}watupro_taken_exams WHERE exam_id = $x_id", ARRAY_A);
   $quest = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}watupro_question WHERE exam_id = $x_id", ARRAY_A);
   $sanswc = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}watupro_student_answers WHERE exam_id = $x_id AND is_correct = 1", ARRAY_A);
   $sanswt = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}watupro_student_answers WHERE exam_id = $x_id", ARRAY_A);
   $answ = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}watupro_answer", ARRAY_A);

   $examTakes = $wpdb->get_results( "SELECT user_id FROM {$wpdb->prefix}watupro_taken_exams WHERE exam_id = $x_id", ARRAY_A);


//Top 10 students sql query
   $topStudents = $wpdb->get_results( "SELECT DISTINCT user_id FROM {$wpdb->prefix}watupro_taken_exams WHERE exam_id = $x_id ORDER BY percent_points DESC", ARRAY_A);

   $maxPoints = $maxScore[0]['MAX(max_points)'];

   $highScorePerc = ($topScore[0]['MAX(points)'] * 100) / $maxPoints;

   $highScorePercCl = number_format($highScorePerc, 2);

   $lowScorePerc = ($lowScore[0]['MIN(points)'] * 100) / $maxPoints;

   $lowScorePercCl = number_format($lowScorePerc, 2);
echo 'ready to print';

echo '
<style type="text/css">
	.TFtable{
		width:100%;
		border-collapse:collapse;
	}
	.TFtable td{
		padding:7px; border:#4e95f4 1px solid;
	}
	/* provide some minimal visual accomodation for IE8 and below */
	.TFtable tr{
		background: #b8d1f3;
	}
	/*  Define the background color for all the ODD background rows  */
	.TFtable tr:nth-child(odd){
		background: #b8d1f3;
	}
	/*  Define the background color for all the EVEN background rows  */
	.TFtable tr:nth-child(even){
		background: #dae5f4;
	}

</style>
';

echo '<h1>'.$pName.'</h1>';
echo '<div>
  <input type="button" value="print" onclick="PrintDiv();" />
</div>';
echo '<table class="TFtable">';
   //Run query for student list with info and stat
   foreach($topStudents as $studs) {
     $studID = $studs['user_id'];
     $studentInfo = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}users WHERE ID = $studID", ARRAY_A);
     $studMetaFn = $wpdb->get_results( "SELECT meta_value FROM {$wpdb->prefix}usermeta WHERE user_id = $studID AND meta_key = 'first_name'", ARRAY_A);
     $studMetaLn = $wpdb->get_results( "SELECT meta_value FROM {$wpdb->prefix}usermeta WHERE user_id = $studID AND meta_key = 'last_name'", ARRAY_A);
     $topStudScore = $wpdb->get_results( "SELECT percent_correct FROM {$wpdb->prefix}watupro_taken_exams WHERE user_id = $studID", ARRAY_A);
echo '<div>';

   echo '

         <tr>
   ';
     echo '<td><strong>Student Name: </strong>'.$studMetaFn[0]['meta_value'].' ';
     echo $studMetaLn[0]['meta_value'].'</td>';
     echo '<td><strong>Correct: </strong>'.$topStudScore[0]['percent_correct'].'%</td>';
     echo '<td><strong>Login Name: </strong>'.$studentInfo[0]['user_login'].'</td>';
   echo '

      </tr>
   ';

echo '</div>';
   }

echo '</table>';

?>

<!--start section to print to page -->
<div id="divToPrint" style="display:none;">
  <div style="

      /* print styles */

	  .TFtable{
		width:100%;
		border-collapse:collapse;
	  }
	  .TFtable td{
		padding:7px; border:#4e95f4 1px solid;
	  }
	  /* provide some minimal visual accomodation for IE8 and below */
	  .TFtable tr{
		background: #b8d1f3;
	  }
	  /*  Define the background color for all the ODD background rows  */
	  .TFtable tr:nth-child(odd){
		background: #b8d1f3;
	  }
	  /*  Define the background color for all the EVEN background rows  */
	  .TFtable tr:nth-child(even){
		background: #dae5f4;
	  }

  ">
<?php

   //Run query for student list with info and stat
   foreach($topStudents as $studs) {
     $studID = $studs['user_id'];
     $studentInfo = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}users WHERE ID = $studID", ARRAY_A);
     $studMetaFn = $wpdb->get_results( "SELECT meta_value FROM {$wpdb->prefix}usermeta WHERE user_id = $studID AND meta_key = 'first_name'", ARRAY_A);
     $studMetaLn = $wpdb->get_results( "SELECT meta_value FROM {$wpdb->prefix}usermeta WHERE user_id = $studID AND meta_key = 'last_name'", ARRAY_A);
     $topStudScore = $wpdb->get_results( "SELECT percent_correct FROM {$wpdb->prefix}watupro_taken_exams WHERE user_id = $studID", ARRAY_A);
$result_content .= '
   <div>
         <tr>
            <td><strong>Student Name: </strong>'.$studMetaFn[0]['meta_value'].' '.$studMetaLn[0]['meta_value'].'</td>
            <td><strong>Correct:</strong>'.$topStudScore[0]['percent_correct'].'%</td>
            <td><strong>Login Name: </strong>'.$studentInfo[0]['user_login'].'</td>
         </tr> 
   </div>';
   }

$pdf = do_shortcode('[dkpdf-button]');


      $check_post = get_page_by_title($post_title, 'OBJECT', 'post');
      if(empty($check_post)) {
           global $user_ID;
           $new_post = [
              'post_title' => $_POST['exam'].'(RESULTS)',
              'post_content' => '
                 <table class="TFtable">
              '.$result_content.'</table>'.$pdf,
              'post_status' => 'publish',
              'post_date' => date('Y-m-d H:i:s123'),
              'post_author' => $user_ID,
              'post_type' => 'post',
              'post_category' => [0]
          ];
          $post_id = wp_insert_post($new_post);
      }
      else {
/**
        $update_post = [
          'ID' => $check_post->ID,
          'post_title' => $_POST['exam'],
          'post_content' => 'new test content',
          'post_status' => 'publish',
          'post_author' => $user_ID,
          'post_category' => [0]
        ];
         wp_update_post($update_post);
**/
      }

echo 'this result has already been posted';
}

function result_finder() {

   finder_form();
   if(isset($_POST['post_results'])) {
     post_results();
   }
   if(isset($_POST['submit'])) {
      get_studs();
   }
}

function qAdmin_shortcode() {

   ob_start();

      result_finder();

   return ob_get_clean();
}

add_shortcode( 's_exam', 'qAdmin_shortcode' );

?>
<script type="text/javascript">
    function PrintDiv() {
       var divToPrint = document.getElementById('divToPrint');
       var popupWin = window.open('', '_blank', 'width=800,height=800');
       popupWin.document.open();
       popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
        popupWin.document.close();
    }
 </script>
