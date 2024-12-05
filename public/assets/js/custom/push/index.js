// const token = document.querySelector('meta[name="csrf-token"]');
$.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

const columns = [
  {data: 'name'},
  {data: 'provider'},
  {data: 'created_at'},
  {data: 'action'},
];

const columnDef = [
  {
    targets: '_all',
    className: 'dt-left'
  },
  // {
  //   targets: 1,
  //   render: function (data, type, row) {
  //     let provider = "<span class='badge badge-light-" + data.color + " fw-bold'>" +
  //       data.provider + "</span>";

  //     return provider;
  //   }
  // }
];

let tableEle = document.getElementById('channel-list');
let channelDT = new DataTable(tableEle, {
  responsive: true,
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

// search button onClick
let search = document.getElementById('dTSearch');
let searchBtn = document.getElementById('dTSearchBtn');
searchBtn.addEventListener('click', function () {
  channelDT.search(search.value).draw();
});