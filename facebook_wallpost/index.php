<?php
session_start();
$session_id=session_id();
include('dbcon.php');
//$userid=1;
//$courseid=1;
//$newsid=1;
//$catid=1;
//$groupid=1;

//Check whether the requested user is the same one with logged in user. 
//This is avoiding anyone to request other users wall page  i.e. https://158.108.219.215/maxlife/facebook_wallpost/index.php?user=fengjini

// $username=$_GET["user"]; // this will allow anyone change the user = xxx to direct access anyone's wall post
//$sqluser = "SELECT id,category FROM users WHERE login = '".$username."';";
$sqluser = "SELECT id,category,session_id FROM users WHERE session_id = '".$session_id."';";
$quser = mysql_query($sqluser);
$userid = mysql_result($quser,0,"id");


$session_saved_id=mysql_result($quser,0,"session_id");

$user_cat = mysql_result($quser,0,"category");
//$upicture = @mysql_result($quser,0,"picture");
//echo $username." ".$userid;
//$userid=$_GET["user"];

$courseid=$_GET["course"];
$newsid=$_GET["news"];

//$catid=$_GET["cat"];
//$groupid=$_GET["group"];

		
//if ($upicture=='' || $upicture==null)  
//{	
//	$imageUser = 'images/no-image-m.png';	
//} else {
//	$imageUser = '../files/preference/'.$userid.'/'.$upicture;
//}


$_SESSION["uid"] = $userid;
$_SESSION["ucat"] = $user_cat;
$_SESSION["cid"] = $courseid;
$_SESSION["nid"] = $newsid;
//$_SESSION["upic"] = $imageUser;
//echo $_SESSION["uid"];
session_write_close();

?>
<!doctype html public "-//W3C//DTD HTML 4.0 //EN">

<html>

<head>
<title>Wall Posting for MaxLearn</title>

<link href="facebox.css" media="screen" rel="stylesheet" type="text/css" />

<!-- Page styles -->
<link type='text/css' href='css/demo.css' rel='stylesheet' media='screen' />

<!-- Contact Form CSS files -->
<link type='text/css' href='css/basic.css' rel='stylesheet' media='screen' />


<style type="text/css">
/*
body {
	margin: 0 auto;
	padding: 0;
	width: 570px;
	font: 75%/120% Arial, Helvetica, sans-serif;
}
*/
a:focus {
	outline: none;
}
#panel {
	/*background: #754c24;
	height: 200px;*/
	height: 100%;
	display: none;
}
.slide {
	margin: 0;
	padding: 0;
	border-top: solid 4px #422410;
	background: url(images/btn-slide.gif) no-repeat center top;
}
.btn-slide {
	background: url(images/white-arrow.gif) no-repeat right -50px;
	text-align: center;
	width: 144px;
	height: 31px;
	padding: 10px 10px 0 0;
	margin: 0 auto;
	display: block;
	font: bold 120%/100% Arial, Helvetica, sans-serif;
	color: #fff;
	text-decoration: none;
}
.active {
	background-position: right 12px;
}
</style>

<script type="text/javascript" src="jquery-1.2.6.min.js"></script>
<script type="text/javascript" src="jquery.livequery.js"></script>
<link href="dependencies/screen.css" type="text/css" rel="stylesheet" />

<script src="jquery.elastic.js" type="text/javascript" charset="utf-8"></script>

<script src="jquery.watermarkinput.js" type="text/javascript"></script>

<script type='text/javascript' src='js/jquery.simplemodal.js'></script>
<script type='text/javascript' src='js/basic.js'></script>

