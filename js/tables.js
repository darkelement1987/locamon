function create_tables(page) {

}
columns: [{
    "Title": "Boss",
    "data": "name",
    "render": function(data, type, row) {
        if (type === 'display') {
            return `<img class="img-fluid" src="${data.sprite}" /><strong>${data.name}</strong></td>;`
        } else if (type === 'sort') {
            return data.name;
        } else {
            return data;
        }
    }
}, {
    "Title": "Level",
    "data": "level",
    "format": function(data, type, row) {
        if (type === 'display') {
            return `<div><span><strong>CP:</strong> ${data.cp}</span></div><div>${data.stars}</div>`;
        } else if (type === 'sort') {
            return data.level;
        } else {
            return data;
        }
    }
}, {
    "Title": "Map",
    "data": "gym",
    "format": function(data, type, row) {
        if (type === 'display') {
            return `<a href="${data.mapLink}" >${data.gym_name}</a>`;
        } else if (type === 'sort') {
            return data.level;
        } else {
            return data;
        }
    }
}, {
    "Title": "Expires",
    "data": "countdown",
    "format": function(data, type, row) {
        if (type === 'display') {
            return `<strong>Expires </strong>${data.time_end}<br />${data.end}</<strong>`;
        } else if (type === 'sort') {
            return data.countdown;
        } else {
            return data;
        }
    }
}]