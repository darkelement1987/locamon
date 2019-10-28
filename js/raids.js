var raidTable;
$(document).ready(function() {
    /*table init*/
    raidTable = $('#raid_table').DataTable({
        paging: true,
        processing: true,
        ajax: {
            url: './functions/api.php?type=raids',
            dataSrc: 'data'
        },
        lengthChange: true,
        searching: true,
        ordering: true,
        responsive: true,
        rowId: 'id',
        order: [
            [4, "asc"]
        ],
        columns: [{
                "title": "Boss",
                "data": null,
                "type": "string",
                "render": {
                    display: function(data, type, row) {
                        if (row.form !== null && row.form !== '0' && row.form !==
                            'Normal') {
                            row.name = row.form + ' ' + row.name;
                        }
                        return `<img class="img-fluid" src="${row.sprite}" /><strong>${row.name}</strong></td>`;
                    },
                    sort: "name",
                    _: "name",
                    filter: "name"
                },
                "className": "all"
            },
            {
                "title": "Level",
                "type": "num",
                "data": null,
                "render": {
                    display: "stars",
                    sort: "level",
                    _: "level",
                    filter: "level"
                },
                "className": "all"
            },
            {
                "title": "CP",
                "data": "cp",
                "type": "num",
                "className": "all",
                "searchable": false
            },
            {
                "title": "Map",
                "data": null,
                "type": "string",
                "render": {
                    display: function(data, type, row) {
                        return `<a href="${row.link}" >${row.gym_name}</a>`;
                    },
                    sort: "gym_name",
                    _: "gym_name",
                    filter: "gym_name"
                },
                "className": "all"
            },
            {
                "title": "Time",
                "data": null,
                "type": "date",
                "render": {
                    display: function(data, type, row) {
                        let word, time, countdown;
                        if (row.pokemon_id === '0') {
                            word = "Hatches";
                            time = row.time_start
                            countdown = row.start
                        } else {
                            word = "Expires",
                                time = row.time_end
                            countdown = row.end
                        }
                        return `<div><strong>${word} </strong>${time}</div><div class="countdown" data-timestamp="${countdown}"></div>`;
                    },
                    sort: "end",
                    _: "time_end",
                    filter: "end"
                },
                "className": "all"
            }
        ]
    });
    /*event watchers*/
    //raidTable.on('draw', countdown(raidTable));
    raidTable.on('init', update('raids'));

    $('#raid_table').on('click', 'tr', function() {
        var tr = $(this);
        var row = raidTable.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row (the format() function would return the data to be shown)
            if (row.child() && row.child().length) {
                row.child.show();
            } else {
                row.child(raidChild(row.data())).show();
            }
            tr.addClass('shown');
        }
    });
});

function raidChild(data) {
    let times = ['Hatched', 'Disappears'];
    let ex = 'No';
    let team = 'Uncontested';
    if (data.pokemon_id === '0') {
        times = ['Appeared', 'Hatches'];
    }
    if (data.ex === '1') {
        ex = 'yes';
    }
    if (data.team_id === '1') {
        team = "Mystic";
    } else if (data.team_id === '2') {
        team = "Valor";
    } else if (data.team_id === '3') {
        team = "Instinct"
    }
    if (data.static_map !== '') {
        image = data.static_map;
    }
    const row = `
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-6 ">
                    <div class="row d-flex justify-content-around">
                        <div class="col">
                            <h6><span style="text-decoration: underline;">Moveset</span></h6>
                            <ul class="list-unstyled">
                                <li><strong>Quick </strong>- ${data.move_1}</li>
                                <li><strong>Charge </strong>- ${data.move_2}</li>
                            </ul>
                        </div>
                        <div class="col-6 col-xl-3">
                            <h6><span style="text-decoration: underline;">Times</span></h6>
                            <ul class="list-unstyled">
                                <li><strong>${times[0]} </strong>- ${data.time_spawn}</li>
                                <li><strong>${times[1]} </strong> </strong>- ${data.time_end}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col" style="padding: 0px;margin: 0px;margin-top: -20px;margin-bottom: -20px;margin-right: -5px;">
                    <div class="border rounded border-primary justify-content-between align-items-center p-2" style="height: 100%;width: 100%;">
                        <div class="row d-flex" style="margin: 0px;">
                            <div class="col-5" style="padding: 0px;">
                                <h5 class="text-left ml-2"><span style="text-decoration: underline;">Gym Info</span></h5>
                                <ul class="list-unstyled mt-4">
                                    <li><strong>Control </strong>- ${team}</li>
                                    <li><strong>Open Slots </strong>- ${data.slots_available}</li>
                                    <li><strong>EX-Eligible </strong>- ${ex}</li>
                                </ul>
                            </div>
                            <div class="col-7 d-flex flex-column justify-content-between">
                                <div class="row">
                                    <div class="col d-flex justify-content-center"><img class="img rounded" width="240px" height="120px" src="${image}" /></div>
                                </div>
                                <div class="row">
                                    <div class="col d-flex justify-content-between"><a href="http://maps.apple.com/?sll=${data.lat},${data.lon}"><img class="img-fluid" width="40" height="40" src="./images/AppleMaps_logo.svg" /></a><a href="$google_dir = "https://www.google.com/maps/dir/?api=1&destination=${data.lat},${data.lon}"><img class="img-fluid" src="./images/GoogleMaps_logo.svg" width="40" height="40" /></a><a href="https://www.waze.com/ul?ll=${data.lat}%2C${data.lon}&navigate=yes"><img class="img-fluid" src="./images/Waze_logo.svg" width="40" height="40" /></a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>`;
    return row;
}