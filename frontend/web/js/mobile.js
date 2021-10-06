const burgerOpen = document.getElementById('isOpenside');
const sidebar = document.getElementById('sidebar');
const imgClose = document.getElementById('imgClose');
const imgOpen = document.getElementById('imgOpen');
let isOpen = true

burgerOpen.addEventListener('click', function() {
    if(isOpen){
        sidebar.classList.add('sidebarActive')
        imgClose.style.display = 'flex';
        imgOpen.style.display = 'none';
        isOpen = false
    }else{
        sidebar.classList.remove('sidebarActive')
        imgClose.style.display = 'none';
        imgOpen.style.display = 'flex';
        isOpen = true
    }
})

$('.focus').click(function(e){
    e.preventDefault()
    let isShow = parseInt($(this).attr('attr-show'))
    if (isShow == 0){
        $('.block__icon').css({'display':'block'})
        $(this).attr('attr-show', 1)
    }else{
        $('.block__icon').css({'display':'none'})
        $(this).attr('attr-show', 0)
    }

});
$('#notification-button').click(function (e){
    e.preventDefault();
    $('.notification').fadeIn(1000);
});
$('#notification-close').click(function (e){
    e.preventDefault();
    $('.notification').fadeOut(700);
});
$(document).mouseup(function (e){ // событие клика по веб-документу
    var mobileMenu = $(".focus"); // тут указываем ID элемента
    if (!mobileMenu.is(e.target) // если клик был не по нашему блоку
        && mobileMenu.has(e.target).length === 0) { // и не по его дочерним элементам
        $('.block__icon').hide(); // скрываем его
        $(mobileMenu).attr('attr-show', 0);
    }
});
$(".left__box-item a").click(function() {
    $('html, body').animate({
        scrollTop: $('.tab-content').offset().top  // класс объекта к которому приезжаем
    }, 500); // Скорость прокрутки
});

width = window.innerWidth;
let type;

if (width < 575){
    $("#row").addClass('order-2');   
    type = "ytWidgetDeskMobile";

    let url = window.location.href;
    let urlList = url.split('/');
    if(urlList.pop() == 'tickets'){
        $('.right-tab').fadeOut();
    }else{
        $('.right-tab').fadeIn();
        $('.box__left__tickets').fadeOut();
    }
}
else{
    type = "ytWidgetDesk";
}


var script = document.createElement("script");
script.setAttribute("src", "https://translate.yandex.net/website-widget/v1/widget.js?widgetId=" + type + "&pageLang=ru&widgetTheme=light&autoMode=false");
                
document.body.appendChild(script);

/*Tab with left nav*/


