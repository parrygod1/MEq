let assistant = document.getElementById("math-assistant");
let btn = document.getElementById("assistant-button");
let span = document.getElementsByClassName("assistant-close")[0];
let inputs = document.getElementById('close-tags').getElementsByTagName('input');

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

for(let i = 0; i < inputs.length; i++) {
    inputs[i].onclick = function () {
        assistant.style.display = "none";
        switch (inputs[i].name) {
            case 'start': execCmdWithArgument('insertText', '$$'); break;
            case 'end': execCmdWithArgument('insertText', '$$'); break;

            case 'not-equal': execCmdWithArgument('insertText', '\\neq'); break;
            case 'almost-equal': execCmdWithArgument('insertText', '\\approx'); break;
            case 'identical': execCmdWithArgument('insertText', '\\equiv'); break;
            case 'less': execCmdWithArgument('insertText', '\\lt'); break;
            case 'greater': execCmdWithArgument('insertText', '\\gt'); break;
            case 'equal-less': execCmdWithArgument('insertText', '\\le'); break;
            case 'equal-greater': execCmdWithArgument('insertText', '\\ge'); break;
            case 'minus-or-plus': execCmdWithArgument('insertText', '\\mp'); break;
            case 'divides': execCmdWithArgument('insertText', '\\textbar'); break;
            case 'not-divides': execCmdWithArgument('insertText', '\\not\\textbar'); break;
            case 'paralel': execCmdWithArgument('insertText', '\\parallel'); break;
            case 'not-paralel': execCmdWithArgument('insertText', '\\not\\parallel'); break;

            case 'fraction': execCmdWithArgument('insertText', '\frac{}{}'); break;
            case 'subscript': execCmdWithArgument('insertText', '\log_2{}'); break;
            case 'superscript': execCmdWithArgument('insertText', 'x^{}'); break;
            case 'root': execCmdWithArgument('insertText', '\\sqrt{}'); break;
            case 'root-3': execCmdWithArgument('insertText', '\\sqrt[3]{}'); break;
            case 'abs': execCmdWithArgument('insertText', '\\vert{}'); break;
            case 'percent': execCmdWithArgument('insertText', '\\%'); break;
            case 'mod': execCmdWithArgument('insertText', '\\pmod {}'); break;

            case 'limit': execCmdWithArgument('insertText', '\\lim_{x \\to 0} {}'); break;
            case 'integral': execCmdWithArgument('insertText', '\\int_{0}^{1} {}'); break;
            case 'double-integral': execCmdWithArgument('insertText', '\\iint_{0}^{1} {}'); break;
            case 'partial-differential': execCmdWithArgument('insertText', '\\partial {}'); break;
            case 'matrix': execCmdWithArgument('insertText', '\\begin{pmatrix} 1 & x & x^2 \\\\ 1 & y & y^2 \\\\ 1 & z & z^2 \\end{pmatrix}'); break;
            case 'determinant': execCmdWithArgument('insertText', '\\begin{vmatrix} 1 & x & x^2 \\\\ 1 & y & y^2 \\\\ 1 & z & z^2 \\end{vmatrix}'); break;

            case 'degree': execCmdWithArgument('insertText', '\\degree'); break;
            case 'sin': execCmdWithArgument('insertText', '\\sin {}'); break;
            case 'cos': execCmdWithArgument('insertText', '\\cos {}'); break;
            case 'tg': execCmdWithArgument('insertText', '\\tg {}'); break;
            case 'ctg': execCmdWithArgument('insertText', '\\ctg {}'); break;
            case 'angle': execCmdWithArgument('insertText', '\\sphericalangle'); break;

            case 'to': execCmdWithArgument('insertText', '\\to'); break;
            case 'implies': execCmdWithArgument('insertText', '\\Rightarrow'); break;
            case 'equivalent': execCmdWithArgument('insertText', '\\iff'); break;
            case 'for-all': execCmdWithArgument('insertText', '\\forall'); break;
            case 'exists': execCmdWithArgument('insertText', '\\exists'); break;
            case 'not-exists': execCmdWithArgument('insertText', '\\not\\exists'); break;

            case 'natural': execCmdWithArgument('insertText', '\\mathbb N'); break;
            case 'integer': execCmdWithArgument('insertText', '\\mathbb Z'); break;
            case 'rational': execCmdWithArgument('insertText', '\\mathbb Q'); break;
            case 'real': execCmdWithArgument('insertText', '\\mathbb R'); break;
            case 'complex': execCmdWithArgument('insertText', '\\mathbb C'); break;
            case 'intersect': execCmdWithArgument('insertText', '\\bigcap'); break;
            case 'union': execCmdWithArgument('insertText', '\\bigcup'); break;
            case 'subset-of': execCmdWithArgument('insertText', '\\subset'); break;
            case 'not-subset-of': execCmdWithArgument('insertText', '\\not\\subset'); break;
            case 'empty-set': execCmdWithArgument('insertText', '\\emptyset'); break;
            case 'element-of': execCmdWithArgument('insertText', '\\in'); break;
            case 'not-element-of': execCmdWithArgument('insertText', '\\notin'); break;

            case 'function': execCmdWithArgument('insertText', '\\operatorname{f}(x)'); break;
            case 'cases': execCmdWithArgument('insertText', '\\operatorname{f}(x)=\\begin{cases} n/2,  & \\text{if $n$ is even} \\\\ 3n+1, & \\text{if $n$ is odd} \\end{cases}'); break;
            case 'sigma': execCmdWithArgument('insertText', '\\sum_{i=0}^n {}'); break;
            case 'product': execCmdWithArgument('insertText', '\\prod{}'); break;
            case 'infinity': execCmdWithArgument('insertText', '\\infty'); break;
            case 'pi': execCmdWithArgument('insertText', '\\pi'); break;
            case 'e-constant': execCmdWithArgument('insertText', '\\textestimated'); break;
            case 'delta': execCmdWithArgument('insertText', '\\Delta'); break;
            case 'epsilon': execCmdWithArgument('insertText', '\\epsilon'); break;
            case 'phi': execCmdWithArgument('insertText', '\\phi'); break;
            case 'omega': execCmdWithArgument('insertText', '\\omega'); break;
        }

    }
}

function execCmdWithArgument(command, arg) {
    document.execCommand(command, false, arg);
}










