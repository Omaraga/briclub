<?php
/* @var $this yii\web\View */

$script = <<<JS
var testData = [];
    var matrixIds = [];

    document.forms.publish.onsubmit = function (e) {
        e.preventDefault()
        var user = this.name.value;
        var select = document.querySelector('#select');
        var valueSelect = select.value;
        console.log(valueSelect)
        var tabelBlock = document.querySelector('.tabel_num')
        tabelBlock.textContent = valueSelect+'СТОЛ';

        console.log(user,valueSelect)
        data = {username: user, level: valueSelect}

        testData = [];
        matrixIds = [];

        getFirstNode(data)
    }
    function getFirstNode(reqData){
        $.ajax({
            url: '/rest/matrix',
            data: reqData,
            type: 'POST',
            dataType: 'JSON',
            success : function (resp){
                resp = jQuery.parseJSON(resp);
                testData.push({id: resp.matrixId, name: resp.username+'('+resp.matrixId+')', parent: 0});
                chart();
               
            }
        });
    }
    function getChildren(orgChart, matrixId){
        $.ajax({
            url: '/rest/matrix-child',
            data: {id: matrixId},
            type: 'POST',
            dataType: 'JSON',
            success : function (resp){
                resp = jQuery.parseJSON(resp);
                console.log(resp)
                let left = resp.children.left;
                if (left && left.matrixId){
                    let leftNode = {id: left.matrixId, name: left.username+'('+left.matrixId+')', parent: matrixId};
                    //testData.push(leftNode);
                    //console.log(leftNode)
                    orgChart.newNode(leftNode);
                }
                let right = resp.children.right;
                if (right && right.matrixId){
                    let rightNode = {id: right.matrixId, name: right.username+'('+right.matrixId+')', parent: matrixId};
                    //testData.push(leftNode);
                    //console.log(rightNode)
                    orgChart.newNode(rightNode);
                }
                matrixIds.push(matrixId);


            }
        });
    }
    function hasChild(matrixId){
        testData.forEach(function (node){
            console.log(node);
        })
    }
   

    console.log(testData);
    function chart(){
        $(function(){
            org_chart = $('#orgChart').orgChart({
                data: testData,
                showControls: false,
                allowEdit: false,
                onClickNode: function(node){
                    if (matrixIds.indexOf(node.data.id) === -1){
                        getChildren(org_chart, node.data.id);
                    }

                }

            });
        });
    }



JS;

$this->registerJsFile('/js/jquery.orgchart.js',['depends'=>'yii\web\JqueryAsset']);
$this->registerCssFile('https://www.jqueryscript.net/css/jquerysctipttop.css');
$this->registerCssFile('/css/jquery.orgchart.css');
$this->registerJs($script);

?>
<style type="text/css">
    #orgChart{
        width: auto;
        height: auto;
        position: relative;
    }

    .tabel{
        width: 100px;
        height: 100px;
        position: absolute;
        top: 135px;
        right: 20px;
        border-left: 2px solid #000;
        border-radius: 50%;
        transform: rotate(325deg);
    }

    .tabel_num{
        width: 80px;
        transform: rotate(35deg);
        text-align: center;
        padding-bottom: 10px;
    }

    #orgChartContainer{
        width: 100%;
        height: 500px;
        overflow: auto;
        background: #eeeeee;
    }
    .input_def{
        height: 35px;
        border: #2D2D2D;
        background: #616161;
        color: #fff;
        padding: 0 16px;
        border-radius: 4px;
        width: 271px;
    }
    .submit{
        height: 35px;
        background-color: rgb(99 99 171 / 94%);
        border: none;
        border-radius: 4px;
        color: #fff;
        font-size: 17px;
    }
    .width{
        width: 150px;
    }
    .node{
        width: auto!important;
        border-radius: 8px!important;
        box-shadow: 7px 5px 0px #ddd!important;
    }
    .node h2:hover{
        background: transparent!important;
        cursor: pointer!important;
    }
</style>
<div class="jquery-script-clear"></div>

<div>
    <div>
        <form name="publish" style="display: flex; margin: 10px 0;">
            <div>
                <h2>Login</h2>
                <input class="input_def" name="name" type="text" autocomplete="off">
            </div>
            <div style="margin-left: 30px;">
                <h2>Tabel</h2>
                <select class="input_def width" id="select">
                    <option value="1">СТОЛ 1</option>
                    <option value="2">СТОЛ 2</option>
                    <option value="3">СТОЛ 3</option>
                    <option value="4">СТОЛ 4</option>
                    <option value="5">СТОЛ 5</option>
                    <option value="6">СТОЛ 6</option>
                </select>
                <input class="submit" type="submit" value="Показать"/>
            </div>
        </form>
    </div>
    <div id="orgChartContainer">
        <div id="orgChart">

        </div>
        <div class="tabel">
            <h2 class="tabel_num"></h2>
        </div>
    </div>
</div>