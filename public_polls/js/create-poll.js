$(document).ready(function() {
    'use strict';

    function getNewOptionIndex(textWithIds) {
        var ids = [];
        var regExp = /polloption-(\d+)-name/g;

        var matches;
        while ((matches = regExp.exec(textWithIds)) !== null) {
            ids.push(matches[1]);
        }

        return ids.length !== 0 ? (Math.max.apply(null, ids) + 1) : 0;
    }

    function buildAndAppendPollOption(id, $pollOptions) {
        var $wrapperFormGroup = $('<div class="form-group field-polloption-' + id + '-name required">').appendTo($pollOptions);
        var $wrapperInputGroup = $('<div class="input-group">').appendTo($wrapperFormGroup);

        $wrapperInputGroup.append('<input type="text" maxlength="45" id="polloption-' + id + '-name" class="form-control input-sm" name="PollOption[' + id + '][name]" placeholder="Текст варианта">');
        $wrapperInputGroup.append('<div class="input-group-btn"><button type="button" class="btn btn-sm btn-default poll-option-drag-btn"><span class="glyphicon glyphicon-resize-vertical"></span></button><button type="button" class="btn btn-sm btn-default pol-option-remove"><span class="glyphicon glyphicon-remove"></span></button></div>');
        $wrapperFormGroup.append('<div class="help-block">');


        $('#create-poll').yiiActiveForm('add', {
            "id": "polloption-" + id + "-name",
            "name": "[" + id + "]name",
            "container": ".field-polloption-" + id + "-name",
            "input": "#polloption-" + id + "-name",
            "validate": function (attribute, value, messages, deferred, $form) {
                window.yii.validation.required(value, messages, {
                    "message": "Необходимо заполнить «Текст варианта»."
                });
                window.yii.validation.string(value, messages, {
                    "message": "Значение «Текст варианта» должно быть строкой.",
                    "max": 60,
                    "tooLong": "Значение «Текст варианта» должно содержать максимум 45 символа.",
                    "skipOnEmpty": 1
                });
            }
        });

        return $wrapperFormGroup;
    }


    var $pollOptions = $('#create-poll').find('.poll-options');
    if ($pollOptions.length !== 0) {
        $pollOptions.sortable({
            handle: '.poll-option-drag-btn',
            items: '> .form-group',
            tolerance: 'pointer', //anti-bug with moving to the first and the last positions
            cancel: '',
            axis: 'y',
            containment: 'parent'
        });

        $pollOptions.on('click', '.pol-option-remove', function () {
            $(this).closest('.form-group').remove();
        });

        $('#create-poll').on('beforeSubmit', function () {
            var currentId = 0;

            $pollOptions.children().each(function () {
                $(this).removeClass(function (index, className) {
                    return (className.match(/field-polloption-\d+-name/g) || []).join(' ');
                });
                $(this).addClass('field-polloption-' + currentId + '-name');

                var $input = $(this).find('input');
                $input.attr('id', 'polloption-' + currentId + '-name');
                $input.attr('name', 'PollOption[' + currentId + '][name]');

                currentId += 1;
            });
        });

        $('#add-poll-option').on('click', function () {
            var newPollId = getNewOptionIndex($('#create-poll').html());

            buildAndAppendPollOption(newPollId, $pollOptions);
        });
    }
});
