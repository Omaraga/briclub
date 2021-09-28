function copy(element_id){
    var aux = document.createElement("div");
    aux.setAttribute("contentEditable", true);
    aux.innerHTML = document.getElementById(element_id).innerHTML;
    aux.setAttribute("onfocus", "document.execCommand('selectAll',false,null)");
    document.body.appendChild(aux);
    aux.focus();
    document.execCommand("copy");
    document.body.removeChild(aux);
}
var oneObj = {
    element:[{
        name:".progress_block",
        numbers:[0,0,0,0,0,0,0,0,0,0,0,0,0,0]
    },
        {
            name:"#matrix",
            numbers:[0,0,0,0,0,0,0,0,0,0,0,0,0,0]
        }
    ]
}
let i = 0;
$(".progress_block .bar").each(function (index){
    oneObj.element[0].numbers[i] = parseInt($(this).attr('attr-money'))
    i++;
})
setTimeout( function(){
    for(var i = 0 ; i < oneObj.element.length ; i++) {
        $(oneObj.element[i].name + ' .bar').each(function(index) {

                // To better represent % of height
                let money = oneObj.element[i].numbers[index];
                let height = 0;
                if(money <= 100){
                    height = 20*money/100;
                }else if(money > 100 && money <=500){
                    height = money/12.5;
                }else if(money > 500 && money <= 1000){
                    height = (money - 500)*20/500+40;
                }else if(money > 1000 && money <= 5000){
                    height = (money - 1000)*20/4000+60;
                }else if(money > 5000){
                    height = 90;
                }


                $(this).css({'height' : height + '%', 'max-width' : '100%', 'min-width':'90%', 'text-align' : 'center', 'color':'#fff'});

                if (money > 0){
                    $(this).find('.info_sum').text('$'+money)
                }

                // Create tooltip
                $('<span class="tooltip">' + oneObj.element[i].numbers[index] + '%</span>').prependTo(this);


        });

    }

} ,500 );