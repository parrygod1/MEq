var requestURI = '/php/posts/post_query.php?title=';
var requestQuizURI = '/php/quiz/quiz_search.php?title=';

var searchContainer = document.getElementById('search-container');
var textBox = document.getElementById('searchbar');
var textBoxQuiz = document.getElementById('searchbar-quiz');

resultContainer = document.getElementById('content');

var ajax = null;
var postCount = 0;

textBox.onkeyup = function() {
	var val = this.value;
	val = val.replace(/^\s|\s $/, "");

	if (val !== "") {	
		searchForData(val, 1);
	} else {
		clearResult();
	}
}

textBoxQuiz.onkeyup = function() {
	var val = this.value;
	val = val.replace(/^\s|\s $/, "");

	if (val !== "") {	
		searchForQuiz(val);
	} else {
		clearResult();
	}
}

function searchForData(value, page) {
	if (ajax && typeof ajax.abort === 'function') {
		ajax.abort(); // abort previous requests
	}
	
	clearResult();

	ajax = new XMLHttpRequest(); //php response will be in this variable
	ajax.onreadystatechange = function() {
		if (ajax.readyState === 4 && ajax.status === 200) {
			//console.log(ajax.responseText);
			var json = JSON.parse(ajax.responseText);
			if (json === false) {
				noPosts();	
			} else {
				postCount = json['postCount'];
				showPosts(json['array']);
			}
		}
    }
    ajax.open('GET', requestURI +  value +  '&page=' +  page , true);
	ajax.send();
}

function searchForQuiz(value) {
	if (ajax && typeof ajax.abort === 'function') {
		ajax.abort(); // abort previous requests
	}
	
	clearResult();

	ajax = new XMLHttpRequest(); //php response will be in this variable
	ajax.onreadystatechange = function() {
		if (ajax.readyState === 4 && ajax.status === 200) {
			//console.log(ajax.responseText);
			var json = JSON.parse(ajax.responseText);
			if (json === false) {
				noPosts();	
			} else {
				showQuizzes(json);
			}
		}
    }
    ajax.open('GET', requestQuizURI +  value +  '&page=' +  page , true);
	ajax.send();
}

function showQuizzes(data) {
    console.log(data); 
    for(var i = 0; i < data.length; i++){
        createQuizElement(data[i]);   
	}
	MathJax.Hub.Typeset();
}

function showPosts(data) {
	console.log(data); 
    for(var i = 0; i < data.length; i++){
        createElement(data[i]);   
	}
	createPageNumbers();
	MathJax.Hub.Typeset();
}

function createPageNumbers() {
	let pages = Math.ceil(postCount / 6);
	let pageDiv = document.getElementById('pages');
	let name = textBox.value;
	pageDiv.innerHTML = "";

	for(var i = 0; i < pages; i++) {
		let pageNumber = document.createElement('button');
		pageNumber.innerHTML = i + 1;
		pageNumber.setAttribute("onclick", "searchForData('" + name + "'," + (i + 1) + ')');
		pageDiv.appendChild(pageNumber);
	}
}

function createElement(data){
	let content = document.getElementById("content");
	let bar = document.createElement("hr");
	bar.setAttribute('class', 'search-divider-bar');
	content.appendChild(bar);
	
    let mainDiv = document.createElement("div");
    mainDiv.className = "post";
    descDiv = document.createElement("div");
    descDiv.className = "post-desc";
   
    let link = document.createElement("a");
    link.className = "post-title";
    link.href = 'postpage.php?id=' + data['ID'];
    link.innerText = data['NAME'];
    descDiv.appendChild(link);

    let shortDescDiv = document.createElement("div");
    shortDescDiv.className = "post-shortdesc";
	shortDescDiv.innerText = data['DESCRIPTION'];
    descDiv.appendChild(shortDescDiv);


	let info = document.createElement("div");
	info.setAttribute('class', 'post-info')
	
	let postDate = document.createElement("div");
	postDate.className = "post-date";
	postDate.innerText = "Posted on " + data['CREATED_AT'];
	info.appendChild(postDate);

	let views = document.createElement("div");
	views.className = "post-views";
	views.innerText = "Views:  " + data['VIEWS'];
	info.appendChild(views);

	descDiv.appendChild(info);

    mainDiv.appendChild(descDiv);
	content.appendChild(mainDiv);

	let bar2 = document.createElement("hr");
	bar2.setAttribute('class', 'search-divider-bar');
	content.appendChild(bar2);
}

function createQuizElement(data){
	let content = document.getElementById("content");
	let bar = document.createElement("hr");
	bar.setAttribute('class', 'search-divider-bar');
	content.appendChild(bar);
	
    let mainDiv = document.createElement("div");
    mainDiv.className = "post";
    descDiv = document.createElement("div");
    descDiv.className = "post-desc";
   
    let link = document.createElement("a");
    link.className = "post-title";
    link.href = 'quiz.php?id=' + data['ID'];
    link.innerText = data['QUIZ_TITLE'];
    descDiv.appendChild(link);

	let info = document.createElement("div");
	info.setAttribute('class', 'post-info')
	
	let postDate = document.createElement("div");
	postDate.className = "post-date";
	postDate.innerText = "Posted on " + data['CREATED_AT'];
	info.appendChild(postDate);

	descDiv.appendChild(info);

    mainDiv.appendChild(descDiv);
	content.appendChild(mainDiv);

	let bar2 = document.createElement("hr");
	bar2.setAttribute('class', 'search-divider-bar');
	content.appendChild(bar2);
}

function clearResult() {
	resultContainer.innerHTML = "";
	page = 1;
}

function noPosts() {
	resultContainer.innerHTML = "No Posts";
}

function enableSearch() {
	disableQuizSearch();
	textBox.setAttribute('style', 'display: initial');
	searchContainer.setAttribute('style', 'display: block');
	document.getElementById('pages').setAttribute('style', 'display: block');
	clearResult();
	searchAll();
}

function disableSearch(){
	textBox.setAttribute('style', 'display: none');
	searchContainer.setAttribute('style', 'display: none');
}

function searchTop(){
	disableSearch();
	searchForData('*top*');
	document.getElementById('pages').setAttribute('style', 'display: none');
}

function searchNew(){
	disableSearch();
	searchForData('*new*');
	document.getElementById('pages').setAttribute('style', 'display: none');
}

function searchAll(){
	searchForData('', 1);
}

function enableQuizSearch(){
	disableSearch();
	textBoxQuiz.setAttribute('style', 'display: initial');
	searchContainer.setAttribute('style', 'display: block');
	document.getElementById('pages').setAttribute('style', 'display: none');
	clearResult();
	searchForQuiz('*new*');
}

function disableQuizSearch(){
	textBoxQuiz.setAttribute('style', 'display: none');
	searchContainer.setAttribute('style', 'display: none');
}

searchNew();