function update(page) {
    if (page !== '') {
        countdown();
        setInterval(function() {
            update(page);
        }, updateTime * 1000);
    }
}

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

function update(page) {
    $.get('./functions/api.php', {
        type: page,
        update: '1'
    }, function(data) {
        data = JSON.parse(data);
        data = data.data;
        if (data.length > 0) {
            if (page == 'raids') {
                window.raidTable.rows.add(data);
            }
        }
    });
}

function countdown(table) {
    if (table === 'mon') {
        table = monTable;
    } else if (table === 'raid') {
        table = raidTable;
    }
    $('.countdown')
        .each(function() {
            var timestamp = $(this).data('timestamp');
            var timeOne = new Date(timestamp * 1000);
            var timeNow = new Date();
            if (timeOne <= timeNow) {
                table.row($(this).closest('tr')).remove().draw();
                return false;
            }
            $(this)
                .countdown(timestamp * 1000, function(event) {
                    var timer = event.strftime('%M:%S');
                    $(this)
                        .html('<span>' + timer + '</span><span>Remaining</span>');
                })
                .on('update.countdown', function(event) {
                    var min = event.strftime('%M');
                    min = parseInt(min);
                    if (min >= 15) {
                        $(this)
                            .addClass('text-success');
                    } else if (min >= 6 && min <= 15) {
                        $(this)
                            .addClass('text-warning');
                        if ($(this)
                            .hasClass('text-success')) {
                            $(this)
                                .removeClass('text-success');
                        }
                    } else if (min <= 6) {
                        $(this)
                            .addClass('text-danger');
                        if ($(this)
                            .hasClass('text-warning')) {
                            $(this)
                                .removeClass('text-warning');
                        }
                    }
                })
                .on('finish.countdown', function() {
                    table.row($(this).closest('tr')).remove().draw();
                });
        });


}