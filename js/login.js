/** move this to different file is used on other page **/
function IsEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}
function IsPhone(phone) {
  var regex = /\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/;
  return regex.test(phone);
}
function IsZipCode(zipcode) {
  var regex = /^\d{5}(?:[\s-]\d{4})?$/ ;
  return regex.test(zipcode);
}
/** move this to different file is used on other page **/

/* FACEBOOK */
// This is called with the results from from FB.getLoginStatus().
function statusChangeCallback(response) {
	//console.log('statusChangeCallback');
	//console.log(response);
	// The response object is returned with a status field that lets the
	// app know the current login status of the person.
	// Full docs on the response object can be found in the documentation
	// for FB.getLoginStatus().
	if (response.status === 'connected') {
		// Logged into your app and Facebook.
		testAPI();
	} else if (response.status === 'not_authorized') {
		// The person is logged into Facebook, but not your app.
		//document.getElementById('status').innerHTML = 'Please log ' +
		//'into this app.';
		alert('Please authorize this app.');
		enable_buttons();
	} else {
		// The person is not logged into Facebook, so we're not sure if
		// they are logged into this app or not.
		//document.getElementById('status').innerHTML = 'Please log ' +
		//'into Facebook.';
		alert('Please login to facebook.');
		enable_buttons();
	}
}

// This function is called when someone finishes with the Login
// Button.  See the onlogin handler attached to it in the sample
// code below.
function checkLoginState() {
	FB.getLoginStatus(function(response) {
		statusChangeCallback(response);
	});
}

window.fbAsyncInit = function() {
	FB.init({
		appId      : fb_app_id,
		cookie     : true,  // enable cookies to allow the server to access 
		                // the session
		xfbml      : true,  // parse social plugins on this page
		version    : 'v2.0' // use version 2.0
	});

// Now that we've initialized the JavaScript SDK, we call 
// FB.getLoginStatus().  This function gets the state of the
// person visiting this page and can return one of three states to
// the callback you provide.  They can be:
//
// 1. Logged into your app ('connected')
// 2. Logged into Facebook, but not your app ('not_authorized')
// 3. Not logged into Facebook and can't tell if they are logged into
//    your app or not.
//
// These three cases are handled in the callback function.

/*
	FB.getLoginStatus(function(response) {
		statusChangeCallback(response);
	});
*/
};

// Load the SDK asynchronously
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

function fb_login(){
    disable_buttons();
    FB.login(function(response) {
	statusChangeCallback(response);       
    }, {
        scope: 'publish_stream,email'
    });
}

// Here we run a very simple test of the Graph API after login is
// successful.  See statusChangeCallback() for when this call is made.
function testAPI() {
	//console.log('Welcome!  Fetching your information.... ');
	FB.api('/me', function(response) {
		//console.log(response);
		//console.log('Successful login for: ' + response.name);
		document.getElementById('status').innerHTML =
		'Thanks for logging in, ' + response.name + '! Please wait, you will redirect to the page.';
		$.ajax({
				type: "POST",
				url: ajax_url+'fb_login/',
				data: { id:response.id, first_name: response.first_name, last_name: response.last_name, name: response.name, email: response.email, id: response.id, gender: response.gender, fb_link: response.link, timezone: response.timezone, csrf_b2b: $( "input[name='csrf_b2b']" ).val() },
				dataType : "json",
			})
			.done(function( msg ) {
				if ( msg.login == 'true') window.location= msg.go;
		});
	});
}

/* GOOGLE */
(function() {
    var po = document.createElement('script');
    po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/client:plusone.js?onload=render';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(po, s);
  })();

  function render() {
    gapi.signin.render('googleSignInBtn', {
     // 'callback': 'signinCallback',
      'clientid': google_client_id,
      'cookiepolicy': 'single_host_origin',
      'requestvisibleactions': 'http://schema.org/AddAction',
      'scope': 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.profile.emails.read'
    });
    var additionalParams = {
     'callback': signinCallback,
       'clientid': google_client_id,
      'cookiepolicy': 'single_host_origin',
      'requestvisibleactions': 'http://schema.org/AddAction',
      'scope': 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.profile.emails.read'
   };

   // Attach a click listener to a button to trigger the flow.
   var signinButton = document.getElementById('googleSignInBtn');
   signinButton.addEventListener('click', function() {
     disable_buttons();
     gapi.auth.signIn(additionalParams); // Will use page level configuration
   });
  }
  /*
(function() {
	var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
	po.src = 'https://apis.google.com/js/client:plusone.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();
*/
var profile, email;

