$('.mobile-item').click(function (e){
    e.preventDefault()
    let json = $(this).data('json')

    $('#refs-name').text(json.fio)
    $('#refs-email').text(json.email)
    $('#refs-parent').text(json.parent)
    $('#refs-level').text('Уровень: '+json.level)
    $('#refs-date').text(json.created_at)
    $('#refs-date-activate').text(json.time_personal)
    $('.mobile-info-block').slideToggle(500)
})