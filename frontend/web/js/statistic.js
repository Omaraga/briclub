var oneObj = {
    element:[{
            name:"#money",
            numbers:[0,0,0,0,0,0,0,0,0,0,0,0,0,0]
        },
        {
            name:"#matrix",
            numbers:[0,0,0,0,0,0,0,0,0,0,0,0,0,0]
        }
    ]
}
let i = 0;
$("#money .bar").each(function (index){
    oneObj.element[0].numbers[i] = parseInt($(this).attr('attr-money'))
    i++;
})
 i = 0;
$("#matrix .bar").each(function (index){
    oneObj.element[1].numbers[i] = parseInt($(this).attr('attr-child'))
    i++;
})


setTimeout( function(){
    for(var i = 0 ; i < oneObj.element.length ; i++) {
        $(oneObj.element[i].name + ' .bar').each(function(index) {
            if (i === 0){
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

                //$(this).closest('.year').find('.label').css('max-width' , '20%');
                let type = $(this).closest('.year').attr('attr-type');
                if (type === 'month'){
                    $(this).css({'height' : height + '%', 'max-width' : '100%', 'min-width':'20%'});
                }else{
                    $(this).css({'height' : height + '%', 'max-width' : '100%'});
                }

                if (money > 0){
                    $(this).find('.info_sum').text('$'+money)
                }
                // Create tooltip
                $('<span class="tooltip">' + oneObj.element[i].numbers[index] + '%</span>').prependTo(this);
            }else{
                // To better represent % of height

                let child = oneObj.element[i].numbers[index];
                let height = 0;
                if(child <= 5){
                    height = 20*child/5;
                }else if(child > 5 && child <=10){
                    height = (child-5)*20/5+20;
                }else if(child > 10 && child <= 50){
                    height = (child - 10)*20/40+40;
                }else if(child > 50 && child <= 100){
                    height = (child - 50)*20/50+60;
                }else if(child > 100){
                    height = 90;
                }
                let type = $(this).closest('.year').attr('attr-type');
                if (type === 'month'){
                    $(this).css({'height' : height + '%', 'width' : '100%', 'min-width':'20%'});
                }else{
                    $(this).css({'height' : height + '%', 'max-width' : '100%'});
                }


                $(this).find('.info_sum').text(child)


                // Create tooltip
                $('<span class="tooltip">' + oneObj.element[i].numbers[index] + '%</span>').prependTo(this);
            }

        });

    }

} ,500 );

$(".statisticSelector").change(function (e){
    let tab = $(this).attr('attr-tab')
    let type = $(this).val()
    window.location= '/profile/statistic/'+type +'/'+ tab
})

