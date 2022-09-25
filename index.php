<?php
	// force refresh on load - not from cache
		if(!session_id()) {
				@session_start();   
		}
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: ' . gmdate( 'D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache'); 

		if(isset($_SESSION['form_submitted'])) {
				unset($_SESSION['form_submitted']);
				header('Location: ?' . uniqid());
				#header('Refresh: 0');
		}
?>


<!DOCTYPE html>
<html>
<head>
<title>
Qwurks!
</title>
<!--<link rel="stylesheet" type="text/css" href="css/main.css">-->
<link rel="stylesheet" type="text/css" href="css/dae.css">
<meta charset="UTF-8"> 
</head>

<body>
<?php 
//	include_once 'includes/analyticstracking.php'; 
?>

<div id="wrapper">

<?php include 'includes/header.php'; ?>
<?php include 'includes/lcolumn.php'; ?>
<?php include 'includes/rcolumn.php'; ?>

<div id="content">
<?
//	php include 'includes/adbanner1.php';
?>
<!-- **************   Don't change anything above this line EXCEPT the title!     ************** -->

<?php
	
$json = json_decode(file_get_contents('data.json'), true); //read file contents

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
	
$dataArray = $json;

if ($sort == "newest") {
	$dataArray = array_reverse($dataArray); // reverse objects so newest is first
} elseif ($sort == "oldest") {
	$dataArray = $json;
} elseif ($sort == "mostvotes") {
	function cmp($a, $b) {
		return $b['totalvotes'] - $a['totalvotes'];
	}
	usort($dataArray, "cmp");
} elseif ($sort == "leastvotes") {
	function cmp($a, $b) {
		return $a['totalvotes'] - $b['totalvotes'];
	}
	usort($dataArray, "cmp");
}

//echo '<script>alert("sorting by: '.$sort.'")</script>';


$arrLength = count($dataArray);

$pagenum = isset($_GET['page']) ? $_GET['page'] : 1;
//echo '<script>alert("you are now on page number: '.$pagenum.'")</script>';

if (!isset($pagenum)) { // if pagenum is not set (new visit), pagenum = 1
	$pagenum = 1;
//	echo '<script>alert("you are on page number: '.$pagenum.'")</script>';
} else { // if pagenum is already set, then just retrieve it to know what page you're on

}

if ($arrLength > $pagenum*10) {
	$lastreconpage=$pagenum*10;
} else {
	$lastreconpage=$arrLength;
}

//echo '<script>var cookieLoaded;</script>';
//echo '<script>document.cookie = "loaded=;"</script>';
echo '<script>cookieLoaded = "loaded="</script>'; // init cookie for records loading
//echo '<script>alert("There are '.$arrLength.' records in total")</script>'; //displays number of records

