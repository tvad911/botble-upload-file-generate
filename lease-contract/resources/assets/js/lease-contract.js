let RealEstate = {
    init: function () {
        new RvMediaStandAlone('.js-btn-trigger-add-file', {
            onSelectFiles: function (files, $el) {
                let $currentBoxList = $el.closest('.object-files-wrapper').find('.files-wrapper .list-gallery-media-files');

                $currentBoxList.removeClass('hidden');

                $('.default-placeholder-object-image').addClass('hidden');

                _.forEach(files, function (file) {
                    let template = $(document).find('#object_select_image_template').html();

                    let imageBox = template
                        .replace(/__name__/gi, $el.attr('data-name'));

                    let $template = $('<li class="object-image-item-handler">' + imageBox + '</li>');

                    $template.find('.image-data').val(file.url);
                    $template.find('.preview_image').attr('src', file.thumb).show();

                    $currentBoxList.append($template);
                });
            }
        });
        new RvMediaStandAlone('.files-wrapper .btn-trigger-edit-object-image', {
            onSelectFiles: function (files, $el) {
                let firstItem = _.first(files);

                let $currentBox = $el.closest('.object-image-item-handler').find('.image-box');
                let $currentBoxList = $el.closest('.list-gallery-media-files');

                $currentBox.find('.image-data').val(firstItem.url);
                $currentBox.find('.preview_image').attr('src', firstItem.thumb).show();

                _.forEach(files, function (file, index) {
                    if (!index) {
                        return;
                    }
                    let template = $(document).find('#object_select_image_template').html();

                    let imageBox = template
                        .replace(/__name__/gi, $currentBox.find('.image-data').attr('name'));

                    let $template = $('<li class="object-image-item-handler">' + imageBox + '</li>');

                    $template.find('.image-data').val(file.url);
                    $template.find('.preview_image').attr('src', file.thumb).show();

                    $currentBoxList.append($template);
                });
            }
        });

    }
};

$(document).ready(function () {
    RealEstate.init();

    $('body').on('click', '.list-gallery-media-files .btn_remove_image', function (event) {
        event.preventDefault();
        $(this).closest('li').remove();
    });

    $(document).on('click', '.btn-trigger-remove-object-image', function (event) {
        event.preventDefault();
        $(this).closest('.object-image-item-handler').remove();
        if ($('.list-gallery-media-files').find('.object-image-item-handler').length === 0) {
            $('.default-placeholder-object-image').removeClass('hidden');
        }
    });

    $(document).on('change', '#type', event => {
        if ($(event.currentTarget).val() === 'rent') {
            $('#period').closest('.form-group').removeClass('hidden').fadeIn();
        } else {
            $('#period').closest('.form-group').addClass('hidden').fadeOut();
        }
    });

    $(document).on('change', '#never_expired', event => {
        if ($(event.currentTarget).is(':checked') === true) {
            $('#auto_renew').closest('.form-group').addClass('hidden').fadeOut();
        } else {
            $('#auto_renew').closest('.form-group').removeClass('hidden').fadeIn();
        }
    });
});