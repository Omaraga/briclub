function countdown(minutes, seconds) {
    if (seconds === null){
        seconds = 60
    }
    var mins = minutes

    function tick() {
        var counter = $("#timer")
        if (mins > 1){
            var current_minutes = mins - 1
        }else{
            var current_minutes = mins
        }

        seconds--;
        $(counter).html(current_minutes.toString() + ":" + (seconds < 10 ? "0" : "") + String(seconds))
        if (seconds > 0) {
            timeoutHandle = setTimeout(tick, 1000);
        } else {

            if (mins > 1) {

                // countdown(mins-1);   never reach “00″ issue solved:Contributed by Victor Streithorst
                setTimeout(function() {
                    countdown(mins - 1);
                }, 1000);

            }else{

                $("#sendEven").fadeIn().attr('disabled', false)
                $("#timer").fadeOut();
            }
        }
    }
    tick();
}
function validateInputFile(obj){
    if($(obj).val().length === 0){
        $(obj).closest('.form-group').addClass('has-error')
        $(obj).focus();
        return false;
    }else{
        return true;
    }
}
$(document).ready(function(){

    var timeoutHandle;
    let firstName = $('#exampleInputFirstName').val()
    let lastName = $("#exampleInputLastName").val()
    let phone = $("#exampleInputPhone").val()
    $(".tab-modal-setting input").change(function (){
        $(this).closest('form').find('.btn-cancel').attr('disabled', false)
    })
    $("#sendEven").click(function (e){
        e.preventDefault()
        $(this).closest('form').find("#inputTypeReq").val('send')

        $(this).closest('form').unbind('submit').submit(); // continue the submit unbind preventDefault
    })

    $("#cancelOwnInfo").click(function (e){
        e.preventDefault()
        $('#exampleInputFirstName').val(firstName)
        $("#exampleInputLastName").val(lastName)
        $("#exampleInputPhone").val(phone)
        $(this).attr('disabled', true)

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
    $('.input').keydown(function(e){
        $(this).val('');
    })
    let fullValarr = [0,0,0,0,0,0]

    $('.input').keyup(function(e){
        var $wrap = $(this).closest('.right__box-inputs')
        var $inputs = $wrap.find('.input')
        var val = $(this).val()
        let key = e.keyCode || e.charCode
        if (key == 8){
            if ($inputs.index(this) > 0){
                // Передача фокуса следующему innput
                $inputs.eq($inputs.index(this) - 1).focus()
            }
        }else{
            // Ввод только цифр
            if(val == val.replace(/[0-9]/, '')) {
                $(this).val('')
                return false
            }


            // Передача фокуса следующему innput
            $inputs.eq($inputs.index(this) + 1).focus()
            // Заполнение input hidden
            indexInp = $inputs.index(this)
            fullValarr[indexInp] = parseInt(val)
            let fullval = fullValarr.join('')
            $wrap.find('#inputFullCode').val(fullval)
        }


    });
    countdown(1, 30);

    //Main data verification block
    $("#verifyMainData").click(function (e){
        e.preventDefault()
        $('.stage-2').each(function (e){
            $(this).removeClass('active')
        })
        $("#mainDataTab").addClass('active')
    })
    $("#mainDataNext").click(function (e){
        e.preventDefault()
        var valid = true
        $("#mainDataTab1 input[type='hidden']").each(function (e){
            if (!validateInputFile(this)){
                valid = false
            }

        })
        if (valid){
            $("#mainDataTab1").fadeOut()
            $("#mainDataTab2").fadeIn()
        }else{
            //$(this).attr('disabled',true)
        }

    })
    $('#form-verification').submit(function(e) {
        e.preventDefault()
        let valid = true
        $("#form-verification input[type='hidden']").each(function (e){
            if (!validateInputFile(this)){
                valid = false
            }

        })
        if (valid){
            $(this).unbind('submit').submit(); // continue the submit unbind preventDefault
        }


    })
    $("#mainDataVerification-2-back").click(function (e){
        e.preventDefault()
        $("#mainDataTab1").fadeIn()
        $("#mainDataTab2").fadeOut()
    })

    /*ADDRESS VERIFICATION*/
    $("#startVerifAddress").click(function (e){
        e.preventDefault()
        $('.stage-3').each(function (e){
            $(this).removeClass('active')
        })
        $("#addressTab").addClass('active')
    })
    $("#addressNext").click(function (e){
        e.preventDefault()
        let valid = true
        if (valid){
            $("#addressVerifTab1").fadeOut()
            $("#addressVerifTab2").fadeIn()
        }else{
            //$(this).attr('disabled',true)
        }

    })
    $("#addressBack").click(function (e){
        e.preventDefault()

        $("#addressVerifTab1").fadeIn()
        $("#addressVerifTab2").fadeOut()

    })

    $(".file-upload input[type=file]").change(function(){
        var filename = $(this).val().replace(/.*\\/, "");
        $(this).closest('.file-upload').find('.filename').val (filename);

    });
    $('.remove').click(function(){
        $(this).closest('.file-upload').find('.upload')[0].files.value = '';
        $(this).closest('.file-upload').find('.filename').val ('');
    })
    $("input[name='AvatarForm[imageFile]']").change(function (){
        $(this).closest('form').submit();
    })


    $('.hide_button').click(function(e){
        e.preventDefault()
        let width = window.innerWidth

        if(width < 576){
            $('#block__userSettings').slideUp()
            $('.main__title').slideUp()
            $('#user_block').slideUp()
            $('.header').slideUp()
            $('body').css('background-color','#071B43')
            $('.visib').css('display','block')
            $('.fon').css('box-shadow','none')
            $('form').css('margin-top','-2rem')
            $('.visib').fadeIn()
        }else if(width < 991){
            $('#block__userSettings').slideUp()
            $('.visib').fadeIn()
        }
    })

    $('.back').click(function(e){
        $('#block__userSettings').fadeIn()
        $('.main__title').fadeIn()
        $('#user_block').fadeIn()
        $('#user_block').fadeIn()
        $('header').fadeIn()
        $('.main__title').fadeIn()
        $('body').css({'background':'#fff'})
        $('.visib').css({'display':'block'})
        $('.fon').css({'box-shadow':'none'})
        $('.visib').slideUp()
    })



});



var bs_modal = $('#modal');
var image = document.getElementById('image');
var cropper,reader,file;


$("body").on("change", ".image", function(e) {
    var files = e.target.files;
    var done = function(url) {
        image.src = url;
        bs_modal.modal('show');
    };


    if (files && files.length > 0) {
        file = files[0];

        if (URL) {
            done(URL.createObjectURL(file));
        } else if (FileReader) {
            reader = new FileReader();
            reader.onload = function(e) {
                done(reader.result);
            };
            reader.readAsDataURL(file);
        }
    }
});

bs_modal.on('shown.bs.modal', function() {
    cropper = new Cropper(image, {
        aspectRatio: 1,
        viewMode: 3,
        preview: '.preview'
    });
}).on('hidden.bs.modal', function() {
    cropper.destroy();
    cropper = null;
});

$("#crop").click(function() {
    canvas = cropper.getCroppedCanvas({
        width: 160,
        height: 160,
    });

    canvas.toBlob(function(blob) {
        url = URL.createObjectURL(blob);
        var reader = new FileReader();
        reader.readAsDataURL(blob);
        reader.onloadend = function() {
            var base64data = reader.result;

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "/profile/settings",
                data: {image: base64data},
                success: function(data) {
                    bs_modal.modal('hide');
                }
            });
        };
    });
});
