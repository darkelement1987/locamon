var monTable;
$(document).ready(function() {
    /*Table Init*/
    monTable = $('#mon_table').DataTable({
        order: [
            [2, "asc"]
        ],
        paging: true,
        lengthChange: true,
        searching: true,
        processing: true,
        ajax: {
            url: './functions/api.php?type=pokemon',
            dataSrc: 'data'
        },
        ordering: true,
        lengthChange: true,
        rowId: 'id',
        dom: 'l<"filter">frtip',
        initComplete: function() {
            $('.dataTables_length')
                .addClass('d-flex justify-content-between')
                .append(
                    '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#filtersModal">Filters</button>'
                );
            monSelect();
            formSelect();
        },
        columns: [{
            "title": "Pokemon",
            "name": "pokemon",
            "type": "string",
            "data": null,
            "createdCell": function(td, cellData, rowData, row, col) {
                let gradient;
                if (rowData.types.length === 1) {
                    gradient = '60deg, ' + rowData.types[0].color + ', transparent 50%';
                } else if (rowData.types.length === 2) {
                    gradient = '60deg, ' + rowData.types[0].color + ', ' + rowData
                        .types[1].color + ' 30%, transparent 60%';
                }
                $(td).css('background', 'linear-gradient(' + gradient + ')');
            },
            "render": {
                display: function(data, type, row) {
                    if (row.form !== '0' && row.form !== 'Normal') {
                        row.name = row.form + ' ' + row.name;
                    }
                    return `<img class="img-fluid" src="${row.sprite}" /><strong>${row.name}</strong></td>`;
                },
                sort: "name",
                _: "name",
                filter: "name"
            },
            "className": "all"
        }, {
            "title": "Link",
            "name": "link",
            "data": null,
            "render": {
                display: function(data, type, row) {
                    return `<a href="${mapLink}?lat=${row.lat}&?lon=${row.lon}">Maps</a>`;
                }
            },
            "searchable": false,
            "orderable": false,
            "classname": "all"
        }, {
            "title": "Disappears",
            "name": 'disappears',
            "data": null,
            "type": "date",
            "render": {
                display: function(data, type, row) {
                    return `<div><strong>${row.disappear_time}</strong></div><div class="countdown" data-timestamp="${row.expires}"></div>`;
                },
                sort: "expires",
                _: "disappear_time",
                filter: "expires"
            },
            "classname": "all"
        }, {
            "title": "Updated",
            "name": "updated",
            "data": null,
            "type": "date",
            "render": {
                display: "last_modified",
                sort: "updated",
                _: "last_modified",
                filter: "updated"
            }
        }, {
            "title": "Encountered",
            "name": "encountered",
            "type": "num",
            "data": null,
            "render": {
                display: function(data, type, row) {
                    if (row.iv !== '' && row.iv !== null) {
                        return `<i class="far fa-check-square fa-2x showIv text-success"></i> ${row.iv}% `;
                    } else {
                        return '<i class="far fa-times-circle fa-2x"></i>';
                    }
                },
                filter: function(data, type, row) {
                    if (row.iv !== '' && row.iv !== null) {
                        return row.iv;
                    } else {
                        return '0';
                    }
                },
                sort: function(data, type, row) {
                    if (row.iv === '' || row.iv === null) {
                        return 0;
                    } else {
                        return row.iv;
                    }
                }
            },
            "searchable": false
        }, {
            "name": "iv",
            'visible': false,
            'data': 'iv'
        }, {
            'name': "atk_iv",
            'visible': false,
            'data': 'atk_iv'
        }, {
            'name': "def_iv",
            'visible': false,
            'data': 'def_iv'
        }, {
            'name': "sta_iv",
            'visible': false,
            'data': 'sta_iv'
        }, {
            'name': "cp",
            'visible': false,
            'data': 'cp'
        }, {
            'name': "level",
            'visible': false,
            'data': 'level'
        }, {
            'name': "form",
            'visible': false,
            'data': 'form'
        }, {
            'name': "expires",
            'visible': false,
            'data': 'expires',
            'type': 'num'
        }]
    });
    /*event watchers*/
    monTable.on('draw', countdown(monTable));
    monTable.on('init', countdown(monTable));
    monTable.on('init', update('pokemon'));
    /*child row function*/
    $('#mon_table').on('click', '.showIv', function() {
        var tr = $(this).closest('tr');
        var row = monTable.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row (the format() function would return the data to be shown)
            if (row.child() && row.child().length) {
                row.child.show();
            } else {
                row.child(monChild(row.data())).show();
            }
            tr.addClass('shown');
        }
    });
    //search functions
    $('#filtersModal').on('click', '.filter-clear', function(e) {
        monTable.search('').draw();
    });
    $('#filtersModal').on('input', 'input', function(e) {
        monTable.draw();
    });

});

