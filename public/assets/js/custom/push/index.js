$.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

const columns = [
  {data: 'name'},
  {data: 'provider'},
  {data: 'created_at'},
  {
    data: function (row, type, set) {
      changed_push_test_url = push_test_url.replace('id', row.id);

      let action_buttons = '<a href="' + changed_push_test_url + '"' +
        'id="send"' +
        'class="btn btn-icon btn-danger hover-scale"' +
        'data-bs-toggle="tooltip"' +
        'data-bs-placement="top"' +
        'title="Test Push Notification"' +
      '>' +
        '<i class="bi bi-send fs-5"></i>' +
      '</a>';

      return action_buttons;
    }
  }
];

const columnDef = [
  {
    targets: '_all',
    className: 'dt-left'
  }
];

let tableEle = document.getElementById('channel-list');
let channelDT = new DataTable(tableEle, {
  responsive: true,
  lengthMenu: [5, 10, 25, 100],
  dom:
    "<'#dTToolbar.row mb-2'" +
    "<'col-sm-6 d-flex align-items-center justify-content-end dt-toolbar'l>" +
    ">" +
    "<'table-responsive'tr>" +
    "<'row'" +
    "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
    "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
    ">"
  ,
  processing: true,
  serverSide: true,
  ajax: {
    url: push_data_url,
    type: 'POST',
    data: function (response) {
      return response.data;
    }
  },
  columns: columns,
  columnDefs: columnDef
});

// datatable row click
channelDT.on('click', 'tbody tr', function () {
  let data = channelDT.row(this).data();
  changed_push_detail_url = push_detail_url.replace('id', data.id);

  location.href = changed_push_detail_url;
});

// search box wrapper
let searchBox = document.getElementById('dTSearchW');
searchBox.classList.add('d-flex');
searchBox.hidden = false;

// datatable toolbar
let dTToolbar = document.getElementById('dTToolbar');
dTToolbar.insertBefore(searchBox, dTToolbar.firstChild);

// datatable page length
let pageLength = document.getElementById('dt-length-0');
pageLength.classList.remove('form-select-solid');
pageLength.classList.remove('form-select-sm');

// search function
let search = document.getElementById('dTSearch');
let searchBtn = document.getElementById('dTSearchBtn');
// search button onClick
searchBtn.addEventListener('click', function () {
  channelDT.search(search.value).draw();
});
// search on hit enter
search.addEventListener('keydown', function (event) {
  if (event.key === 'Enter') {
    channelDT.search(this.value).draw();
  }
});

// test push notification button
channelDT.on('click', '#send', function (event) {
  event.stopPropagation();
});

// test push notification button tooltip
channelDT.on('draw', function () {
  let tooltipElList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  tooltipElList.map(function (tooltipEl) {
    return new bootstrap.Tooltip(tooltipEl)
  })
});
