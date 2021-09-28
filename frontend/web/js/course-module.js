var isOpen = false;
var isOpenListItem = false
$('.course-name').on('click',function() {
    if(!isOpenListItem){
        isOpenListItem = true;
        $(this).closest('li').find('.course-item').css({
            'display' : 'block'
        });
        console.log('ertyuio')
    }else{
        isOpenListItem = false
        $(this).closest('li').find('.course-item').css({
            'display' : 'none'
        });
    }
    if(isOpenListItem){
        $(this).closest('li').find('#closeBloc').css({
            'display' : 'flex'
        })
        $(this).closest('li').find('#open').css({
            'display' : 'none'
        })
    }else{
        $(this).closest('li').find('#closeBloc').css({
            'display' : 'none'
        })
        $(this).closest('li').find('#open').css({
            'display' : 'flex'
        })
    }
});
$('#closeItem').on('click',function() {
    $(this).closest('li').find('.course-item').css({
        'display' : 'none'
    })
})
$('.header-img').on('click', function() {
    if(!isOpen){
        isOpen = true
        $('.list-click').css({
            'display' : 'block'
        })
    }else{
        isOpen = false
        $('.list-click').css({
            'display' : 'none'
        })
    }
})
$('#closeItem').on('click',function() {
    $(this).closest('li').find('.course-item').css({
        'display' : 'none'
    })
})
$('.header-img').on('click', function() {
    if(!isOpen){
        isOpen = true
        $('.list-click').css({
            'display' : 'block'
        })
    }else{
        isOpen = false
        $('.list-click').css({
            'display' : 'none'
        })
    }
})