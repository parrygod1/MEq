var questionArray = []
var questionWrapper = document.getElementById("quiz-upload-wrapper");
var inputJSON = document.getElementById("hidden-quiz-JSON");
var arrlen = 0;

class QuestionElement{
    constructor(id)
    {
        this.id = id;
        this.title = "";
        this.description = "";
        this.image = "";
        this.question = "";
        this.answer = "";

        this.mainElem = null;
        this.fileManager = null;
        this.createHTML();
    }

    createFileManager(){
        let fileManager = document.createElement('input');
        this.fileManager = fileManager;

        this.fileManager.setAttribute('class', 'button-upload-file');
        this.fileManager.setAttribute('type', 'file');
        this.fileManager.setAttribute('id', 'image' + this.id);
        this.fileManager.setAttribute('accept', 'image/*');
        this.fileManager.setAttribute('onchange', 'addFiles(' + this.id + ')');
        //class="button-regular" type="file" id="image" accept="image/*" onchange="addFiles()" />
    }

    setEventListeners(){
        this.mainElem.addEventListener('change', (event) => {
            switch (event.target.className) {
                case "quiz-upload-title":
                    this.title = event.target.value;
                    break;
                case "quiz-upload-desc":
                    this.description = event.target.value;
                    break;
                case "quiz-upload-image":
                    this.image = event.target.value;
                    break;
                case "quiz-upload-question":
                    this.question = event.target.value;
                    break;
                case "quiz-upload-answer":
                    this.answer = event.target.value;
                    break;
                default:
                    break;
            }
        });

    }

    createHTML() {
        let mainElem = document.createElement('div');
        mainElem.className = "quiz-upload-entry";
        this.mainElem = mainElem;

        this.createFileManager();
        let label = document.createElement('label');
        label.textContent = "Figure/Image: ";

        let titleElem = document.createElement('input');
        titleElem.className = "quiz-upload-title";
        titleElem.placeholder = "Question title";

        let descElem = document.createElement('input');
        descElem.className = "quiz-upload-desc";
        descElem.placeholder = "Question description";

        let questionElem = document.createElement('input');
        questionElem.required = true;
        questionElem.className = "quiz-upload-question";
        questionElem.placeholder = "Question sentence";

        let answerElem = document.createElement('input');
        answerElem.required = true;
        answerElem.className = "quiz-upload-answer";
        answerElem.placeholder = "Answer";

        let button = document.createElement('button');
        button.innerText = 'Delete';
        button.setAttribute('value', this.id);
        button.setAttribute('onclick', 'removeQuestion(this.value); return false;');
        button.setAttribute('class', 'button-delete-question');

        this.setEventListeners();

        mainElem.appendChild(titleElem);
        mainElem.innerHTML += '<br>';
        mainElem.appendChild(descElem);
        mainElem.innerHTML += '<br>';
        mainElem.appendChild(label);
        mainElem.appendChild(this.fileManager);
        mainElem.innerHTML += '<br>';
        mainElem.appendChild(questionElem);
        mainElem.innerHTML += '<br>';
        mainElem.appendChild(answerElem);
        mainElem.innerHTML += '<br>';
        mainElem.appendChild(button);
        mainElem.innerHTML += '<hr>';

        questionWrapper.appendChild(mainElem);
    }

    
}

class Question{
    constructor(title, desc, img, question, answer)
    {
        this.title = title;
        this.description = desc;
        this.graphicpath = img;
        this.question = question;
        this.answer = answer;
    }
}

function addQuestion(){
    let question = new QuestionElement(arrlen);
    arrlen++;
    questionArray.push(question);
}

function removeQuestion(id){
    console.log('ok');
    id = parseInt(id);
    let question = questionArray.find(q => q.id === id);
    if(question.mainElem !== null)
        question.mainElem.remove();
    questionArray = questionArray.filter(q => !(q.id === id));
}

function questionsToJSON(){
    let uploadArray = [];
    for(i=0; i<questionArray.length; i++){
        uploadArray.push(new Question(questionArray[i].title, 
                                    questionArray[i].description, 
                                    questionArray[i].image, 
                                    questionArray[i].question, 
                                    questionArray[i].answer));
    }
    var data = JSON.stringify(uploadArray);
    inputJSON.value = data;
    console.log(data);
}



function addFiles(id) {
    let fileManager = document.getElementById('image' + id);
    let question = questionArray.find(q => q.id === id);
    if (fileManager.files.length > 0) {
        for (var i = 0; i <= fileManager.files.length - 1; i++) {
            var reader = new FileReader();
            reader.onload = function(e) {
                question.image = e.target.result;
            };
            reader.readAsDataURL(fileManager.files.item(i));
        }
    }
}