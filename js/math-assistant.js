let assistant = document.getElementById("math-assistant");
let btn = document.getElementById("assistant-button");
let span = document.getElementsByClassName("assistant-close")[0];


btn.onclick = function() {
    assistant.style.display = "block";
}

span.onclick = function() {
    assistant.style.display = "none";
}

window.onclick = function(event) {
    if (event.target === assistant) {
        assistant.style.display = "none";
    }
}