function signinCallback(authResult) {
	if (authResult) {
		if (authResult['error'] == undefined){
			//toggleElement('signin-button'); // Hide the sign-in button after successfully signing in the user.
			gapi.client.load('plus','v1', loadProfile);  // Trigger request to get the email address.
		} else {
			//console.log('An error occurred');
			alert('An error occurred. Please try again!');
			enable_buttons();
		}
		} else {
			//console.log('Empty authResult');  // Something went wrong
			alert('Empty authResult');
			enable_buttons();
	}
}

function loadProfile(){
	var request = gapi.client.plus.people.get( {'userId' : 'me'} );
	request.execute(loadProfileCallback);
}
 
 function loadProfileCallback(obj) {
	profile = obj;

	// Filter the emails object to find the user's primary account, which might
	// not always be the first in the array. The filter() method supports IE9+.
	email = obj['emails'].filter(function(v) {
		return v.type === 'account'; // Filter out the primary email
	})[0].value; // get the email from the filtered results, should always be defined.
	//console.log(profile);
	//console.log(email);
	$.ajax({
			type: "POST",
			url: ajax_url+'google_login/',
			data: { first_name: obj.name.givenName, last_name: obj.name.familyName, name: obj.displayName, email: email, id: obj.id, gender: obj.gender, g_link: obj.url, picture: obj.image.url, csrf_b2b: $( "input[name='csrf_b2b']" ).val() },
			dataType : "json",
		})
		.done(function( msg ) {
			if ( msg.login == 'true') window.location= msg.go;
	});
  }
  
/* LINKEDIN */ 
function linkedinLogin(){
	disable_buttons();
	IN.User.authorize(function(){
	       onLinkedInAuth();
	   });
}

  function onLinkedInAuth() {
  IN.API.Profile("me")
  .fields("id", "first-name", "last-name", "picture-url", "email-address")
    .result( function(me) {
      var id = me.values[0].id;
      var emailAddress = me.values[0].emailAddress;
      var firstName = me.values[0].firstName;
      var lastName = me.values[0].lastName;
      var pictureUrl = me.values[0].pictureUrl;
      
      $.ajax({
			type: "POST",
			url: ajax_url+'linkedin_login/',
			data: { first_name: firstName, last_name: lastName, email: emailAddress, id: id, picture: pictureUrl, csrf_b2b: $( "input[name='csrf_b2b']" ).val() },
			dataType : "json",
		})
		.done(function( msg ) {
			if ( msg.login == 'true') window.location= msg.go;
	});
      // AJAX call to pass back id to your server
     // console.log(me);
    });
}

//validate login form
function validate_login(){
	if ( jQuery('#name').val() == '' ){
		alert('Please enter your name!');
		jQuery('#name').focus();
		return false;
	}
	
	if ( jQuery('#business').length > 0 && jQuery('#business').val() == '' ){
		alert('Please enter your Business Name!');
		jQuery('#business').focus();
		return false;
	}
	
	if ( jQuery('#email').val() == '' || ! IsEmail(jQuery('#email').val()) ){
		alert('Please enter valid email address!');
		jQuery('#email').focus();
		return false;
	}
	if ( jQuery('#phone').length > 0 && ( jQuery('#phone').val() == '' || ! IsPhone( jQuery('#phone').val() ) ) ){
		alert('Please enter valid phone number!');
		jQuery('#phone').focus();
		return false;
	}
	if ( jQuery('#zipcode').length > 0 && ( jQuery('#zipcode').val() == '' || ! IsZipCode( jQuery('#zipcode').val() ) ) ){
		alert('Please enter valid zip code!');
		jQuery('#zipcode').focus();
		return false;
	}
	jQuery('#login_form').submit();
}

jQuery(window).load(function(){
	jQuery('.socbtns').show();
	enable_buttons();
});

function disable_buttons(){
	jQuery('.btn, input').attr('disabled','disabled');
}

function enable_buttons(){
	jQuery('.btn, input').removeAttr('disabled');
}