function monChild(data) {
    let formLine;
    if (data.static_map !== '') {
        let image = data.static_map;
    }
    let atk_color = ivColors(data.atk_iv);
    let sta_color = ivColors(data.sta_iv);
    let def_color = ivColors(data.def_iv);
    if (data.form !== '0' && data.form !== "Normal") {
        formLine = `<li><strong>Form - </strong>${data.form}</li>`
    } else {
        formLine = '';
    }
    const row = `
            <div class="card">
                <div class="card-body">
                    <h5 class="text-left"><span style="text-decoration: underline;">Stats</span></h5>
                    <hr />
                    <div class="row">
                        <div class="col">
                            <div class="row">
                                <div class="col-3 d-flex justify-content-center align-items-center">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td><strong>IV - ${data.iv}</strong></td>
                                                    <td class="d-flex bg-secondary text-light justify-content-around align-items-center">
                                                        <i class="fas fa-fist-raised fa-lg"></i><span style="color:${atk_color}"> ${data.atk_iv} </span>
                                                        <i class="fa fa-shield-alt fa-lg"></i><span style="color:${def_color}"> ${data.def_iv} </span>
                                                        <i class="fas fa-plus-circle fa-lg"></i><span style="color:${sta_color}"> ${data.sta_iv} </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Level - ${data.level}</strong><br /></td>
                                                    <td><strong>CP - ${data.cp}</strong></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-4 d-flex flex-column justify-content-center align-items-center">
                                    <ul class="list-unstyled">
                                        <li><strong>Quick Move </strong>- ${data.move_1}</li>
                                        <li><strong>Charge Move </strong>- ${data.move_2}</li>
                                        <li><strong>Gender - </strong>${data.gender}</li>
                                        ${formLine}
                                    </ul>
                                </div>
                                <div class="col-4 d-flex justify-content-around">
                                    <div class="row d-flex justify-content-center">
                                        <img src="${data.static_map}" alt="static map" height="150"/>
                                    </div>
                                    <div class="row">
                                        <div class="col d-flex flex-column justify-content-around">
                                            <a href="http://maps.apple.com/?sll=${data.lat},${data.lon}">
                                                <img class="img-fluid" width="40" height="40" src="./images/AppleMaps_logo.svg" />
                                            </a>
                                            <a href="$google_dir = "https://www.google.com/maps/dir/?api=1&destination=${data.lat},${data.lon}">
                                                <img class="img-fluid" src="./images/GoogleMaps_logo.svg" width="40" height="40" />
                                            </a>
                                            <a href="https://www.waze.com/ul?ll=${data.lat}%2C${data.lon}&navigate=yes">
                                                <img class="img-fluid" src="./images/Waze_logo.svg" width="40" height="40" />
                                            </a>
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
/*pokemon filter modal*/
function monSelect() {
    var data = [];
    var mons = [];
    $.each(monTable.rows().data().sort().unique(),
        function(i, v) {
            let name = v.name.replace(v.form, '').trim();
            if (!mons.includes(name)) {
                mons.push(name);
                data.push({
                    id: name,
                    text: name,
                    sprite: v.sprite
                });
            }
        });
    $('#monSelect').select2({
        width: '100%',
        data: data,
        placeholder: {
            id: "",
            placeholder: ""
        },
        allowClear: true,
        closeOnSelect: false,
        dropdownParent: $('#monFilter'),
        containerCssClass: 'select2-container',
        dropdownCssClass: 'select2-dropdown',
        dropdownPosition: 'below',
        templateResult: function(d) {
            if (!d.id) {
                return $(d.text);
            } else {
                return $(`<div class="mon-option">
                                <img class="img mon-image" width="32" height="32" src="` +
                    d.sprite + `"></img>
                                <span class="mon-name">` + d.text + `</span>
                            </div>`);
            }
        }
    });
    $('#monSelect').on('change', function() {
        monTable.draw();
    })
}

function formSelect() {
    let data = [];
    let forms = [];
    $.each(monTable.rows().data().sort().unique(),
        function(i, v) {
            if (!forms.includes(v.form) && v.form !== '0' && v.form !== 'Alola' && v.form !== 'Shiny') {
                forms.push(v.form)
                data.push({
                    id: v.form,
                    text: v.form,
                    sprite: v.sprite
                });
            }
        });
    $('#formSelect').select2({
        width: '100%',
        data: data,
        placeholder: {
            id: "",
            placeholder: ""
        },
        allowClear: true,
        closeOnSelect: false,
        dropdownParent: $('.other-forms'),
        containerCssClass: 'select2-container',
        dropdownCssClass: 'select2-dropdown',
        dropdownPosition: 'below',
        templateResult: function(d) {
            if (!d.id) {
                return $(d.text);
            } else {
                return $(`<div class="mon-option">
                                <img class="img mon-image" width="32" height="32" src="` +
                    d.sprite + `"></img>
                                <span class="mon-name">` + d.text + `</span>
                            </div>`);
            }
        }
    });
}

