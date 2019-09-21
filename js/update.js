$(document).ready(function() {
    let page = getParameterByName('page');
    setInterval(function() {
        update(page);
    }, 15000);
});

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
        page: page
    }, function(data) {
        /*todo add to datatable */
    });
}