
function searchUser(){
	var msgAll = document.getElementsByClassName('msg') ;
	var profilAll = document.getElementsByClassName('listProfil') ;
	var toSearch = document.getElementById('searchUser').value ;
	var find = false ;
	
	for(var i=0; i<msgAll.length; i++){
		if(msgAll[i].dataset.id.indexOf(toSearch)!=-1 || msgAll[i].dataset.nom.indexOf(toSearch)!=-1){
			msgAll[i].style.display = 'block'
			find = true ;
		}
		else{
			msgAll[i].style.display = 'none' ;
		}
	}
	if(find==false){
		document.getElementById('noResultMessage').innerHTML='<span>Aucun résultat trouvé</span>';
	}
	else{
		document.getElementById('noResultMessage').innerHTML= '' ;
	}
	
	find = false ;
	
	for(var i=0; i<profilAll.length; i++){
		if(profilAll[i].dataset.id.indexOf(toSearch)!=-1 || profilAll[i].dataset.nom.indexOf(toSearch)!=-1){
			profilAll[i].style.display = 'block'
			find = true ;
		}
		else{
			profilAll[i].style.display = 'none' ;
		}
	}
	if(find==false){
		document.getElementById('noResultProfil').innerHTML='<span>Aucun résultat trouvé</span>';
	}
	else{
		document.getElementById('noResultProfil').innerHTML= '' ;
	}
}

