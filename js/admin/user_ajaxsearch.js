var requestURI = '/meq/php/admin/user_query.php?';
var postURI = '/meq/php/admin/user_update.php?';

var textBoxName = document.getElementById('searchbar-name');
var textBoxId = document.getElementById('searchbar-id');

resultContainer = document.getElementById('search-results');

var ajax = null;

textBoxName.onkeyup = searchInputChanged;
textBoxId.onkeyup = searchInputChanged;

function searchInputChanged (){
    let valname = textBoxName.value;
    let valid = textBoxId.value;

	valname = valname.replace(/^\s|\s $/, "");
  valid = valid.replace(/^\s|\s $/, "");

	if (valname !== '' || valid !== '' ) {	
		searchForData(valname, valid);
	} else {
		clearResult();
	}
}

function postData(action, id) {
	if (ajax && typeof ajax.abort === 'function') {
		ajax.abort(); // abort previous requests
	}

	ajax = new XMLHttpRequest(); //php response will be in this variable
	ajax.onreadystatechange = function() {
		if (ajax.readyState === 4 && ajax.status === 200) {
			console.log("yes");
		}
  }

	if(action !== '' && id !== ''){
		ajax.open('POST', postURI + 'action=' + action + '&id=' + id , true);
		ajax.send();
	} 
}

function searchForData(valueName, valueId) {
	if (ajax && typeof ajax.abort === 'function') {
		ajax.abort(); // abort previous requests
	}

	clearResult();

	ajax = new XMLHttpRequest(); //php response will be in this variable
	ajax.onreadystatechange = function() {
		if (ajax.readyState === 4 && ajax.status === 200) {
			var json = JSON.parse(ajax.responseText);
			if (json === false) {
				noUsers();
			} else {
				showUsers(json);
			}
		}
    }

    let requestHeader = '';

	console.log(valueName, valueId);
	if(valueName !== '' && valueId !== ''){
			requestHeader += 'name=' + valueName + '&id=' + parseInt(valueId);
	} else if(valueId !== ''){
		requestHeader += 'id=' + parseInt(valueId);
	}
	else if(valueName !== ''){
			requestHeader += 'name=' + valueName;
	}

    ajax.open('GET', requestURI + requestHeader , true);
	ajax.send();
}

function showUsers(data) {
    console.log(data); 
    for(var i = 0; i < data.length; i++){
        createElement(data[i]);   
	}
}

function createElement(data){
    let box = document.createElement('div');
    box.setAttribute('class', 'admin-box');

    let content = '<a class="profile-link" href="profilepage.php?id=' + data['ID'] + '"><div class="admin-box-top">' + data['USERNAME'] +
										'</div></a><div class="admin-box-panel"> id: ' + data['ID'] + '<div><button onclick="postData(\'admin\',' + data['ID'] + 
										')">Give admin</button> <button onclick="postData(\'ban\',' + data['ID'] + 
										')">Ban</button> <button onclick="postData(\'unban\',' + data['ID'] + ')">Unban</button></div></div>';

    box.innerHTML = content;
    resultContainer.appendChild(box);
}

function clearResult() {
	resultContainer.innerHTML = "";
}

function noUsers() {
	resultContainer.innerHTML = "No Users";
}