
function inscripOnClick(){
		var login    = document.getElementById('loginInscription').value;
		var name     = document.getElementById('nomInscription').value;
		var password = document.getElementById('passwordInscription').value;
		var xhr      = new XMLHttpRequest();
		var params   = new FormData();
		
		params.append('ident', login);
		params.append('name', name);
		params.append('password', password);
		
		xhr.open('POST', 'services/createUser.php', true);
		xhr.onload = function() {
			var object = JSON.parse(this.responseText);
			
			if (object.status == 'error' && object.message != null) {
				document.getElementById('resultInscription').innerHTML = '<span>' + object.message + '</span>';
			}
			else {
				document.getElementById('resultInscription').innerHTML = '<span>Compte activé !</span>';
			}
		};
		xhr.send(params);
}

function loginOnClick(){
		var login    = document.getElementById('loginLogin').value;
		var password = document.getElementById('passwordLogin').value;
		var xhr      = new XMLHttpRequest();
		var params   = new FormData();
		
		params.append('ident', login);
		params.append('password', password);
		
		xhr.open('POST', 'services/login.php', true);
		xhr.onload = function() {
			var object = JSON.parse(this.responseText);
			
			if (object.status == 'error' && object.message != null) {
				document.getElementById('resultLogin').innerHTML = '<span>' + object.message + '</span>';
			}
			else {
				document.getElementById('resultLogin').innerHTML = '<span>Connecté !</span>';
				window.location.reload();
			}
		};
		xhr.send(params);
}


function logoutOnClick(){
		var xhr = new XMLHttpRequest();
		
		xhr.open('POST', 'services/logout.php', true);
		xhr.onload = function() {
			var object = JSON.parse(this.responseText);
			
			if (object.status == 'error' && object.message != null) {
				document.getElementById('resultLogout').innerHTML = '<span>' + object.message + '</span>';
			}
			else {
				window.location.reload();
			}
		};
		xhr.send(null);
}

function uploadAvatarOnClick(){
	var file = document.getElementById('uploadAvatar').files[0];
	var param = new FormData();
	var xhr = new XMLHttpRequest();
	
	document.getElementById('resultUploadAvatar').innerHTML = '<span>Saving...</span>';
	
	param.append('img', file);
	xhr.open('POST', 'services/uploadAvatar.php', true);
	xhr.onload = function() {
		if (this.responseText.startsWith('{'))
			var object = JSON.parse(this.responseText);
		else
			var object = this.responseText;
		if (object.status == 'error' && object.message != null) {
			document.getElementById('resultUploadAvatar').innerHTML = '<span>' + object.message + '</span>';
		}
		else {
			document.getElementById('resultUploadAvatar').textContent = 'Sauvegarder';
			window.location.reload();
		}
	};
	xhr.send(param);
}



function getAvatarOnLoad(size){
	var param   = new FormData();
	var xhr = new XMLHttpRequest();
	param.append('size',size);
	xhr.open('POST', 'services/getAvatar.php', true);
	
	xhr.send(null);
	}

				
function setProfileOnClick(){
	var nomProfil = document.getElementById('nomSetProfile').value;
	var pwdProfil = document.getElementById('passwordSetProfile').value;
	var presentationProfil = document.getElementById('presentationSetProfile').value;
	
	var xhr      = new XMLHttpRequest();
	var params   = new FormData();
	
	params.append('nomProfile', nomProfil);
	params.append('presentationProfile', presentationProfil);
	params.append('passwordProfile', pwdProfil);
	
	xhr.open('POST', 'services/setProfile.php', true);
	
	xhr.onload = function() {
			var object = JSON.parse(this.responseText);
			
			if (object.status == 'error') {
				document.getElementById('resultSetProfile').innerHTML = '<span>' + object.message + '</span>';
			}
			else {
				window.location.reload();
				document.getElementById('resultSetProfile').innerHTML = '<span>Changement effectés !</span>';
			}
	};
	xhr.send(params);
}
	
function followOnClick(){
	var identToFollow = document.getElementById('profil').dataset.identprofil ;

	var xhr      = new XMLHttpRequest();
	var param  = new FormData();
	
	param.append('identToFollow', identToFollow);
	xhr.open('POST', 'services/follow.php', true);
	xhr.onload = function() {
			var object = JSON.parse(this.responseText);
			
			if (object.status != 'error') {
				window.location.reload();
			}
	};
	xhr.send(param);
}

function unfollowOnClick(){
	var identToUnfollow = document.getElementById('profil').dataset.identprofil ;

	var xhr      = new XMLHttpRequest();
	var param  = new FormData();
	
	param.append('identToUnfollow', identToUnfollow);
	xhr.open('POST', 'services/unfollow.php', true);
	xhr.onload = function() {
			var object = JSON.parse(this.responseText);
			
			if (object.status != 'error') {
				window.location.reload();
			}
	};
	xhr.send(param);
}
	
function postOnClick(){
	var msg    = document.getElementById('message').value;
	var xhr      = new XMLHttpRequest();
	var param   = new FormData();
	
	param.append('message', msg);
	
	xhr.open('POST', 'services/postMessage.php', true);
		xhr.onload = function() {
			var object = JSON.parse(this.responseText);
			
			if (object.status == 'error' && object.message != null) {
				document.getElementById('resultMessage').innerHTML = '<span>' + object.message + '</span>';
			}
			else {
				document.getElementById('resultMessage').innerHTML = '<span>Message posté !</span>';
				document.getElementById('message').value = "" ;
			}
		};
		xhr.send(param);	
}