for($i=$pagenum*10-10;$i<$lastreconpage;$i++) { // pages with 10 records works except for last page
	echo '<script>cookieLoaded = cookieLoaded + '.$dataArray[$i]["index"].' + ","</script>'; //add records to cookie as they load
	echo '<script>document.cookie = cookieLoaded</script>';
//	echo '<script>alert("loaded with php: " + document.cookie)</script>';
	echo '<div class="record" id="record'.$dataArray[$i]["index"].'">'; // opening tags for each record div
	echo '<div class="submittedby">Submitted by: '.$dataArray[$i]["submituser"].'</div>'; // submitted by div
	echo '<div class="question">'.$dataArray[$i]["question"].'</div>'; // question
	echo '<div class="explanation">'.$dataArray[$i]["explanation"].'</div>'; // explanation div
	echo '<div class="ansbuttons">'; // opening tag for both answer buttons
	echo '<div class="yesbutton" data-id="'.$dataArray[$i]["index"].'" id="yesbutton'.$dataArray[$i]["index"].'">Yes</div>'; // yes button
	echo '<div class="nobutton" data-id="'.$dataArray[$i]["index"].'" id="nobutton'.$dataArray[$i]["index"].'">No</div>'; // no button
	echo '</div>'; // closing tag for both answer buttons
	if ($dataArray[$i]["totalvotes"] == 1) {
		echo '<div class="totalvotes" id="totalvotes'.$dataArray[$i]["index"].'">'.$dataArray[$i]["totalvotes"].' vote</div><br>'; // show total votes so far
	} else {
		echo '<div class="totalvotes" id="totalvotes'.$dataArray[$i]["index"].'">'.$dataArray[$i]["totalvotes"].' votes</div><br>'; // show total votes so far
	}
	echo '<div class="votebars" id="votebar'.$dataArray[$i]["index"].'">';
	echo '<div class="yesvotebars" id="yesvotebar'.$dataArray[$i]["index"].'"></div>';
	echo '<div class="novotebars" id="novotebar'.$dataArray[$i]["index"].'"></div>';
	echo '</div>'; //closing tag for votebars
	$commentsnum = count($dataArray[$i]["comments"]);
	if ($commentsnum == 1) {
		echo '<div class="commentsnum" id="commentsnum'.$dataArray[$i]["index"].'">'.$commentsnum.' comment</div>';
	} else {
		echo '<div class="commentsnum" id="commentsnum'.$dataArray[$i]["index"].'">'.$commentsnum.' comments</div>';
	}
	echo '<div class="commentbox" id="commentbox'.$dataArray[$i]["index"].'">'; // opening tag for commentbox div
	echo '</div>'; // closing tag for commentbox div

	if ($commentsnum == 0) {
		echo '<a href="allcomments.php?index='.$dataArray[$i]["index"].'" class="allcomments" id="allcomments'.$dataArray[$i]["index"].'"><div>Start the discussion!</div></a>';
	} else {
		echo '<a href="allcomments.php?index='.$dataArray[$i]["index"].'" class="allcomments" id="allcomments'.$dataArray[$i]["index"].'"><div>Join the discussion!</div></a>';
	}

	if ($commentsnum == 0) {
		echo '<script>document.getElementById("commentbox'.$dataArray[$i]["index"].'").innerHTML = "There aren\'t any comments yet. Be the first!"</script>';
	}	
	echo '</div>'; // closing tag for record div
//	echo '<script>alert("The id of the yes/no button is '.$dataArray[$i]["index"].'")</script>';
//	echo '<script>alert("The array index is '.$i.'")</script>';
} 

// show pagination buttons
echo '<div class="pagebuttons">';
if ($pagenum>10) {
	echo '<a href=?sort='.$sort.'&page='.($pagenum-10).'><div class="pagebutton">'.($pagenum-10).'</div></a>';
}
if ($pagenum>2) {
	echo '<a href=?sort='.$sort.'&page='.($pagenum-2).'><div class="pagebutton">'.($pagenum-2).'</div></a>';
}
// this will show a button for the next page if there are enough records left
if ($pagenum>1) {
	echo '<a href=?sort='.$sort.'&page='.($pagenum-1).'><div class="pagebutton">'.($pagenum-1).'</div></a>';
}
echo '<div id="currpagebutton" class="pagebutton">'.$pagenum.'</div>'; // button for current page
//echo '<script>alert("There are '.$arrLength.' records and you are on page '.$pagenum.'.")</script>';
// this will show a button for the next page if there are enough records left
if ($arrLength>$pagenum*10 && $pagenum<$arrLength/$pagenum) {
	echo '<a href=?sort='.$sort.'&page='.($pagenum+1).'><div class="pagebutton">'.($pagenum+1).'</div></a>';
}
// this will show a button for the page after next if there are enough records left
if ($arrLength>$pagenum*10+10 && $pagenum<$arrLength/($pagenum+1)) {
	echo '<a href=?sort='.$sort.'&page='.($pagenum+2).'><div class="pagebutton">'.($pagenum+2).'</div></a>';
}
// this will show a button for page+10 if there are enough records left
//if ($arrLength>$pagenum*10+100 && $pagenum<$arrLength/($pagenum+1)) {
//	echo '<a href=?sort='.$sort.'&page='.($pagenum+10).'><div class="pagebutton">'.($pagenum+10).'</div></a>';
//}
echo '</div>';


