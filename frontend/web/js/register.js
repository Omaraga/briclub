function fadeTabs(selTabId){
    $(".reg-tabs").each(function (el, val) {
        let tabId = parseInt($(val).attr('tab-id'))
        if (tabId !== selTabId){
            $(this).slideUp().fadeOut()
        }else{
            $(this).fadeIn()
        }
    })

}
function removeError(obj){
    $(obj).removeClass('has-error')
    $(obj).next().find('span').remove()
}
function addError(obj, text){
    $(obj).addClass('has-error')
    $(obj).next().html('<span>'+text+'</span>')
}
function validatePage(pageId){
    let notErrorInputSize = $(".reg-tabs[tab-id  = "+pageId+"] .has-error").length
    if (notErrorInputSize > 0){
        $(".reg-tabs[tab-id  = "+pageId+"] .button-navigate-next").attr("disabled", true)
    }else{
        $(".reg-tabs[tab-id  = "+pageId+"] .button-navigate-next").attr("disabled", false)
    }
}
function validateInput(obj){
    let inputName = $(obj).attr('name');
    let inputVal = $(obj).val()
    $(this).removeClass('has-error')
    if (inputName === 'SignupForm[fn]'){
        if (inputVal.length > 0){
            removeError(obj)
        }else{
            removeError(obj)
            addError(obj, "Поле обязательно к заполнению")
        }

    }else if (inputName === 'SignupForm[ln]'){
        if (inputVal && inputVal.length > 0){
            removeError(obj)
        }else{
            removeError(obj)
            addError(obj, "Поле обязательно к заполнению")
        }

    }else if(inputName === 'SignupForm[phone]' || inputName === 'SignupForm[username]' || inputName === 'SignupForm[email]'){
        if (inputVal && inputVal.length > 0){
            removeError(obj)
        }else{
            removeError(obj)
            addError(obj, "Поле обязательно к заполнению")
        }
    }else if(inputName === 'SignupForm[password1]'){
        if (inputVal && inputVal.length > 6){
            removeError(obj)
        }else{
            removeError(obj)
            addError(obj, "Длина пароля должна быть не менее 8 символов")
        }
    }else if (inputName === 'SignupForm[password_repeat1]'){
        let password = $(".form__regs input[name='SignupForm[password]']").val()
        if (password === inputVal){
            removeError(obj)
        } else{
            removeError(obj)
            addError(obj, "Пароли не совпадают")
        }
    }
}

$(document).ready(function(){
    let selTabId = parseInt($(".ellipsises__list .active").text())
    fadeTabs(selTabId)
    $(".button-navigate-back").click(function (e) {
        e.preventDefault()
        let pageId = parseInt($(this).attr("attr-curr-page"))
        if (pageId > 1){
            let prePage = --pageId
            $(".ellipsises__list li").each(function (id, val) {
                let liId = $(this).attr("attr-id")
                if(liId == prePage){
                    $(this).addClass('active')
                }else{
                    $(this).removeClass('active')
                }
            })
            fadeTabs(prePage)
        }
    })

    $('.eye').click(function(e){
        e.preventDefault()
        let isShow = $(this).attr('attr-show')

        if(isShow == 1){
            $(this).children().removeClass('fa-eye-slash').addClass('fa-eye')
            $(this).attr('attr-show','2')
            $(this).parent().find('input').attr('type', 'text')
        }else{
            $(this).children().removeClass('fa-eye').addClass('fa-eye-slash')
            $(this).attr('attr-show','1')
            $(this).parent().find('input').attr('type', 'password')
        }

    })

    $(".button-navigate-next").click(function (e) {
        e.preventDefault()
        let pageId = parseInt($(this).attr("attr-curr-page"))

        $(".reg-tabs[tab-id  = "+pageId+"] input").each(function (id, val) {
            validateInput(this)
        })
        let notErrorInputSize = $(".reg-tabs[tab-id  = "+pageId+"] .has-error").length

        if (notErrorInputSize === 0){
            if (pageId < 3){
                let prePage = ++pageId
                $(".ellipsises__list li").each(function (id, val) {
                    let liId = $(this).attr("attr-id")
                    if(liId == prePage){
                        $(this).addClass('active')
                    }else{
                        $(this).removeClass('active')
                    }
                })
                fadeTabs(prePage)
            }
        }else{
            $(".reg-tabs[tab-id  = "+pageId+"] input").each(function (id, val) {
                validateInput(this)

            })
        }

    })
    $(".form__regs input").unbind().blur(function (e) {
        validateInput(this)
        let currPage = parseInt($(this).attr('attr-curr-page'))
        validatePage(currPage)

    })

    $(".form__regs input[name=adult], .form__regs input[name=agree]").change(function (e) {
        let allChecked = true
        $(".form__regs input[name=adult], .form__regs input[name=agree]").each(function (id, val) {
            if (!$(this).is(':checked')){
                allChecked = false
            }
        })

        let notErrorInputSize = $(".has-error").length

        if (allChecked && notErrorInputSize === 0){
            $('#regbutton').attr('disabled', false)
        }else{
            $('#regbutton').attr("disabled", true)
        }


    })
});
