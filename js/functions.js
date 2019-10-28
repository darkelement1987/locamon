$(document).on('ready', function() {
    var faPokeball = {
        prefix: "fa",
        iconName: "pokeball",
        icon: [1024, 1024, [], "e001", `M 512.00, 359.18 C 512.00, 359.18 512.00, 359.18 512.00, 359.18 596.40, 359.18 664.81, 427.60 664.82, 512.00 664.82, 512.00 664.82, 512.00 664.82, 512.00 664.82, 596.40 596.40, 664.81 512.00, 664.82 512.00, 664.82 512.00, 664.82 512.00, 664.82 427.60, 664.82 359.19, 596.40 359.18, 512.00 359.18, 512.00 359.18, 512.00 359.18, 512.00 359.18, 427.60 427.60, 359.18 512.00, 359.18 512.00, 359.18 512.00, 359.18 512.00, 359.18 512.00, 359.18 512.00, 359.18 512.00, 359.18 Z M 512.00, 410.12 C 455.73, 410.12 410.12, 455.73 410.12, 512.00 410.12, 568.27 455.73, 613.88 512.00, 613.88 568.27, 613.88 613.88, 568.27 613.88, 512.00 613.88, 455.73 568.27, 410.12 512.00, 410.12 512.00, 410.12 512.00, 410.12 512.00, 410.12 Z M 806.16, 217.84 C 884.17, 295.86 928.00, 401.67 928.00, 512.00 928.00, 622.33 884.17, 728.14 806.16, 806.16 728.14, 884.17 622.33, 928.00 512.00, 928.00 401.67, 928.00 295.86, 884.17 217.84, 806.16 139.83, 728.14 96.00, 622.33 96.00, 512.00 96.00, 401.67 139.83, 295.86 217.84, 217.84 295.86, 139.83 401.67, 96.00 512.00, 96.00 622.33, 96.00 728.14, 139.83 806.16, 217.84 Z M 332.02, 512.00 C 332.02, 512.00 138.45, 512.00 138.45, 512.00 138.45, 611.07 177.81, 706.09 247.86, 776.14 317.91, 846.19 412.93, 885.55 512.00, 885.55 611.07, 885.55 706.09, 846.19 776.14, 776.14 846.19, 706.09 885.55, 611.07 885.55, 512.00 885.55, 512.00 885.55, 512.00 885.55, 512.00 885.55, 512.00 691.98, 512.00 691.98, 512.00 691.98, 412.60 611.40, 332.02 512.00, 332.02 412.60, 332.02 332.02, 412.60 332.02, 512.00 Z`]
    };
    var poke = FontAwesome.library.add(faPokeball);
    $('.fa-pokeball').html(FontAwesome.icon(faPokeball).html);
});

function ivColors(iv) {
    iv = parseInt(iv);
    if (iv <= 6) {
        return '#ff0000';
    } else if (iv > 6 && iv <= 11) {
        return '#fada5e'
    } else if (iv > 11) {
        return '#00ff00';
    }
}

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
    $('.countdown')
        .each(function() {
            var timestamp = $(this).data('timestamp');
            var timeOne = new Date(timestamp);
            var timeNow = new Date();
            if (timeOne <= timeNow) {
                table.row($(this).closest('tr')).remove().draw();
                return false;
            } else {
                $(this)
                    .countdown(timestamp, function(event) {
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
            }
        });
}