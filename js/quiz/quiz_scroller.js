var requestURI = 'php/quiz/quiz_query.php?id=';
var postURI = 'php/quiz/quiz_updatescore.php'

var questionArray = []
var questionSize = 0;
var parentDocumentName = document.getElementById('quiz-title');
var questionTitle = document.getElementById('question-title');
var questionDesc = document.getElementById('question-desc');
var questionSentence = document.getElementById('actual-question');
var questionGraphic = document.getElementById('graphic');
var counter = document.getElementById('question-counter');
var answerBox = document.getElementById('answer-text');
var checkButton = document.getElementById('button-check');

var ajax = null;
function getData(value){
    if (ajax && typeof ajax.abort === 'function') {
		ajax.abort(); // abort previous requests
	}
    ajax = new XMLHttpRequest(); //php response will be in this variable
	ajax.onreadystatechange = function() {
		if (ajax.readyState === 4 && ajax.status === 200) {
            var json = JSON.parse(ajax.responseText);
			if (json === false || json === null) {
                noData();
			} else {
                parentDocumentName.innerHTML = json['QUIZ_TITLE'];
                showData(json['CONTENT']);
			}
		}
    }
    ajax.open('GET', requestURI +  value, true);
	ajax.send();
}

function postData(){
    if (ajax && typeof ajax.abort === 'function') {
		ajax.abort();
	}
    ajax = new XMLHttpRequest();
	ajax.onreadystatechange = function() {
		if (ajax.readyState === 4 && ajax.status === 200) {
            alert('Quiz completed! ' + ajax.responseText);
		}
    }
    ajax.open('POST', postURI, true);
	ajax.send();
}

function noData(){
    parentDocumentName.textContent = 'Quiz does not exist';
}

function showData(data){
    questionArray = JSON.parse(data);
    questionSize = questionArray.length;
    setQuestionInfo();
    setCheckAnswer();
}

var correctAnswers = 0;

function setCheckAnswer(){
    checkButton.addEventListener("click", function(){
        if(answerBox.value == questionAnswer){
            correctAnswers++;
            if(correctAnswers == questionArray.length){
                postData(); //update user score
            }
            alert("Correct");
        }
        else
            alert("Incorrect");
    })
}

var currentIndex = 0;

function setQuestionInfo(){
    questionTitle.innerHTML = questionArray[currentIndex].title;
    questionDesc.innerHTML = questionArray[currentIndex].description;
    questionSentence.innerHTML = questionArray[currentIndex].question;
    questionGraphic.src = questionArray[currentIndex].graphicpath;
    questionAnswer = questionArray[currentIndex].answer;
    counter.innerHTML = "(" + (currentIndex + 1) + "/" + questionSize + ")";
    let answer = document.getElementById('answer-wrapper');
    answer.setAttribute('style', 'display:block');    
}

document.getElementById("button-next").addEventListener("click", function(){
    if(currentIndex < questionSize-1)
        currentIndex++;
    setQuestionInfo();
    answerBox.value = "";
});

document.getElementById("button-prev").addEventListener("click", function(){
    if(currentIndex > 0)
        currentIndex--;
    setQuestionInfo();
    answerBox.value = "";
});

//runner code
var url = new URL(window.location.href);
var id = url.searchParams.get("id");
getData(id);