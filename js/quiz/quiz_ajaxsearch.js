var requestQuizURI = '/php/quiz/quiz_search.php?title=';

var toggle = null; //bool to see if we are actually searching
var textBox = document.getElementById('searchbar');
resultContainer = document.getElementById('content');

var ajax = null;
var page = 0;

textBox.onkeyup = function() {
    if(toggle == true){
    	var val = this.value;
    	val = val.replace(/^\s|\s $/, "");

    	if (val !== "") {	
    		searchForData(val);
    	} else {
    		clearResult();
        }
    }
    toggle = false;
}

function searchForQuiz(value) {
	if (ajax && typeof ajax.abort === 'function') {
		ajax.abort(); // abort previous requests
	}

	clearResult();

	ajax = new XMLHttpRequest(); //php response will be in this variable
	ajax.onreadystatechange = function() {
		if (ajax.readyState === 4 && ajax.status === 200) {
			var json = JSON.parse(ajax.responseText);
			if (json === false) {
				noQuiz();	
			} else {
				showQuizzes(json);
			}
		}
    }
    ajax.open('GET', requestQuizURI +  value +  '&page=' +  page , true);
	ajax.send();
}

function showPosts(data) {
    console.log(data); 
    for(var i = 0; i < data.length; i++){
        createElement(data[i]);   
	}
	MathJax.Hub.Typeset();
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
    link.innerText = data['QUIZ_TITLE'];
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

function clearResult() {
	resultContainer.innerHTML = "";
	page = 0;
}

function noQuiz() {
	searchNewQuiz();
}

function searchNewQuiz(){
	searchForData('*new*');
}

function enableQuizSearch(){
    textBox.setAttribute('style', 'display: initial');
	clearResult();
    toggle = true;
}