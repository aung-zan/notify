const token = document.querySelector('meta[name="csrf-token"]');
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

const columns = [
  {data: 'name'},
  {
    data: 'provider_name',
    render: function (data, type, row) {
      return '<span class="badge badge-outline badge-primary">' + data + '</span>';
    }
  },
  {data: 'created_at'},
  {data: 'updated_at'},
  {
    data: function (row, type, set) {
      const changed_email_test_url = email_test_url.replace('id', row.id);

      let action_buttons = '<a href="' + changed_email_test_url + '"' +
        'id="send"' +
        'class="btn btn-outline btn-outline-danger hover-scale"' +
        'data-bs-toggle="tooltip"' +
        'data-bs-placement="top"' +
        'title="Test Email Notification"' +
      '>' +
        '<i class="bi bi-send fs-5"></i>' +
        'Test' +
      '</a>';

      return action_buttons;
    }
  }
];

const columnDef = [
  {
    targets: '_all',
    className: 'dt-left'
  },
  {
    targets: 4,
    orderable: false,
    searchable: false,
  }
];

let tableEle = document.getElementById('email-list');
let emailDT = new DataTable(tableEle, {
  responsive: true,
  // scrollY: "350px",
  // scrollCollapse: true,
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
  columns: columns,
  columnDefs: columnDef,
  order: [[0, 'asc']],
  processing: true,
  serverSide: true,
  ajax: {
    url: email_data_url,
    type: 'POST',
    data: function (response) {
      return response.data;
    }
  }
});

// datatable row click
emailDT.on('click', 'tbody tr', function () {
  let data = emailDT.row(this).data();
  const changed_email_detail_url = email_detail_url.replace('id', data.id);

  location.href = changed_email_detail_url;
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
  emailDT.search(search.value).draw();
});
// search on hit enter
search.addEventListener('keydown', function (event) {
  if (event.key === 'Enter') {
    emailDT.search(this.value).draw();
  }
});

// test push notification button
emailDT.on('click', '#send', function (event) {
  event.stopPropagation();
});

// test push notification button tooltip
emailDT.on('draw', function () {
  let tooltipElList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  tooltipElList.map(function (tooltipEl) {
    return new bootstrap.Tooltip(tooltipEl)
  })
});
