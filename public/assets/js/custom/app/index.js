$.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

const columns = [
  {data: 'name'},
  {
    data: 'notifications',
    render: function (data, type, row) {
      return '<span class="badge badge-outline badge-primary">' + data + '</span>';
    }
  },
  {
    data: 'channels',
    render: function (data, type, row) {
      const channel = '<span class="badge badge-outline badge-success mb-2">' + data + '</span>';
      // '<br />' +
      // '<span class="badge badge-outline badge-success mb-2">Email Testing - SMTP</span>';

      return channel;
    }
  },
  {data: 'created_at'},
  {data: 'updated_at'},
  {
    data: function (row, type, set) {
      const appShowURL = app_detail_url.replace('id', row.id);

      const actionDiv = document.querySelector('.dropdown').cloneNode(true);
      actionDiv.removeAttribute('hidden');

      const appShowBtn = actionDiv.querySelector('.details');
      appShowBtn.setAttribute('href', appShowURL);

      return actionDiv;
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

let tableEle = document.getElementById('app-list');
let appDT = new DataTable(tableEle, {
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
  columns: columns,
  columnDefs: columnDef,
  order: [[0, 'asc']],
  processing: true,
  serverSide: true,
  ajax: {
    url: app_data_url,
    type: 'POST',
    data: function (response) {
      return response.data;
    }
  }
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
  appDT.search(search.value).draw();
});
// search on hit enter
search.addEventListener('keydown', function (event) {
  if (event.key === 'Enter') {
    appDT.search(this.value).draw();
  }
});