//var_dump($dataArray);
?>

<script>
alreadyvoted = new Array();

// this is how you add event listener for a CLASS!
var ybutton = document.querySelectorAll('div.yesbutton');
for (var i=0; i<ybutton.length; i++) {
	ybutton[i].addEventListener("click", vote, false);
}

var nbutton = document.querySelectorAll('div.nobutton');
for (var i=0; i<nbutton.length; i++) {
	nbutton[i].addEventListener("click", vote, false);
}

var combutton = document.querySelectorAll('button.commentsubmit');
for (var i=0; i<combutton.length; i++) {
	combutton[i].addEventListener("click", addComment, false);
}

function vote(e) {
	var vote = e.target.getAttribute("class");
	var dataid = e.target.getAttribute("data-id");
//	alert("voted dataid: " + voted[dataid]);
	if (alreadyvoted[dataid]!="yes") { // prevents user from voting twice on the same question
		addVote = "yes";
		showVotebars(vote, dataid, addVote);
		saveToCookie(vote, dataid);
	} else {
//		alert("Sorry. You can only vote once.");
		return;
	}
}

function saveToCookie(vote, dataid) {
	var voted = getCookie("voted");
	var votes = getCookie("votes");
//	alert("voted = " + voted + " and votes = " + votes);
	if (voted != "" && voted != null) { // if there is data in "voted"
		voted = voted + "," + dataid;
		votes = votes + "," + vote;
	} else { // if no previous data in cookie
		voted = dataid;
		votes = vote;
	}
//	alert("This is the cookie. Voted: " + voted + ", and votes: " + votes);
	var d = new Date();
	var exdays = 90;
	d.setTime(d.getTime() + (exdays*24*60*60*1000));
	var expires = "expires="+d.toUTCString();
	document.cookie = "voted=" + voted + "; " + expires;
	document.cookie = "votes=" + votes + "; " + expires;
}

function saveVote(vote, dataid) {
//alert("saving vote now!");
 // 1. Create XHR instance - Start
    var xhr;
    if (window.XMLHttpRequest) {
        xhr = new XMLHttpRequest();
    }
    else if (window.ActiveXObject) {
        xhr = new ActiveXObject("Msxml2.XMLHTTP");
    }
    else {
        throw new Error("Ajax is not supported by this browser");
    }
    // 1. Create XHR instance - End
	
	 // 2. Define what to do when XHR feed you the response from the server - Start
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status == 200 && xhr.status < 300) {
//                alert(xhr.responseText);
            }
        }
    }
    // 2. Define what to do when XHR feed you the response from the server - Start

    // 3. Specify your action, location and Send to the server - Start 
    xhr.open('POST', 'savevote.php');
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("vote=" + vote + "&id=" + dataid);
    // 3. Specify your action, location and Send to the server - End
}

function showVotebars(vote, dataid, addVote) {
//	alert("vote, dataid and addVote are: " + vote + " " + dataid + " " + addVote);
	GetData(function(data) { //callback function to AJAX that reads JSON file
		var obj = JSON.parse(data);
		var yesses = obj[dataid].yesnum;
		var nos = obj[dataid].nonum;
//		alert("From file... Yesses: " + obj[dataid].yesnum + "<br>Nos: " + obj[dataid].nonum);
		var totalvotes = obj[dataid].totalvotes;
		if (addVote == "yes") {
			var totalvotes = totalvotes + 1;
			if (vote == "yesbutton") {
				yesses = yesses + 1;
			} else if (vote == "nobutton") {
				nos = nos + 1;
			}
			saveVote(vote, dataid);
		}
		//		document.getElementById(vote + dataid).style.padding = "5px";
		loadComments(obj, dataid);
		expandComments(dataid, yesses, nos, totalvotes, vote);
	});
}

