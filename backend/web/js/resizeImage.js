$('.open-input-file').click(function (e){
    e.preventDefault();
    let dataInputName = $(this).data('file');
    let inputFile = $('#verificationFileInput');
    $(inputFile).data('name', dataInputName);
    $(inputFile).click();
});


var input = document.getElementById("verificationFileInput");


input.addEventListener("change", function (event) {
    uploadPhotos(event, this);
    let filename = $(this).val().split('\\').pop();
    let inputName = $(this).data('name');
    $('#input-'+inputName).find('.input-filename').html(filename);
    this.value = '';
});

 function uploadPhotos(event, input){
    // Read in file
    var file = event.target.files[0];
    let inputName = $(input).data('name');
    // Ensure it's an image
    if(file.type.match(/image.*/)) {
        console.log('An image has been loaded');

        // Load the image
        var reader = new FileReader();
        reader.onload = function (readerEvent) {
            var image = new Image();
            image.onload = function (imageEvent) {

                // Resize the image
                var canvas = document.createElement('canvas'),
                    max_size = 800,// TODO : pull max size from a site config
                    width = image.width,
                    height = image.height;
                if (width > height) {
                    if (width > max_size) {
                        height *= max_size / width;
                        width = max_size;
                    }
                } else {
                    if (height > max_size) {
                        width *= max_size / height;
                        height = max_size;
                    }
                }
                canvas.width = width;
                canvas.height = height;
                canvas.getContext('2d').drawImage(image, 0, 0, width, height);
                var dataUrl = canvas.toDataURL('image/jpeg');
                var resizedImage = dataURLToBlob(dataUrl);
                $.event.trigger({
                    type: "imageResized",
                    blob: resizedImage,
                    url: dataUrl,
                    name: inputName
                });
            }
            image.src = readerEvent.target.result;
        }
        reader.readAsDataURL(file);
    }
};
/* Utility function to convert a canvas to a BLOB */
var dataURLToBlob = function(dataURL) {
    var BASE64_MARKER = ';base64,';
    if (dataURL.indexOf(BASE64_MARKER) == -1) {
        var parts = dataURL.split(',');
        var contentType = parts[0].split(':')[1];
        var raw = parts[1];

        return new Blob([raw], {type: contentType});
    }

    var parts = dataURL.split(BASE64_MARKER);
    var contentType = parts[0].split(':')[1];
    var raw = window.atob(parts[1]);
    var rawLength = raw.length;

    var uInt8Array = new Uint8Array(rawLength);

    for (var i = 0; i < rawLength; ++i) {
        uInt8Array[i] = raw.charCodeAt(i);
    }

    return new Blob([uInt8Array], {type: contentType});
}
/* End Utility function to convert a canvas to a BLOB      */
/* Handle image resized events */
$(document).on("imageResized", function (event) {
    var data = new FormData();


    if (event.blob && event.url) {
        //data.image_data = event.blob;

        data.append('file', event.blob);
        data.append('name', event.name);
        data.append('action','verification');
        $.ajax({
            url: '/profile/upload-file',
            data: data,
            type: 'POST',
            contentType: false,
            processData: false,
            dataType:'json',
            success: function(response){
                $('#input-'+event.name).find('input').val(response.fileName);
            }
        });

    }
});

