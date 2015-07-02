$(document).ready(function() {
    var $votePoll = $('#vote-poll');
    var $votePollResults = $('#vote-poll-results');

    if ($votePollResults.length !== 0 || $votePoll.length !== 0) {
        var $pollId = location.href.match(/poll\/(\d+)/)[1];
        var $documentBody = $('body');


        var superEC = new evercookie({
            history: false, //DO NOT TURN ON  // CSS history knocking or not .. can be network intensive
            java: false, // Java applet on/off... may prompt users for permission to run.
            tests: 1,  // 1000 what is it, actually?
            silverlight: false, // you might want to turn it off https://github.com/samyk/evercookie/issues/45
            asseturi: '/eassets' // assets = .fla, .jar, etc
        });
        delete evercookie;

        superEC.get('poll-' + $pollId, function(value) {
            if ($votePoll.length !== 0) {
                $documentBody.data('pollInfo', value || '0');

                if (typeof value === 'undefined') {
                    $votePoll.find('input').removeAttr('disabled');
                } else {
                    window.location.replace('/poll/' + $pollId);
                }
            } else {
                if (typeof value !== 'undefined') {
                    $votePollResults.find('.to-poll')
                        .text('Вы голосовали в этом опросе')
                        .addClass('disabled')
                        .on('click', function () {
                            return false;
                        });
                }
            }
        });


        if ($votePoll.length !== 0) {
            $votePoll.on('beforeSubmit', function () {
                superEC.set('poll-' + $pollId, '1');

                $(this).append($('<input type="hidden" name="PollVoteCheckbox[pollInfo]">').val($documentBody.data('pollInfo')));
            });
        }
    }
});
