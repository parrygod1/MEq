textinput = document.getElementsByClassName('textinput')[0];

function enablePreview() {
    preview = document.getElementById('comment-preview');
    preview.innerHTML = textinput.value;
    MathJax.Hub.Typeset()
}

function checkComment(){
    if(textinput.value.length === 0)
        alert('Invalid comment!');
}
