<?php
require_once __DIR__ . "/../userAccount/UserRoles.php";

class VPostContent
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function viewPostContent()
    {
        if ($this->data->rowcount() == 0) { //page doesn't exist
            echo '<div class="title-main">Post not found</div><hr class="section-divider-bar">';
            BD::opreste_conexiune();
            exit();
        } else {
            $row = $this->data->fetch(PDO::FETCH_ASSOC);
            
            //show private documents only to admins or the user who posted it            
            if($row['PUBLIC'] == true || (isset($_SESSION['role']) && $_SESSION['role'] == UserRoles::ADMIN) || 
            (isset($_SESSION['userid']) && $_SESSION['userid'] == $row['ID_USER'])){
                echo '<div class="title-main">' . $row['NAME'] . '</div><hr class="section-divider-bar">';
                echo $row['CONTENT'];
                if($row['QUIZCONTENT'] != null && $row['QUIZCONTENT'] != '[]'){
                    echo '<p style="text-align: center; font-size: 30px; margin-top: 2%;">Think you got it? Then try solving the problems! </p>
                        <div style="text-align: center; align-content: center;">
                            <button class="button-regular" type="button" onclick="window.location.href=(\'quiz.php?id=' . $row['QUIZID'] . '\');">Practice</button>
                        </div>';
                }
            }
            else{
                if(!isset($_SESSION['userid']) || (!isset($_SESSION['role']) || $_SESSION['role'] != UserRoles::ADMIN)
                    || $_SESSION['userid'] != $row['ID_USER']){
                    echo '<div class="title-main">Post is private</div><hr class="section-divider-bar">';
                    BD::opreste_conexiune();
                    exit();
                }
            }
        }
    }

    public function viewUploadPage()
    { ?>
        <div class="title-main">Create a theory page</div>
        <hr class="section-divider-bar">
        <form action="" method="POST" enctype="multipart/form-data" onsubmit="prepareDiv()" id="uploadPage">
            <div class="upload-title">
                <label for="docTitle">Title:</label>
                <input class="upload-title" type="text" id="docTitle" name="docTitle" placeholder="Page title" required="required"><br>

                <label for="docContent">Content:</label><br>
            </div>
            <div style="text-align: center; align-content: center;">

                <input class="fa fa-bold" name="bold" value="&#xf032" type="button" onclick="execCmd('bold')"><i></i>
                <input class="fa fa-italic" name="italic" type="button" value="&#xf033" onclick="execCmd('italic')">
                <input class="fa fa-underline" name="underline" type="button" value="&#xf0cd" onclick="execCmd('underline')">
                <input class="fa fa-align-left" name="justifyLeft" type="button" value="&#xf036" onclick="execCmd('justifyLeft')">
                <input class="fa fa-align-center" name="justifyCenter" type="button" value="&#xf037" onclick="execCmd('justifyCenter')">
                <input class="fa fa-align-right" name="justifyRight" type="button" value="&#xf038" onclick="execCmd('justifyRight')">
                <input class="fa fa-align-justify" name="justifyFull" type="button" value="&#xf039" onclick="execCmd('justifyFull')">
                <input class="fa fa-cut" name="cut" type="button" value="&#xf0c4" onclick="execCmd('cut')">
                <input class="fa fa-copy" name="copy" type="button" value="&#xf0c5" onclick="execCmd('copy')">
                <input class="fa fa-subscript" name="subscript" type="button" value="&#xf12c" onclick="execCmd('subscript')">
                <input class="fa fa-superscript" name="superscript" type="button" value="&#xf12b" onclick="execCmd('superscript')">
                <input class="fa fa-undo" name="undo" type="button" value="&#xf0e2" onclick="execCmd('undo')">
                <input class="fa fa-redo" name="redo" type="button" value="&#xf01e" onclick="execCmd('redo')">
                <input class="fa fa-list-ul" name="insertUnorderedList" type="button" value="&#xf0ca" onclick="execCmd('insertUnorderedList')">
                <input class="fa fa-list-ol" name="insertOrderedList" type="button" value="&#xf0cb" onclick="execCmd('insertOrderedList')">
                <input class="fa fa-paragraph" name="insertParagraph" type="button" value="&#xf1dd" onclick="execCmd('insertParagraph')">
                <input class="fa fa-bold" name="insertHorizontalRule" type="button" value="HR" onclick="execCmd('insertHorizontalRule')">
                <input class="fa fa-bold" name="renderLatex" type="button" value="TeX-Render" onclick="MathJax.Hub.Typeset();">
                <input class="fa fa-bold" name="renderLatex" type="button" value="Reset" onclick="execCmd('removeFormat')">
                <br>
                <select onclick="execCmdWithArgument('formatBlock', this.value)">
                    <option value="H1">H1</option>
                    <option value="H2">H2</option>
                    <option value="H3">H3</option>
                    <option value="H4">H4</option>
                    <option value="H5">H5</option>
                    <option value="H6">H6</option>
                </select>

                <select onclick="execCmdWithArgument('fontName', this.value)">
                    <option value="Arial">Arial</option>
                    <option value="Comic Sans MS">Comic Sans MS</option>
                    <option value="Courier">Courier</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Tahoma">Tahoma</option>
                    <option value="Times New Roman">Times New Roman</option>
                    <option value="Verdana">Verdana</option>
                </select>
                <select onclick="execCmdWithArgument('fontSize', this.value)">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                </select>
                Color: <input type="color" onchange="execCmdWithArgument('foreColor', this.value);">
                Background: <input type="color" onchange="execCmdWithArgument('hiliteColor', this.value);">

                <br>
                <input class="fa fa-bold" id="assistant-button" type="button" value="Math assistant">

            </div>

            <div id="math-assistant">
                <div class="math-assistant-content">
                    <div id="close-tags">
                    <span class="assistant-close">&times;</span>
                    <input class="fa fa-subscript" name="start" type="button" value="Start equation" style="width: 100px; margin-top: 15px; margin-bottom: 10px; background-color: #caff8a;" >
                    <input class="fa fa-subscript" name="end" type="button" value="End equation"  style="width: 100px; margin-top: 15px; background-color: #ff6666;" >
                    <br>


                        <!-- COMPARE -->
                        <p style="margin-top: -10px; margin-bottom: 0;">Assign and compare</p>
                        <input class="fa fa-bold" name="not-equal" type="button" value="&#x226D" >
                        <input class="fa fa-bold" name="almost-equal" type="button" value="&#x2248" >
                        <input class="fa fa-bold" name="identical" type="button" value="&#x2261" >
                        <input class="fa fa-bold" name="less" type="button" value="&#x3c" >
                        <input class="fa fa-bold" name="greater" type="button" value="&#x3e" >
                        <input class="fa fa-bold" name="equal-less" type="button" value="&#x2264" >
                        <input class="fa fa-bold" name="equal-greater" type="button" value="&#x2265" >
                        <input class="fa fa-bold" name="minus-or-plus" type="button" value="&#x2213" >
                        <input class="fa fa-bold" name="divides" type="button" value="&#x2223" >
                        <input class="fa fa-bold" name="not-divides" type="button" value="&#x2224" >
                        <input class="fa fa-bold" name="paralel" type="button" value="&#x2225" >
                        <input class="fa fa-bold" name="not-paralel" type="button" value="&#x2226" >

                        <br>

                        <!-- OPERATIONS -->
                        <p style="margin-top: -6px; margin-bottom: 0">Operations</p>
                        <input class="fa fa-bold" name="fraction" type="button" value="&#189">
                        <input class="fa fa-subscript" name="subscript" type="button" value="&#xf12c">
                        <input class="fa fa-superscript" name="superscript" type="button" value="&#xf12b" >
                        <input class="fa fa-bold" name="root" type="button" value="&#x221A" >
                        <input class="fa fa-bold" name="root-3" type="button" value="&#x221B" >
                        <input class="fa fa-bold" name="abs" type="button" value="|x|">
                        <input class="fa fa-bold" name="percent" type="button" value="&#x25" >
                        <input class="fa fa-bold" name="mod" type="button" value="mod" style="font-size: 12px;">

                        <br>

                        <!-- CALCULUS -->
                        <p style="margin-top: -6px; margin-bottom: 0">Calculus</p>
                        <input class="fa fa-bold" name="limit" type="button" value="lim" >
                        <input class="fa fa-bold" name="integral" type="button" value="&#x222B" >
                        <input class="fa fa-bold" name="double-integral" type="button" value="&#x222C" >
                        <input class="fa fa-bold" name="partial-differential" type="button" value="&#x2202" >
                        <input class="fa fa-bold" name="matrix" type="button" value="[&#x22c5&#x22c5&#x22c5]" style="font-size: 14px;" >
                        <input class="fa fa-bold" name="determinant" type="button" value="|&#x22c5&#x22c5&#x22c5|" style="font-size: 14px;" >

                        <br>

                        <!-- TRIGONOMETRY -->
                        <p style="margin-top: -6px; margin-bottom: 0">Trigonometry</p>
                        <input class="fa fa-bold" name="degree" type="button" value="&#xb0" >
                        <input class="fa fa-bold" name="sin" type="button" value="sin" >
                        <input class="fa fa-bold" name="cos" type="button" value="cos" >
                        <input class="fa fa-bold" name="tg" type="button" value="tg" >
                        <input class="fa fa-bold" name="ctg" type="button" value="ctg" >
                        <input class="fa fa-bold" name="angle" type="button" value="&#x2222" >

                        <br>

                        <!-- LOGICAL -->
                        <p style="margin-top: -6px; margin-bottom: 0">Logical</p>
                        <input class="fa fa-bold" name="to" type="button" value="&#x2192" >
                        <input class="fa fa-bold" name="implies" type="button" value="&#x21d2" >
                        <input class="fa fa-bold" name="equivalent" type="button" value="&#x21d4" >
                        <input class="fa fa-bold" name="for-all" type="button" value="&#x2200" >
                        <input class="fa fa-bold" name="exists" type="button" value="&#x2203" >
                        <input class="fa fa-bold" name="not-exists" type="button" value="&#x2204" >

                        <br>

                        <!-- SETS -->
                        <p style="margin-top: -6px; margin-bottom: 0">Set operations</p>
                        <input class="fa fa-bold" name="natural" type="button" value="&#x2115" >
                        <input class="fa fa-bold" name="integer" type="button" value="&#x2124" >
                        <input class="fa fa-bold" name="rational" type="button" value="&#x211a" >
                        <input class="fa fa-bold" name="real" type="button" value="&#x211d" >
                        <input class="fa fa-bold" name="complex" type="button" value="&#x2102" >
                        <input class="fa fa-bold" name="intersect" type="button" value="&#x2229" >
                        <input class="fa fa-bold" name="union" type="button" value="&#x222A" >
                        <input class="fa fa-bold" name="subset-of" type="button" value="&#x2282" >
                        <input class="fa fa-bold" name="not-subset-of" type="button" value="&#x2284" >
                        <input class="fa fa-bold" name="empty-set" type="button" value="&#x2205" >
                        <input class="fa fa-bold" name="element-of" type="button" value="&#x2208" >
                        <input class="fa fa-bold" name="not-element-of" type="button" value="&#x2209" >


                        <!-- SYMBOLS -->
                        <p style="margin-top: -6px; margin-bottom: 0">Symbols, letters and constants</p>
                        <input class="fa fa-bold" name="function" type="button" value="&#x192" >
                        <input class="fa fa-bold" name="cases" type="button" value="&#x192={&#x22c5&#x22c5&#x22c5" style="font-size: 10px;" >
                        <input class="fa fa-bold" name="sigma" type="button" value="&#931" >
                        <input class="fa fa-bold" name="product" type="button" value="&#x220F" >
                        <input class="fa fa-bold" name="infinity" type="button" value="&#x223E" >
                        <input class="fa fa-bold" name="e-constant" type="button" value="e" >
                        <input class="fa fa-bold" name="pi" type="button" value="&#x03D6" >
                        <input class="fa fa-bold" name="delta" type="button" value="&#x0394" >
                        <input class="fa fa-bold" name="epsilon" type="button" value="&#x03B5" >
                        <input class="fa fa-bold" name="phi" type="button" value="&#x03C6" >
                        <input class="fa fa-bold" name="omega" type="button" value="&#x03C9" >


                    </div>

                </div>
            </div>

            <div contenteditable class="upload-content" name="docContent" id="docContent" style="border:solid 1px #999; padding:10px; resize: none;"></div>
            <input type="hidden" id="docContent_hidden" name="docContent" required="required" />

            <div style="font-size: 20px; text-align: center; align-content: center;">
                <label for="docContent">We recommend you to draw the figure <a href="https://www.drawsvg.org/drawsvg.html" target="_blank">here</a> and save it in svg format.</label><br>
            </div>
            <div style="text-align: center; align-content: center;">
                <p>
                    <input class="button-upload-file" name="docContent[]" type="file" id="image" accept="image/*" multiple="multiple" onchange="showFiles()" />
                </p>
                <p>
            </div>
            </div>
            <hr>
            <div style="text-align: center; align-content: center;">
                
         </div>





    <?php }

    public function viewQuizEditor()
    { ?>
        <div id="quiz-editor-title"> Quiz Editor </div>
        <div style="text-align: center; align-content: center;">
            <div id="quiz-upload-wrapper">

            </div>
            <button class="button-add-question" onclick="addQuestion(); return false;">Add question</button>
            <input name="upload" id="subButton" class="button-regular" type="submit" value="Upload page" />
                <input name="docQuiz" type="text" style="display:none" id="hidden-quiz-JSON"/>
        </div>
        <br>
        </form>

        </body>
        <script>
            document.getElementById("subButton").onclick = function() {
                if (document.getElementById("docContent").innerHTML.trim().length === 0) {
                    alert("Please fill content as well.");
                    return false;
                }
            };
        </script>
        <script>
            function showFiles() {
                var div = document.getElementById('docContent');
                var fi = document.getElementById('image');

                if (fi.files.length > 0) {
                    for (var i = 0; i <= fi.files.length - 1; i++) {

                        var reader = new FileReader();
                        reader.onload = function(e) {
                            var img = new Image();
                            img.src = e.target.result;
                            img.height = 300;
                            img.width = 300;


                            div.appendChild(img);
                        };
                        reader.readAsDataURL(fi.files.item(i));
                    }
                }
            }

            function execCmd(command) {
                document.execCommand(command, false, null);
            }

            function execCmdWithArgument(command, arg) {
                document.execCommand(command, false, arg);
            }

            function prepareDiv() {
                document.getElementById("docContent_hidden").value = document.getElementById("docContent").innerHTML;
                questionsToJSON();
            }

        </script>
        <script src="js/quiz/quiz_editor.js"></script>
<?php }
}

?>