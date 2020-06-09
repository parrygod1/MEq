var requestURI = '/meq/php/quiz/quiz_query.php?id=';
var postURI = '/meq/php/quiz/quiz_updatescore.php'

var questionArray = []
var correctAnswers = [];
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
    for(i=0; i < questionSize; i++){
        correctAnswers.push(false);
    }
    setQuestionInfo();
    setCheckAnswer();
}

function checkFinishedQuiz(){
    for(i=0; i < correctAnswers.length; i++){
        if(correctAnswers[i]===false)
            return false;
    }
    return true;
}

function setCheckAnswer(){
    checkButton.addEventListener("click", function(){
        if(answerBox.value == questionAnswer){
            correctAnswers[currentIndex] = true;
            if(checkFinishedQuiz()){
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
    questionTitle.innerText = questionArray[currentIndex].title;
    questionDesc.innerText = questionArray[currentIndex].description;
    questionSentence.innerText = questionArray[currentIndex].question;
    questionGraphic.src = questionArray[currentIndex].graphicpath;
    questionAnswer = questionArray[currentIndex].answer;
    counter.innerText = "(" + (currentIndex + 1) + "/" + questionSize + ")";
    let answer = document.getElementById('answer-wrapper');
    answer.setAttribute('style', 'display:block');    
}

document.getElementById("button-next").addEventListener("click", function(){
    if(currentIndex < questionSize-1)
        currentIndex++;
    setQuestionInfo();
    MathJax.Hub.Typeset()
    answerBox.value = "";
});

document.getElementById("button-prev").addEventListener("click", function(){
    if(currentIndex > 0)
        currentIndex--;
    setQuestionInfo();
    MathJax.Hub.Typeset()
    answerBox.value = "";
});

//runner code
var url = new URL(window.location.href);
var id = url.searchParams.get("id");
getData(id);