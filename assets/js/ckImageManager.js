'use strict';

$(document).ready(function () {
    var $body = $('body'),
        $imgId = null,
        $sidebar = $('#ck-sidebar'),
        $uploadBtn = $('#ck-img-upload'),
        $form = $('#img-upload-form'),
        $fileInput = $('input[name="CkImageForm[img_files][]"]'),
        $progessBar = $('.ck-progress'),
        $details = $('.ck-details'),
        $detailsOpen = $('#ck-upload-details'),
        $detailsClose = $('.ck-details-close');

    $fileInput.change(function () {
        $form.submit();
    });

    $body.on('beforeSubmit', $form, function () {
        ckImage.upload();

        return false;
    });

    $uploadBtn.click(function () {
        $('#ckimageform-img_files').trigger('click');
    });

    $body.on('click', '.ck-img-box', function () {
        if ($(this).hasClass('active')) {
            $('.ck-img-box').removeClass('active');
            $sidebar.empty().css({'display': 'none'});
        } else {
            $('.ck-img-box').removeClass('active');
            $(this).addClass('active');
            ckImage.getDetails($(this).data());
        }
    });

    $detailsOpen.click(function () {
        $details.animate({width: 'toggle'});
    });

    $detailsClose.click(function () {
        $details.animate({width: 'toggle'});
    });

    $body.on('click', '#ck-select', function () {
        ckImage.select($(this).data('id'));
    });

    $body.on('click', '#ck-delete', function () {
        ckImage.delete($(this).data('id'));
    });

    var ckImage = {
        upload: () => {
            let form = document.getElementById('img-upload-form');
            let formData = new FormData(form);

            if (formData !== undefined) {
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
                        $details.find('.ck-details-body').empty().append(response);
                        $.pjax.reload({container: "#ck-pjax-image-list"});
                    },
                    error: function (xhr) {
                        console.log(xhr);
                    }
                });
            }

            return false;
        },

        getDetails: (data) => {
            if (data !== undefined && typeof data === 'object') {
                $imgId = data.id;

                $.ajax('/imagemanager/ck-image/get-details', {
                    method: "GET",
                    dataType: 'json',
                    data: {id: data.id},
                    cache: false,
                    success: function (response) {
                        if (response['success']) {
                            $sidebar.empty().append(response['template']).css({'display': 'block'});
                        } else if (response['success'] === false) {
                            $sidebar.empty().append(response['message']).css({'display': 'block'});
                        }
                    }
                });
            }
        },

        select: (imgId) => {
            if (imgId !== null && typeof imgId === 'number') {
                var sField = window.queryStringParameter.get(window.location.href, "CKEditorFuncNum");
                window.top.opener.CKEDITOR.tools.callFunction(sField, '/imagemanager/ck-image/get-image?id=' + $imgId);
                window.self.close();
            }
        },

        delete: (imgId) => {
            if (imgId !== null && typeof imgId === 'number') {
                $.ajax('/imagemanager/ck-image/delete', {
                    method: "POST",
                    dataType: 'json',
                    data: {id: imgId},
                    cache: false,
                    success: function (response) {
                        if (response['success']) {
                            $.pjax.reload({container: "#ck-pjax-image-list"});
                            $sidebar.empty().css({'display': 'none'});
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
