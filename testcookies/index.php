<!DOCTYPE html>
<html>
<head>
<script>

function addToCookie(postNum) {
	var x = document.cookie;
	alert("cookie before click: " + x);
	if (x == "" || x == null) {
		alert("cookie was empty before click!");
		x = "voted=" + postNum;
		document.cookie = x;
		alert("cookie after click: " + document.cookie);
	} else {
		alert("cookie was not empty!");
		x = x + "," + postNum;
		document.cookie = x;
		alert("cookie is now: " + document.cookie);
	}
}

document.cookie="voted=4";
alert(document.cookie);

function seeCookie() {
	var x = document.cookie;
	alert("This is the cookie: " + x);
	x = x.replace("voted=", "");
	y = x.split(",");
	for (i=0; i<y.length; i++) {
		alert("Value " + i + " of the array is " + y[i]);
	}
}

function resetCookie() {
	document.cookie = "voted=;expires=Thu, 01 Jan 1970 00:00:00 UTC";
}

</script>
</head>
<body onload="getCookie()">
<button onclick="setCookie()">Click me to set a cookie!</button>
<button onclick="resetCookie()">Click me to reset a cookie!</button>
<button onclick="seeCookie()">Click me to see the cookie!</button>
<button onclick="addToCookie(1)">1</button>
<button onclick="addToCookie(2)">2</button>
<button onclick="addToCookie(3)">3</button>
<button onclick="addToCookie(4)">4</button>
<button onclick="addToCookie(5)">5</button>
<button onclick="addToCookie(6)">6</button>
<button onclick="addToCookie(7)">7</button>
<button onclick="addToCookie(8)">8</button>
<button onclick="addToCookie(9)">9</button>
<button onclick="addToCookie(0)">0</button>

</body>
</html>