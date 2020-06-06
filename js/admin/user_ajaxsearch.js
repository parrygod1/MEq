var requestURI = '/meq/php/admin/user_query.php?';
var postURI = '/meq/php/admin/user_update.php?';

var textBoxName = document.getElementById('searchbar-name');
var textBoxId = document.getElementById('searchbar-id');

resultContainer = document.getElementById('search-results');

var ajax = null;

var usersCount = 0;
var searchButton = document.getElementById('search-button');

searchButton.onclick = searchInputChanged;

function searchInputChanged (){
    let valname = textBoxName.value;
    let valid = textBoxId.value;

	valname = valname.replace(/^\s|\s $/, "");
  valid = valid.replace(/^\s|\s $/, "");

	searchForData(valname, valid, null);
}

function postData(action, id) {
	if (ajax && typeof ajax.abort === 'function') {
		ajax.abort(); // abort previous requests
	}

	ajax = new XMLHttpRequest(); //php response will be in this variable
	ajax.onreadystatechange = function() {
		if (ajax.readyState === 4 && ajax.status === 200) {
			if(ajax.responseText.length > 0){
				alert(ajax.responseText);
			} else {
				alert('Action failed');
			}
		}
  }

	if(action !== '' && id !== ''){
		ajax.open('POST', postURI, true);
		ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajax.send('action=' + action + '&id=' + id);
	} 
}

function searchForData(valueName, valueId, page) {
	if (ajax && typeof ajax.abort === 'function') {
		ajax.abort(); // abort previous requests
	}

	clearResult();

	ajax = new XMLHttpRequest(); //php response will be in this variable
	ajax.onreadystatechange = function() {
		if (ajax.readyState === 4 && ajax.status === 200) {
			var json = JSON.parse(ajax.responseText);
			usersCount = json['usersCount'];
			if (json === false) {
				noUsers();
			} else {
				showUsers(json['array']);
			}
		}
    }

    let requestHeader = '';

	// console.log(valueName, valueId);
	if(page === undefined || page === null) {
		page = 1;
	}

	if(valueName !== '' && valueId !== ''){
			requestHeader += 'name=' + valueName + '&id=' + parseInt(valueId);
	} else if(valueId !== ''){
		requestHeader += 'id=' + parseInt(valueId);
	}
	else if(valueName !== ''){
			requestHeader += 'name=' + valueName + '&page=' + page;
	}
	else {
		requestHeader += 'name=*all*' + '&page=' + page; 
	}

  ajax.open('GET', requestURI + requestHeader , true);
	ajax.send();
}

function createPageNumbers() {
	let pages = Math.ceil(usersCount / 6);
	let pageDiv = document.getElementById('pages');
	let name = (textBoxName.value != "") ? textBoxName.value : '*all*';
	pageDiv.innerHTML = "";

	for(var i = 0; i < pages; i++) {
		let pageNumber = document.createElement('button');
		pageNumber.innerHTML = i + 1;
		pageNumber.setAttribute("onclick", "searchForData('" + name + "', ''," + (i + 1) + ')');
		pageDiv.appendChild(pageNumber);
	}
}

function showUsers(data) {
    console.log(data); 
    for(var i = 0; i < data.length; i++){
        createElement(data[i]);   
		}
		createPageNumbers();
}

function createElement(data){
    let box = document.createElement('div');
    box.setAttribute('class', 'admin-box');

    let content = '<a class="profile-link" href="profilepage.php?id=' + data['ID'] + '"><div class="admin-box-top">' + data['USERNAME'] +
										'</div></a><div class="admin-box-panel"> id: ' + data['ID'] + '<div><button class="actionbtn" id="admin-btn" onclick="postData(\'admin\',' + data['ID'] + 
										')">Give admin</button> <button class="actionbtn" id="ban-btn" onclick="postData(\'ban\',' + data['ID'] + 
										')">Ban</button> <button id="unban-btn" class="actionbtn" onclick="postData(\'unban\',' + data['ID'] + ')">Unban</button></div></div>';

    box.innerHTML = content;
    resultContainer.appendChild(box);
}

function clearResult() {
	resultContainer.innerHTML = "";
}

function noUsers() {
	resultContainer.innerHTML = "No Users";
}