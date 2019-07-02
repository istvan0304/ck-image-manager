'use strict';

$(document).ready(function () {
    var $body = $('body'),
        $imgId = null,
        $sidebar = $('#ck-sidebar'),
        $uploadBtn = $('#ck-img-upload'),
        $form = $('#img-upload-form'),
        $fileInput = $('input[name="CkImageForm[img_files][]"]'),
        $img = $('.ck-img'),
        $selectedImgContainer = $('.ck-selected-img-container'),
        $progessBar = $('.ck-progress'),
        $details = $('.ck-details'),
        $detailsOpen = $('#ck-upload-details'),
        $detailsClose = $('.ck-details-close'),
        $deleteBtn = $('#ck-delete');

    $fileInput.change(function () {
        $form.submit();
    });

    $body.on('beforeSubmit', $form, function () {
        // let formData = new FormData($form[0]);
        // let formData = new FormData(document.querySelector("form"));

        var form = document.getElementById('img-upload-form');
        var formData = new FormData(form);
        console.log(formData);

        $.ajax('/imagemanager/ck-image/upload', {
            xhr: function () {
                var xhr = new window.XMLHttpRequest();

                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = Math.round((evt.loaded * 100) / evt.total);
                        $('.ck-progress-line').css('display', 'block');
                        $progessBar.css('width', percentComplete + '%');
                        $('#ck-percentage').text(percentComplete + '%');

                        if (percentComplete === 100) {

                        }

                    }
                }, false);

                return xhr;
            },
            method: "POST",
            dataType: 'json',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            success: function (response) {
                console.log($details.find('.ck-details-body'));
                $details.find('.ck-details-body').empty().append(response);
                $.pjax.reload({container: "#ck-pjax-image-list"});
            },
            error: function (xhr) {

            }
        });

        return false;
    });

    $uploadBtn.click(function () {
        $('#ckimageform-img_files').trigger('click');
    });

    $body.on('click', '.ck-img', function () {
        ckImage.getDetails($(this).parent().data());
    });

    $detailsOpen.click(function () {
        $details.animate({width:'toggle'});
    });

    $detailsClose.click(function () {
        $details.animate({width:'toggle'});
    });

    $('#ck-select').click(function () {
        console.log($imgId);
        // ckImage.select();
        console.log(typeof $imgId);
    });

    $deleteBtn.click(function () {
        ckImage.delete();
    });

    var ckImage = {
        getDetails: (data) => {
            if (data !== undefined && typeof data === 'object') {
                $imgId = data.id;

                $.ajax('/imagemanager/ck-image/get-details', {
                    method: "GET",
                    dataType: 'json',
                    data: {id: data.id},
                    cache: false,
                    success: function (response) {
                        if(response['success']){
                            $sidebar.empty().append(response['template']);
                        }else if(response['success'] === false){
                            $sidebar.empty().append(response['message']);
                        }
                    }
                });


                $selectedImgContainer.empty().append($img);

            }
        },

        select: () => {
            if (isNaN(typeof $imgId === 'number')) {
                var sField = window.queryStringParameter.get(window.location.href, "CKEditorFuncNum");
                window.top.opener.CKEDITOR.tools.callFunction(sField, '/ckimagemanager/ck-image/preview-thumbnail?id=' + $imgId);
                window.self.close();
            }
        },

        delete: () => {
            if ($imgId !== null && typeof $imgId === 'number') {
                $.ajax('/imagemanager/ck-image/delete', {
                    method: "POST",
                    dataType: 'json',
                    data: {id: $imgId},
                    cache: false,
                    success: function (response) {
                        if(response['success']){
                            $.pjax.reload({container: "#ck-pjax-image-list"});
                        }
                    }
                });
            }
        }
    };
});


window.queryStringParameter = {
    get: function (uri, key) {
        var reParam = new RegExp('(?:[\?&]|&amp;)' + key + '=([^&]+)', 'i');
        var match = uri.match(reParam);
        return (match && match.length > 1) ? match[1] : null;
    },
    set: function (uri, key, value) {
        //replace brackets
        var keyReplace = key.replace("[]", "").replace(/\[/g, "%5B").replace(/\]/g, "%5D");
        //replace data
        var re = new RegExp("([?&])" + keyReplace + "=.*?(&|$)", "i");
        var separator = uri.indexOf('?') !== -1 ? "&" : "?";
        if (uri.match(re)) {
            return uri.replace(re, '$1' + keyReplace + "=" + value + '$2');
        } else {
            return uri + separator + keyReplace + "=" + value;
        }
    }
};
