<!DOCTYPE html>
<html>
<head>
<title>
Qwurks!
</title>
<link rel="stylesheet" type="text/css" href="css/main.css">
<link rel="stylesheet" type="text/css" href="css/dae.css">

</head>

<body>
<?php include_once 'includes/analyticstracking.php'; ?>

<div id="wrapper">

<?php include 'includes/header.php'; ?>
<?php include 'includes/lcolumn.php'; ?>
<?php include 'includes/rcolumn.php'; ?>

<div id="content">
<?php// include 'includes/adbanner1.php'; ?>
<!-- **************   Don't change anything above this line EXCEPT the title!     ************** -->

<?php
$dataArray = json_decode(file_get_contents('data.json'), true); //read file contents

$index = isset($_GET['index']) ? $_GET['index'] : 1;

	echo '<div class="record" id="record'.$dataArray[$index]["index"].'">'; // opening tags for each record div
	echo '<div class="submittedby">Submitted by: '.$dataArray[$index]["submituser"].'</div>'; // submitted by div
	echo '<div class="question">'.$dataArray[$index]["question"].'</div>'; // question
	echo '<div class="explanation">'.$dataArray[$index]["explanation"].'</div>'; // explanation div
	echo '<div class="ansbuttons">'; // opening tag for both answer buttons
	echo '<div class="yesbutton" data-id="'.$dataArray[$index]["index"].'" id="yesbutton'.$dataArray[$index]["index"].'">Yes</div>'; // yes button
	echo '<div class="nobutton" data-id="'.$dataArray[$index]["index"].'" id="nobutton'.$dataArray[$index]["index"].'">No</div>'; // no button
	echo '</div>'; // closing tag for both answer buttons
	if ($dataArray[$index]["totalvotes"] == 1) {
		echo '<div class="totalvotes" id="totalvotes'.$dataArray[$index]["index"].'">'.$dataArray[$index]["totalvotes"].' vote</div><br>'; // show total votes so far
	} else {
		echo '<div class="totalvotes" id="totalvotes'.$dataArray[$index]["index"].'">'.$dataArray[$index]["totalvotes"].' votes</div><br>'; // show total votes so far
	}
	echo '<div class="votebars" id="votebar'.$dataArray[$index]["index"].'">';
	echo '<div class="yesvotebars" id="yesvotebar'.$dataArray[$index]["index"].'"></div>';
	echo '<div class="novotebars" id="novotebar'.$dataArray[$index]["index"].'"></div>';
	echo '</div>'; //closing tag for votebars
	$commentsnum = count($dataArray[$index]["comments"]);
	if ($commentsnum == 1) {
		echo '<div class="commentsnum" id="commentsnum'.$dataArray[$index]["index"].'">'.$commentsnum.' comment</div>';
	} else {
		echo '<div class="commentsnum" id="commentsnum'.$dataArray[$index]["index"].'">'.$commentsnum.' comments</div>';
	}
	echo '<div class="commentinputwrap" id="commentinputwrap'.$dataArray[$index]["index"].'">'; // open tag for comment input
		echo '<textarea placeholder="Your name or nickname here" maxlength="30" class="nicknameinput" id="nicknameinput'.$dataArray[$index]["index"].'"></textarea>';
		echo '<textarea placeholder="Your comment here" maxlength="300" class="commentinput" id="commentinput'.$dataArray[$index]["index"].'"></textarea>';
		echo '<button class="commentsubmit" data-id="'.$dataArray[$index]["index"].'" id="commentsubmit'.$dataArray[$index]["index"].'">Comment</button>';
	echo '</div>'; // closing tag for comment input wrap
	echo '<div class="commentboxsingle" id="commentbox'.$dataArray[$index]["index"].'">'; // opening tag for commentbox div
	echo '</div>'; // closing tag for commentbox div
	if ($commentsnum == 0) {
		echo '<script>document.getElementById("commentbox'.$dataArray[$index]["index"].'").innerHTML = "There aren\'t any comments yet. Be the first!"</script>';
	}
	echo '</div>'; // closing tag for record div


?>

<script>
alreadyvoted = new Array();
var index = <?php echo $index; ?>;
//alert("index is: " + index);

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

function onLoad() {
//	loaded = getCookie("loaded");
//	alert("from JS, loaded is: " + loaded);
	voted = getCookie("voted");
//	alert("from JS, voted is: " + voted);
	votes = getCookie("votes");
//	alert("from JS, votes is: " + votes);

//	loadedArray = loaded.split(",");
	votedArray = voted.split(",");
	votesArray = votes.split(",");
	
	addVote = "yes";
	for (j=0; j<votedArray.length; j++) {
		if (index == votedArray[j]) {
			addVote = "no";
//			alert("Match! index is: " + index + ", and votedArray[j] is: " + votedArray[j]);
			showVotebars(votesArray[j], index, addVote);
		}
	}
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
	objDiv.scrollTop = objDiv.scrollHeight;
	});
}

function showVotebars(vote, dataid, addVote) {
	GetData(function(data) { //callback function to AJAX that reads JSON file
		var obj = JSON.parse(data);
		var yesses = obj[dataid].yesnum;
		var nos = obj[dataid].nonum;
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
		length = obj[dataid].comments.length;
		obj[dataid].comments = obj[dataid].comments.reverse();
		document.getElementById("commentbox" + dataid).innerHTML = "";
		if (length == 0) {
			document.getElementById("commentbox" + dataid).innerHTML = "There aren't any comments yet. Be the first to comment!";
		}
		for (x=0; x<length; x++) {
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
			
			if (length == 1) {
				document.getElementById("commentsnum" + dataid).innerHTML = length + " comment";
			} else {
				document.getElementById("commentsnum" + dataid).innerHTML = length + " comments";
			}
		}
}

function expandComments(dataid, yesses, nos, totalvotes, vote) {
		alreadyvoted[dataid] = "yes";
//		alert("alreadyvoted[dataid] = " + alreadyvoted[dataid]);
		var yesperc = yesses/totalvotes*100;
		var noperc = nos/totalvotes*100;
		if (totalvotes == 1) {
			document.getElementById("totalvotes" + dataid).innerHTML = totalvotes + " vote";
		} else {
			document.getElementById("totalvotes" + dataid).innerHTML = totalvotes + " votes";
		}
		document.getElementById("commentbox" + dataid).style.display = "inline-block";
//		document.getElementById("commentbox" + dataid).style.maxHeight = "500px";
		document.getElementById("commentbox" + dataid).style.overflow = "auto";
		document.getElementById("votebar" + dataid).style.display = "inline-block";
		document.getElementById("yesvotebar" + dataid).style.width = yesperc + "%";
		document.getElementById("novotebar" + dataid).style.width = noperc + "%";
		document.getElementById("yesvotebar" + dataid).innerHTML = Math.round(yesperc) + "%";
		document.getElementById("novotebar" + dataid).innerHTML = Math.round(noperc) + "%";
		document.getElementById("commentinputwrap" + dataid).style.display = "inline-block";
		if (vote == "yesbutton") {
			document.getElementById("yesbutton" + dataid).style.color = "yellow";
		} else if (vote == "nobutton") {
			document.getElementById("nobutton" + dataid).style.color = "yellow";
		}
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
<?php// include 'includes/adbanner2.php'; ?>
</div>

<?php include 'includes/footer.php'; ?>
</div>
</body>
</html>