function loadComments (obj, dataid) {
//		alert("loadComments called");
		length = obj[dataid].comments.length;
		obj[dataid].comments = obj[dataid].comments.reverse();
		document.getElementById("commentbox" + dataid).innerHTML = "";
		if (length == 0) {
			document.getElementById("commentbox" + dataid).style.color = "dimgrey";
			document.getElementById("commentbox" + dataid).innerHTML = "There aren't any comments yet. Be the first to comment!";
		}
		if (length > 5) { // checks to see if more than 5 comments
			var count = 5;
		} else {
			var count = length; // if 5 comments or less, display all of them
		}
		for (x=0; x<count; x++) {
			var commentdiv = document.createElement("div"); // create commentdiv div, append, set properties
			document.getElementById("commentbox" + dataid).appendChild(commentdiv);
			commentdiv.setAttribute("class", "comment");
			commentdiv.setAttribute("id", "comment" + dataid + x);

			var datediv = document.createElement("div"); // create commentuser div, append, set properties
			document.getElementById("comment" + dataid + x).appendChild(datediv);
			datediv.setAttribute("class", "datestamp");
			datediv.setAttribute("id", "datestamp" + dataid + x);
			document.getElementById("datestamp" + dataid + x).innerHTML = obj[dataid].comments[x].date;
			
			var commentcontentdiv = document.createElement("div"); // create commentcontentdiv div, append, set
			document.getElementById("comment" + dataid + x).appendChild(commentcontentdiv);
			commentcontentdiv.setAttribute("class", "commentcontent");
			commentcontentdiv.setAttribute("id", "commentcontent" + dataid + x);
			document.getElementById("commentcontent" + dataid + x).innerHTML = obj[dataid].comments[x].comment;

			var nicknamediv = document.createElement("div"); // create commentuser div, append, set properties
			document.getElementById("comment" + dataid + x).appendChild(nicknamediv);
			nicknamediv.setAttribute("class", "commentuser");
			nicknamediv.setAttribute("id", "commentuser" + dataid + x);
			document.getElementById("commentuser" + dataid + x).innerHTML = obj[dataid].comments[x].user;

//			alert("Index: " + dataid + ", nickname: " + obj[dataid].comments[x].user + ", comment: " + obj[dataid].comments[x].comment + ", date: " + obj[dataid].comments[x].date);

			if (length == 1) {
				document.getElementById("commentsnum" + dataid).innerHTML = length + " comment";
			} else {
				document.getElementById("commentsnum" + dataid).innerHTML = length + " comments";
			}
		}
}

function expandComments(dataid, yesses, nos, totalvotes, vote) {
//		alert("expandComments called");
		alreadyvoted[dataid] = "yes";
//		alert("alreadyvoted[dataid] = " + alreadyvoted[dataid]);
		var yesperc = yesses/totalvotes*100;
		var noperc = nos/totalvotes*100;
		if (totalvotes == 1) {
			document.getElementById("totalvotes" + dataid).innerHTML = totalvotes + " vote";
		} else {
			document.getElementById("totalvotes" + dataid).innerHTML = totalvotes + " votes";
		}
		if (vote == "yesbutton") {
			document.getElementById("yesbutton" + dataid).style.color = "yellow";
//			document.getElementById("nobutton" + dataid).style.visibility = "hidden";
		} else if (vote == "nobutton") {
			document.getElementById("nobutton" + dataid).style.color = "yellow";
//			document.getElementById("yesbutton" + dataid).style.visibility = "hidden";
		}
		document.getElementById("commentbox" + dataid).style.display = "inline-block";
		document.getElementById("allcomments" + dataid).style.display = "inline-block";
//		document.getElementById("commentbox" + dataid).style.maxHeight = "500px";
//		document.getElementById("commentbox" + dataid).style.overflow = "auto";
		document.getElementById("votebar" + dataid).style.display = "inline-block";
		document.getElementById("yesvotebar" + dataid).style.width = yesperc + "%";
		document.getElementById("novotebar" + dataid).style.width = noperc + "%";
		document.getElementById("yesvotebar" + dataid).innerHTML = Math.round(yesperc) + "%";
		document.getElementById("novotebar" + dataid).innerHTML = Math.round(noperc) + "%";
}