<script type="text/javascript">

	// <![CDATA[	

	$(document).ready(function(){	
	
		$('#shareButton').click(function(){

			var a = $("#watermark").val();
			//var b = $("#course_id").val();
			if(a != "Type Here and Click Send.")
			{
				//if(b > 0) {			
				<? if(isset($_SESSION["cid"])){ ?>
					$.post("posts.php?value="+a+"&uid="+<? echo $_SESSION["uid"]?>+"&cid="+<? echo $_SESSION["cid"]?>, {	
				<? } else {?>
					$.post("posts.php?value="+a+"&uid="+<? echo $_SESSION["uid"]?>, {																								   				<? }?>
	
				}, function(response){
					
					$('#posting').prepend($(response).fadeIn('slow'));
					//$("#watermark").val("What's on your mind?");
					$("#watermark").val("Type Here and Click Send.");
				});
			}
		});	
		
		
		$('.commentMark').livequery("focus", function(e){
			
			var parent  = $(this).parent();
			$(".commentBox").children(".commentMark").css('width','450px');
			$(".commentBox").children("a#SubmitComment").hide();
			$(".commentBox").children(".CommentImg").hide();			
		
			var getID =  parent.attr('id').replace('record-','');			
			$("#commentBox-"+getID).children("a#SubmitComment").show();
			$('.commentMark').css('width','370px');
			$("#commentBox-"+getID).children(".CommentImg").show();			
		});	
		
		//showCommentBox
		$('a.showCommentBox').livequery("click", function(e){
			
			var getpID =  $(this).attr('id').replace('post_id','');	
			
			$("#commentBox-"+getpID).css('display','');
			$("#commentMark-"+getpID).focus();
			$("#commentBox-"+getpID).children("img.CommentImg").show();			
			$("#commentBox-"+getpID).children("a#SubmitComment").show();		
		});	
		
		//doLike
		$('a.doLike').livequery("click", function(e){
			
			var getpID =  $(this).attr('id').replace('post_id','');	
			
			//alert(<? echo $uid;?>);
			
			$.post("add_like.php?post_id="+getpID+"&uid="+<? echo $_SESSION["uid"];?>, {
	
				}, function(response){
					
					$('#commentLike' + getpID).find('span:first').empty().append(response);
				});
			
		});	
		
		//SubmitComment
		$('a.comment').livequery("click", function(e){
			
			//record
			//var getpID =  $(this).parent().attr('id').replace('commentBox-','');	
			var getpID =  $(this).parent().attr('id').replace('commentBox-','');	
			var comment_text = $("#commentMark-"+getpID).val();
			
			if(comment_text != "Write a comment...")
			{
				$.post("add_comment.php?comment_text="+comment_text+"&post_id="+getpID+"&uid="+<? echo $_SESSION["uid"];?>, {
	
				}, function(response){
					
					$('#CommentPosted'+getpID).append($(response).fadeIn('slow'));
					//$('#CommentPosted'+getpID).slideToggle(400);
					//$(this).toggleClass("active"); 
					$("#commentMark-"+getpID).val("Write a comment...");										
				});
				
				
				$.post("count_comment.php?post_id="+getpID, {
	
				}, function(response){
					//alert(response);
					$('#commentGroup' + getpID).find('span:first').empty().append(response);
				});
				
				
			}
			
		});	
		
		//more records show
		$('a.more_records').livequery("click", function(e){
			
			var next =  $(this).attr('id').replace('more_','');
			
			$.post("posts.php?show_more_post="+next, {

			}, function(response){
				$('#bottomMoreButton').remove();
				$('#posting').append($(response).fadeIn('slow'));

			});
			
		});	
		
		//deleteComment
		$('a.c_delete').livequery("click", function(e){
			
			if(confirm('Are you sure you want to delete this comment?')==false)

			return false;
	
			e.preventDefault();
			var parent  = $(this).parent();
			var c_id =  $(this).attr('id').replace('CID-','');	
			
			$.ajax({

				type: 'get',

				url: 'delete_comment.php?c_id='+ c_id,

				data: '',

				beforeSend: function(){

				},

				success: function(){

					parent.fadeOut(200,function(){

						parent.remove();

					});

				}

			});
		});	
		
		
		//Like Comment
		$('a.c_like').livequery("click", function(e){
			var c_id =  $(this).attr('id').replace('CID-','');	
			//alert(c_id);
			$.post("add_clike.php?c_id="+c_id+"&uid="+<? echo $_SESSION["uid"];?>, {
	
				}, function(response){					
					$('#likeComment' + c_id).find('span:first').empty().append(response);
				});
			
		});	
		
		/// hover show remove button
		$('.friends_area').livequery("mouseenter", function(e){
			$(this).children("a.delete").show();	
		});	
		$('.friends_area').livequery("mouseleave", function(e){
			$('a.delete').hide();	
		});	
		/// hover show remove button
		
		
		$('a.delete').livequery("click", function(e){

		if(confirm('Are you sure you want to delete this post?')==false)

		return false;

		e.preventDefault();

		var parent  = $(this).parent();

		var temp    = parent.attr('id').replace('record-','');

		var main_tr = $('#'+temp).parent();

			$.ajax({

				type: 'get',

				url: 'delete.php?id='+ parent.attr('id').replace('record-',''),

				data: '',

				beforeSend: function(){

				},

				success: function(){

					parent.fadeOut(200,function(){

						main_tr.remove();

					});

				}

			});

		});

		$('textarea').elastic();

		jQuery(function($){

		   $("#watermark").Watermark("Type Here and Click Send.");
		   $(".commentMark").Watermark("Write a comment...");

		});

		jQuery(function($){

		   $("#watermark").Watermark("watermark","#369");
		   $(".commentMark").Watermark("watermark","#EEEEEE");

		});	

		function UseData(){

		   $.Watermark.HideAll();

		   //Do Stuff

		   $.Watermark.ShowAll();

		}

	});	
	
	$(document).ready(function()
	{
		$(".comment_button").click(function(){
		
		var element = $(this);
		var I = element.attr("id");
		
		$("#CommentPosted"+I).slideToggle(400);
		$(this).toggleClass("active"); 
		
		return false;});
	});


	// ]]>

</script>
<script type="text/javascript">
$(document).ready(function(){

	$(".btn-slide").click(function(){
		$("#panel").slideToggle("slow");
		$(this).toggleClass("active"); return false;
	});
	
	 
});
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>

<body>

<!--<h1>Wall Posting for M@xLearn</h1>-->

	<div align="center">
	
		<? 
		if($courseid > 0 && $user_cat == 2) {	
		?>        
        <form action="" method="post" name="postsForm">
	
		<div class="UIComposer_Box">
	
		<span class="w">
		<textarea class="input" id="watermark" name="watermark" style="height:20px" cols="45"></textarea>
		</span>
	
			<!--<input type="hidden" name="course_id" id="course_id" value="<? echo $_GET["course"];?>">-->
			<br clear="all" />
			
			<div align="left" style="height:30px; padding:10px 5px;">
				
				<span style="float:left">&nbsp;&nbsp;&nbsp;
				&nbsp;
				</span>
		
                <a id="shareButton" style="float:left" class="small button Detail"> Send</a>
                <div id='basic-modal'>
                    <!--<h3>กระดานประกาศข่าวสาร</h3>-->
                   <input type='button' name='basic' value='Help' class='basic'/>
                </div>
                
                <!-- modal content -->
                <div id="basic-modal-content">
                    <h3>Help</h3>
                    <p><img src="img/wall_maxlearn.png"></p>
                </div>
        
                <!-- preload the images -->
                <div style='display:none'>
                    <img src='img/basic/x.png' alt='' />
                </div>
	
			</div>
	
		</div>
	
		</form>
        <?
		}
        ?>
        
	
		<br clear="all" />
	
		<div id="posting" align="center">
	
		<?php		
		//include_once('posts.php?uid='.$uid.'&courseid='.$courseid.'&newsid='.$newsid');		
		//include_once('posts.php?uid=$uid&courseid=$courseid&newsid=$newsid');
        //include_once('posts.php?uid='.$uid.'&courseid='.$courseid.'&newsid='.$newsid);
		//include_once('posts.php');
        ?>
		  
		</div>
	</div>
    
	<!--<div id="panel">-->
	    <div id="posting" align="center">
    <?php		
		//include_once('posts.php?uid='.$uid.'&courseid='.$courseid.'&newsid='.$newsid');		
		//include_once('posts.php?uid=$uid&courseid=$courseid&newsid=$newsid');
        //include_once('posts.php?uid='.$uid.'&courseid='.$courseid.'&newsid='.$newsid);
		//include_once('posts.php?uid='.$uid);
		//echo $cid;
		include_once('posts.php');
        ?>
        </div>
	<!--</div>-->

	<!--<p class="slide"><a href="#" class="btn-slide">View Wall</a></p>-->

<br clear="all" />
<br clear="all" />

 <!-- FaceBook Button Start, Remove Or leave -->
 <br clear="all" /><br clear="all" /><br clear="all" />
			  
			  
</body>

</html>