$(document)
    .on('input', '#timeselect', function() {
        var val = $(this).val();
        var active = false;
        $('.time-value')
            .html('Pokemon With <strong>' + val.toString() + '</strong> Min Remainings');
        if (val == '0' || val == '45') {
            $('.time-value').html('Not Filtering By Time');
        } else if (val != '0' || val != '45') {
            active = true
        }
    });



//custom search functions
const customFilters = [
    function(settings, data, dataIndex) {
        const time = new Date().getTime();
        const val = parseInt($('#timeselect').val());
        const offset = time + val;
        if (parseInt(data[12]) * 1000 <= offset || isNaN($('#timeselect').val())) {
            return true;
        } else {
            return false;
        }
    },
    function(settings, data, dataIndex) {
        let min = parseInt($('#levelmin').val());
        let max = parseInt($('#levelmax').val());
        const level = data[10];
        if (min === 0) {
            min = null;
        }
        if (max === 0) {
            max = null;
        }
        if ((isNaN(min) && isNaN(max)) ||
            (isNaN(min) && level <= max) ||
            (min <= level && isNaN(max)) ||
            (min <= level && level <= max)) {
            return true;
        } else {
            return false;
        }
    },
    function(settings, data, dataIndex) {
        let min = parseInt($('#cpmin').val());
        let max = parseInt($('#cpmax').val());
        const cp = data[9];
        if (min === 0) {
            min = null;
        }
        if (max === 0) {
            max = null;
        }
        if ((isNaN(min) && isNaN(max)) ||
            (isNaN(min) && cp <= max) ||
            (min <= cp && isNaN(max)) ||
            (min <= cp && cp <= max)) {
            return true;
        } else {
            return false;
        }
    },
    function(settings, data, dataIndex) {
        let min = parseInt($('#ivmin').val());
        let max = parseInt($('#ivmax').val());
        const iv = data[5];
        if (min === 0) {
            min = null;
        }
        if (max === 0) {
            max = null;
        }
        if ((isNaN(min) && isNaN(max)) ||
            (isNaN(min) && iv <= max) ||
            (min <= iv && isNaN(max)) ||
            (min <= iv && iv <= max)) {
            return true;
        } else {
            return false;
        }
    },
    function(settings, data, dataIndex) {
        let min = parseInt($('#atkmin').val());
        let max = parseInt($('#atkmax').val());
        const atk = data[6];
        if (min === 0) {
            min = null;
        }
        if (max === 0) {
            max = null;
        }
        if ((isNaN(min) && isNaN(max)) ||
            (isNaN(min) && atk <= max) ||
            (min <= atk && isNaN(max)) ||
            (min <= atk && atk <= max)) {
            return true;
        } else {
            return false;
        }
    },
    function(settings, data, dataIndex) {
        let min = parseInt($('#defmin').val());
        let max = parseInt($('#defmax').val());
        const def = data[7];
        if (min === 0) {
            min = null;
        }
        if (max === 0) {
            max = null;
        }
        if ((isNaN(min) && isNaN(max)) ||
            (isNaN(min) && def <= max) ||
            (min <= def && isNaN(max)) ||
            (min <= def && def <= max)) {
            return true;
        } else {
            return false;
        }
    },
    function(settings, data, dataIndex) {
        let min = parseInt($('#stamin').val());
        let max = parseInt($('#stamax').val());
        const sta = data[8];
        if (min === 0) {
            min = null;
        }
        if (max === 0) {
            max = null;
        }
        if ((isNaN(min) && isNaN(max)) ||
            (isNaN(min) && sta <= max) ||
            (min <= sta && isNaN(max)) ||
            (min <= sta && sta <= max)) {
            return true;
        } else {
            return false;
        }
    },
    function(settings, data, dataIndex) {
        const mons = $('#monSelect').val();
        if (mons.includes(data[0]) || mons.length === 0) {
            return true;
        } else {
            return false;
        }
    },
    function(settings, data, dataIndex) {
        const forms = $('#formSelect').val();
        if (forms.includes(data[11]) || forms.length === 0) {
            return true;
        } else {
            return false;
        }
    }
];
customFilters.forEach(function(v, i) {
    $.fn.dataTable.ext.search.push(v);
});