function GetData(callback) {
    // 1. Instantiate XHR - Start
    var xhr;
    if (window.XMLHttpRequest) 
        xhr = new XMLHttpRequest();
    else if (window.ActiveXObject) 
        xhr = new ActiveXObject("Msxml2.XMLHTTP");
    else 
        throw new Error("Ajax is not supported by your browser");
    // 1. Instantiate XHR - End

    // 2. Handle Response from Server - Start
    xhr.onreadystatechange = function () {
        if (xhr.readyState < 4) {
//            document.getElementById('record').innerHTML = "Loading...";
        } else if (xhr.readyState === 4) {
            if (xhr.status == 200 && xhr.status < 300) 
//				document.getElementById('content').innerHTML = xhr.responseText;
//				alert(xhr.responseText);
				callback(xhr.responseText);
        }
    }
    // 2. Handle Response from Server - End

    // 3. Specify your action, location and Send to the server - Start    
	myFile = "getdatajson.php";
    xhr.open('POST', myFile, true);
	xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    xhr.send();
    // 3. Specify your action, location and Send to the server - End
}

function addComment(e) {
	var dataid = e.target.getAttribute("data-id");
	var commentfield = document.getElementById("commentinput" + dataid).value;
	var nicknamefield = document.getElementById("nicknameinput" + dataid).value;
	if (commentfield == "") {
		alert("Please enter a comment.");
		return; // do nothing
	}
	if (nicknamefield == "") {
//		alert("Please enter a nickname.");
		nicknamefield = "guest";
	}
	saveComment(dataid, commentfield, nicknamefield, function(data) { //save with callback function to AJAX that reads JSON file
	document.getElementById("commentinput" + dataid).value = "";
	var obj = JSON.parse(data);
	loadComments(obj, dataid);
	var objDiv = document.getElementById("commentbox" + dataid);
//	objDiv.scrollTop = objDiv.scrollHeight; // autoscroll to bottom of commentbox with new comment
	});
}

function saveComment(dataid, commentfield, nicknamefield, callback) {
//alert("saving vote now!");
 // 1. Create XHR instance - Start
    var xhr;
    if (window.XMLHttpRequest) {
        xhr = new XMLHttpRequest();
    }
    else if (window.ActiveXObject) {
        xhr = new ActiveXObject("Msxml2.XMLHTTP");
    }
    else {
        throw new Error("Ajax is not supported by this browser");
    }
    // 1. Create XHR instance - End
	
	 // 2. Define what to do when XHR feed you the response from the server - Start
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status == 200 && xhr.status < 300) {
//			alert(xhr.responseText); // use for debugging by using echo statement in other file if php
			callback(xhr.responseText);
            }
        }
    }
    // 2. Define what to do when XHR feed you the response from the server - Start

    // 3. Specify your action, location and Send to the server - Start 
    xhr.open('POST', 'savecomment.php');
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("commenttext=" + commentfield + "&nicknametext=" + nicknamefield + "&id=" + dataid);
    // 3. Specify your action, location and Send to the server - End
}

function onLoad() {
//	alert("onLoad function has fired");
	loaded = getCookie("loaded");
//	alert("from JS, loaded is: " + loaded);
	voted = getCookie("voted");
//	alert("from JS, votes is: " + voted);
	votes = getCookie("votes");

	loadedArray = loaded.split(",");
	votedArray = voted.split(",");
	votesArray = votes.split(",");

	for (i=0; i<loadedArray.length; i++) {
		for (j=0; j<votedArray.length; j++) {
			if (loadedArray[i] == votedArray[j]) {
//				alert("match found! " + "Loaded " + loadedArray[i] + " = Voted " + votedArray[j]);
				addVote = "no";
				showVotebars(votesArray[j], loadedArray[i], addVote);
			}
		}
	}
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';'); // splits different cookies into a cookie array
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
} 

window.onload = onLoad();

</script>
<!-- **************   Don't change anything below this line!     ************** -->
<?php
//	include 'includes/adbanner2.php'; 
?>
</div>

<?php include 'includes/footer.php'; ?>
</div>
</body>
</html>