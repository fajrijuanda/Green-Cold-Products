/**
 *  Project List Dashboard
 */

'use strict';

(function () {
  let borderColor, labelColor, headingColor, legendColor;

  if (isDarkStyle) {
    borderColor = config.colors_dark.borderColor;
    labelColor = config.colors_dark.textMuted;
    headingColor = config.colors_dark.headingColor;
    legendColor = config.colors_dark.bodyColor;
  } else {
    borderColor = config.colors.borderColor;
    labelColor = config.colors.textMuted;
    headingColor = config.colors.headingColor;
    legendColor = config.colors.bodyColor;
  }

  // Chart Colors
  const chartColors = {
    donut: {
      series1: config.colors.success,
      series2: '#53D28C',
      series3: '#7EDDA9',
      series4: '#A9E9C5'
    },
    line: {
      series1: config.colors.warning,
      series2: config.colors.primary,
      series3: '#7367f029'
    }
  };

  // Shipment statistics Chart
  // --------------------------------------------------------------------
  const shipmentEl = document.querySelector('#shipmentStatisticsChart'),
    shipmentConfig = {
      series: [
        {
          name: 'Shipment',
          type: 'column',
          data: [38, 45, 33, 38, 32, 50, 48, 40, 42, 37]
        },
        {
          name: 'Delivery',
          type: 'line',
          data: [23, 28, 23, 32, 28, 44, 32, 38, 26, 34]
        }
      ],
      chart: {
        height: 320,
        type: 'line',
        stacked: false,
        parentHeightOffset: 0,
        toolbar: { show: false },
        zoom: { enabled: false }
      },
      markers: {
        size: 5,
        colors: [config.colors.white],
        strokeColors: chartColors.line.series2,
        hover: { size: 6 },
        borderRadius: 4
      },
      stroke: {
        curve: 'smooth',
        width: [0, 3],
        lineCap: 'round'
      },
      legend: {
        show: true,
        position: 'bottom',
        markers: {
          width: 8,
          height: 8,
          offsetX: -3
        },
        height: 40,
        itemMargin: {
          horizontal: 10,
          vertical: 0
        },
        fontSize: '15px',
        fontFamily: 'Public Sans',
        fontWeight: 400,
        labels: {
          colors: headingColor,
          useSeriesColors: false
        },
        offsetY: 10
      },
      grid: {
        strokeDashArray: 8,
        borderColor
      },
      colors: [chartColors.line.series1, chartColors.line.series2],
      fill: {
        opacity: [1, 1]
      },
      plotOptions: {
        bar: {
          columnWidth: '30%',
          startingShape: 'rounded',
          endingShape: 'rounded',
          borderRadius: 4
        }
      },
      dataLabels: { enabled: false },
      xaxis: {
        tickAmount: 10,
        categories: ['1 Jan', '2 Jan', '3 Jan', '4 Jan', '5 Jan', '6 Jan', '7 Jan', '8 Jan', '9 Jan', '10 Jan'],
        labels: {
          style: {
            colors: labelColor,
            fontSize: '13px',
            fontWeight: 400
          }
        },
        axisBorder: { show: false },
        axisTicks: { show: false }
      },
      yaxis: {
        tickAmount: 4,
        min: 0,
        max: 50,
        labels: {
          style: {
            colors: labelColor,
            fontSize: '13px',
            fontFamily: 'Public Sans',
            fontWeight: 400
          },
          formatter: function (val) {
            return val + '%';
          }
        }
      },
      responsive: [
        {
          breakpoint: 1400,
          options: {
            chart: { height: 320 },
            xaxis: { labels: { style: { fontSize: '10px' } } },
            legend: {
              itemMargin: {
                vertical: 0,
                horizontal: 10
              },
              fontSize: '13px',
              offsetY: 12
            }
          }
        },
        {
          breakpoint: 1025,
          options: {
            chart: { height: 415 },
            plotOptions: { bar: { columnWidth: '50%' } }
          }
        },
        {
          breakpoint: 982,
          options: { plotOptions: { bar: { columnWidth: '30%' } } }
        },
        {
          breakpoint: 480,
          options: {
            chart: { height: 250 },
            legend: { offsetY: 7 }
          }
        }
      ]
    };
  if (typeof shipmentEl !== undefined && shipmentEl !== null) {
    const shipment = new ApexCharts(shipmentEl, shipmentConfig);
    shipment.render();
  }

  // Reasons for delivery exceptions Chart
  // --------------------------------------------------------------------
  const deliveryExceptionsChartE1 = document.querySelector('#deliveryExceptionsChart'),
    deliveryExceptionsChartConfig = {
      chart: {
        height: 420,
        parentHeightOffset: 0,
        type: 'donut'
      },
      labels: ['Incorrect address', 'Weather conditions', 'Federal Holidays', 'Damage during transit'],
      series: [13, 25, 22, 40],
      colors: [
        chartColors.donut.series1,
        chartColors.donut.series2,
        chartColors.donut.series3,
        chartColors.donut.series4
      ],
      stroke: {
        width: 0
      },
      dataLabels: {
        enabled: false,
        formatter: function (val, opt) {
          return parseInt(val) + '%';
        }
      },
      legend: {
        show: true,
        position: 'bottom',
        offsetY: 10,
        markers: {
          width: 8,
          height: 8,
          offsetX: -3
        },
        itemMargin: {
          horizontal: 15,
          vertical: 5
        },
        fontSize: '13px',
        fontFamily: 'Public Sans',
        fontWeight: 400,
        labels: {
          colors: headingColor,
          useSeriesColors: false
        }
      },
      tooltip: {
        theme: false
      },
      grid: {
        padding: {
          top: 15
        }
      },
      plotOptions: {
        pie: {
          donut: {
            size: '77%',
            labels: {
              show: true,
              value: {
                fontSize: '24px',
                fontFamily: 'Public Sans',
                color: headingColor,
                fontWeight: 500,
                offsetY: -20,
                formatter: function (val) {
                  return parseInt(val) + '%';
                }
              },
              name: {
                offsetY: 30,
                fontFamily: 'Public Sans'
              },
              total: {
                show: true,
                fontSize: '15px',
                fontFamily: 'Public Sans',
                color: legendColor,
                label: 'AVG. Exceptions',
                formatter: function (w) {
                  return '30%';
                }
              }
            }
          }
        }
      },
      responsive: [
        {
          breakpoint: 420,
          options: {
            chart: {
              height: 360
            }
          }
        }
      ]
    };
  if (typeof deliveryExceptionsChartE1 !== undefined && deliveryExceptionsChartE1 !== null) {
    const deliveryExceptionsChart = new ApexCharts(deliveryExceptionsChartE1, deliveryExceptionsChartConfig);
    deliveryExceptionsChart.render();
  }
})();

// DataTable (jquery)
// --------------------------------------------------------------------
$(function () {
  // Variable declaration for table
  var dt_dashboard_table = $('.dt-route-vehicles');

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  // On route vehicles DataTable
  if (dt_dashboard_table.length) {
    var dt_dashboard = dt_dashboard_table.DataTable({
      processing: true, // Aktifkan server-side processing
      serverSide: true, // Pastikan ini diaktifkan untuk mengambil data dari server
      ajax: {
        url: baseUrl + 'project',
        type: 'GET',
        error: function (xhr, status, error) {
          console.error('Error:', error);
          alert('Failed to load data. Please check your server or network connection.');
        }
      },
      columns: [
        { data: 'id' },
        { data: 'id' },
        { data: 'project_id' },
        { data: 'project_name' },
        { data: 'product_id' },
        { data: 'customer' },
        { data: 'location' },
        { data: 'date_delivery' },
        { data: 'actions' }
      ],
      columnDefs: [
        {
          // For Responsive
          className: 'control',
          orderable: false,
          searchable: false,
          responsivePriority: 2,
          targets: 0,
          render: function (data, type, full, meta) {
            return '';
          }
        },
        {
          // For Checkboxes
          targets: 1,
          orderable: false,
          searchable: false,
          checkboxes: true,
          responsivePriority: 3,
          render: function () {
            return '<input type="checkbox" class="dt-checkboxes form-check-input">';
          },
          checkboxes: {
            selectAllRender: '<input type="checkbox" class="form-check-input">',
            selectRow: true
          }
        },
        {
          //Project Id
          targets: 2,
          render: function (data, type, full, meta) {
            var $id = full['project_id'];

            return '<span>' + $id + '</span>';
          }
        },
        {
          // Project Name
          targets: 3,
          render: function (data, type, full, meta) {
            var $name = full['project_name'];

            return '<span>' + $name + '</span>';
          }
        },
        {
          // Icon and location
          targets: 4,
          responsivePriority: 1,
          render: function (data, type, full, meta) {
            var $location = full['location'];
            // Creates full output for row
            var $row_output =
              '<div class="d-flex justify-content-start align-items-center user-name">' +
              '<div class="avatar-wrapper">' +
              '<div class="avatar me-4">' +
              '<span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-car ti-28px"></i></span>' +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column">' +
              $location +
              '</div>' +
              '</div>';
            return $row_output;
          }
        },
        {
          // Product dan Product Detail
          targets: 5,
          responsivePriority: 2,
          render: function (data, type, full, meta) {
            var $name = full['product_name'],
              $category_name = full['category_name'],
              $image = full['image'],
              $size = full['size'],
              $length = full['length'],
              $thickness = full['thickness'],
              $qr_code_path = full['qr_code_path'];

            if ($image) {
              // Untuk menampilkan gambar produk
              var $output =
                '<img src="' +
                assetsPath +
                'img/products/' +
                $image +
                '" alt="Product-' +
                $name +
                '" class="rounded-2">';
            } else {
              // Jika gambar tidak ada, gunakan badge
              var stateNum = Math.floor(Math.random() * 6);
              var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
              var $state = states[stateNum];
              $output = '<span class="avatar-initial rounded-2 bg-label-' + $state + '">' + $name[0] + '</span>';
            }

            // Output lengkap dengan data-qr-code-path
            var $row_output =
              '<div class="d-flex align-items-center product-name" data-qr-code-path="' +
              $qr_code_path +
              '">' +
              '<div class="avatar-wrapper me-3 rounded-2 bg-label-secondary">' +
              '<div class="avatar">' +
              $output +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column justify-content-center">' +
              '<span class="text-heading text-wrap fw-medium">' +
              $name +
              ' ' +
              $category_name +
              '</span>' +
              '<span class="text-truncate mb-0 d-none d-sm-block"><small>' +
              'Size: ' +
              $size +
              ', Length: ' +
              $length +
              ', Thickness: ' +
              $thickness +
              '</small></span>' +
              '</div>' +
              '</div>';
            return $row_output;
          }
        },

        {
          // Customer
          targets: 6,
          render: function (data, type, full, meta) {
            var $customer = full['customer'];

            return '<span>' + $customer + '</span>';
          }
        },
        {
          // Date Order
          targets: 7,
          render: function (data, type, full, meta) {
            var $date_delivery = full['date_delivery'];

            if ($date_delivery) {
              // Konversi tanggal ke format d-m-Y
              var date = new Date($date_delivery);
              var day = String(date.getDate()).padStart(2, '0'); // Tambahkan 0 di depan jika < 10
              var month = String(date.getMonth() + 1).padStart(2, '0'); // Bulan dimulai dari 0
              var year = date.getFullYear();
              var formattedDate = `${day}-${month}-${year}`;
            } else {
              var formattedDate = 'N/A'; // Jika tanggal tidak ada
            }

            return '<span>' + formattedDate + '</span>';
          }
        },
        {
          // Actions
          targets: -1,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            const editButton = `
              <button 
                class="btn btn-sm btn-icon edit-record btn-text-secondary rounded-pill waves-effect" 
                data-id="${full['id']}" 
                data-bs-toggle="modal" 
                data-bs-target="#createApp">
                <i class="ti ti-edit"></i>
              </button>`;

            const deleteButton = `
              <button 
                class="btn btn-sm btn-icon delete-record btn-text-secondary rounded-pill waves-effect" 
                data-id="${full['id']}">
                <i class="ti ti-trash"></i>
              </button>`;

            return `
              <div class="d-flex align-items-center gap-2">
                ${editButton}
                ${deleteButton}
              </div>`;
          }
        }
      ],

      order: [2, 'asc'],
      dom:
        '<"card-header d-flex border-top rounded-0 flex-wrap py-0 flex-column flex-md-row align-items-start"' +
        '<"me-5 ms-n4 pe-5 mb-n6 mb-md-0"f>' +
        '<"d-flex justify-content-start justify-content-md-end align-items-baseline"<"dt-action-buttons d-flex flex-column align-items-start align-items-sm-center justify-content-sm-center pt-0 gap-sm-4 gap-sm-0 flex-sm-row"lB>>' +
        '>t' +
        '<"row"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      lengthMenu: [7, 10, 20, 50, 70, 100],
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search Project',
        info: 'Displaying _START_ to _END_ of _TOTAL_ entries',
        paginate: {
          next: '<i class="ti ti-chevron-right ti-sm"></i>',
          previous: '<i class="ti ti-chevron-left ti-sm"></i>'
        }
      },
      select: {
        style: 'multi',
        className: 'selected'
      },
      buttons: [
        {
          extend: 'collection',
          className: 'btn btn-label-secondary dropdown-toggle me-4 waves-effect waves-light',
          text: '<i class="ti ti-upload me-1 ti-xs"></i>Export',
          buttons: [
            {
              extend: 'print',
              text: '<i class="ti ti-printer me-2" ></i>Print',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7],
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('product-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              },
              customize: function (win) {
                // Customize print view for dark
                $(win.document.body)
                  .css('color', headingColor)
                  .css('border-color', borderColor)
                  .css('background-color', bodyBg);
                $(win.document.body)
                  .find('table')
                  .addClass('compact')
                  .css('color', 'inherit')
                  .css('border-color', 'inherit')
                  .css('background-color', 'inherit');
              }
            },
            {
              extend: 'csv',
              text: '<i class="ti ti-file me-2" ></i>Csv',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7],
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('product-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'excel',
              text: '<i class="ti ti-file-export me-2"></i>Excel',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7],
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('product-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'pdf',
              text: '<i class="ti ti-file-text me-2"></i>Pdf',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7],
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('product-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'copy',
              text: '<i class="ti ti-copy me-2"></i>Copy',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7],
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('product-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'print',
              text: '<i class="ti ti-qrcode me-2"></i>QR Code',
              className: 'dropdown-item buttons-qrcode',
              action: function (e, dt, button, config) {
                // Ambil data baris yang dipilih
                const selectedRows = dt.rows('.selected').data().toArray();

                // Jika tidak ada data yang dipilih
                if (selectedRows.length === 0) {
                  // Tampilkan SweetAlert
                  Swal.fire({
                    title: 'Tidak Ada Produk yang Dipilih',
                    text: 'Silakan pilih minimal satu produk untuk mencetak QR Code.',
                    icon: 'warning',
                    timer: 2000,
                    showConfirmButton: false
                  });

                  // Hentikan eksekusi lebih lanjut
                  return false;
                }

                // Lanjutkan ke proses print jika ada data yang dipilih
                $.fn.dataTable.ext.buttons.print.action.call(this, e, dt, button, config);
              },
              customize: function (win) {
                // Ambil semua baris yang dipilih
                const selectedRows = dt_dashboard.rows('.selected').data().toArray(); // 'selected' adalah class untuk checkbox yang dipilih

                const qrContainer = $('<div class="qr-container"></div>'); // Container utama untuk QR Code cards

                // Proses setiap baris untuk menambahkan QR Code cards
                selectedRows.forEach(item => {
                  const qrCodePath = item.qr_code_path || 'path_to_default_qr.png'; // QR Code Path
                  const projectId = item.project_id || 'N/A';
                  const projectName = item.project_name || 'N/A';
                  const location = item.location || 'N/A';
                  const size = item.size || 'N/A';
                  const length = item.length || 'N/A';
                  const type = item.product_name + ' ' + item.category_name || 'N/A';
                  const customer = item.customer || 'N/A';
                  const deliveryDate = item.date_delivery || 'N/A';

                  const qrCard = `
                    <div class="qr-card">
                      <div class="qr-code-section">
                        <img src="${qrCodePath}" alt="QR Code">
                        <span style="font-size: 8px; font-weight: bold;">Product by</span>
                        <span style="font-size: 8px; font-weight: bold;">PT. Green Cold</span>
                      </div>
                      <div class="qr-info-section">
                        <table class="table-bordered text-center" style="font-size: 8px; width: 100%; height: 100%; font-weight: bold;">
                          <tbody>
                            <tr>
                              <td>${projectId}</td>
                              <td style="border-right:none;">${projectName}</td>
                              <td style="border-left:none;" colspan="2">${location}</td>
                            </tr>
                            <tr>
                              <td>Size:</td>
                              <td style="border-right:none;">${size}</td>
                              <td style="border-left:none;" colspan="2">DO: ${deliveryDate}</td>
                            </tr>
                            <tr>
                              <td>Type:</td>
                              <td colspan="2" style="border-right:none;">${type}</td>
                              <td style="border-left:none;"><img src="/assets/img/logo/light-logo.png" alt="Logo" style="width: 34px; height: 25px;"></td>
                            </tr>
                            <tr>
                              <td>Length:</td>
                              <td>${length}</td>
                              <td colspan="2">Cust: ${customer}</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  `;

                  qrContainer.append(qrCard);
                });

                // Ganti isi body cetakan dengan QR Code cards
                $(win.document.body).html(qrContainer);

                // Tambahkan CSS untuk layout cetak
                $(win.document.head).append(`
                  <style>
                   .qr-container {
  display: grid;
  grid-template-columns: 7cm; /* Kolom dengan lebar 7 cm */
  grid-template-rows: 3cm;   /* Baris dengan tinggi 3 cm */
  gap: 0cm; /* Tidak ada jarak antar elemen */
  justify-content: center;
  align-items: center;
  padding: 0; /* Hilangkan padding */
  margin: 0; /* Hilangkan margin */
  width: 7cm; /* Sesuaikan dengan lebar kertas */
  height: 3cm; /* Sesuaikan dengan tinggi kertas */
}
.qr-card {
  width: 7cm;
  height: 3cm;
  margin: 0;
  border: 1px solid black;
  display: flex;
  overflow: hidden;
  font-family: Arial, sans-serif;
  font-size: 8px;
  border-radius: 8px; /* Hilangkan radius jika tidak diperlukan */
}
.qr-code-section {
  flex: 0.4; /* Lebar QR Code */
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 2px; /* Kurangi padding untuk efisiensi ruang */
  border-right: 1px solid black;
}
.qr-code-section img {
  width: 60px; /* Pastikan QR Code proporsional */
  height: 60px;
  object-fit: contain;
}
.qr-info-section {
  flex: 1.6; /* Perbesar area teks */
  padding: 0px;
}
.qr-info-section table {
  width: 100%;
  height: 100%;
  border-collapse: collapse;
  text-align: left;
}
.qr-info-section td {
  border: 1px solid black;
  padding: 0px;
  font-size: 8px;
}

                  </style>
                `);
              }
            }
          ]
        },
        {
          text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Add Project</span>',
          className: 'btn btn-primary ms-2 ms-sm-0 waves-effect waves-light add-project',
          action: function () {
            // Memicu modal dengan id 'createApp'
            $('#createApp').modal('show');
          }
        }
      ],
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['location'];
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                    col.rowIndex +
                    '" data-dt-column="' +
                    col.columnIndex +
                    '">' +
                    '<td>' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td>' +
                    col.data +
                    '</td>' +
                    '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      }
    });
    $('.dataTables_info').addClass('pt-0');
    // Tangkap event sebelum memproses tombol print
    let formData = {
      projectId: '',
      projectName: '',
      location: '',
      customer: '',
      selectedProduct: null,
      qrCodePath: '',
      size: '',
      length: '',
      type: '',
      deliveryDate: ''
    };

    function populateEditForm(data) {
      // Isi objek formData dengan data dari server
      formData.projectId = data.project_id || '';
      formData.projectName = data.project_name || '';
      formData.location = data.location || '';
      formData.customer = data.customer || '';
      formData.selectedProduct = data.product_id || '';
      formData.deliveryDate = data.date_delivery || '';

      // Isi input form
      const projectIdInput = document.querySelector('input[name="project_id"]');
      if (projectIdInput) projectIdInput.value = formData.projectId;

      const projectNameInput = document.querySelector('input[name="project_name"]');
      if (projectNameInput) projectNameInput.value = formData.projectName;

      const locationInput = document.querySelector('input[name="location"]');
      if (locationInput) locationInput.value = formData.location;

      const customerInput = document.querySelector('input[name="customer"]');
      if (customerInput) customerInput.value = formData.customer;

      // Pilih produk yang sesuai di radio button
      const productRadio = document.querySelector(`input[name="product-radio"][value="${formData.selectedProduct}"]`);
      if (productRadio) {
        productRadio.checked = true;
        formData.qrCodePath = productRadio.dataset.qrCodePath || '';
      }

      // Tampilkan QR Code
      const qrCodeImg = document.getElementById('qr-code');
      if (qrCodeImg) {
        qrCodeImg.src = formData.qrCodePath || '';
      }

      // Update atau inisialisasi Flatpickr
      const flatpickrInput = document.querySelector('.inline-calendar');
      if (flatpickrInput) {
        // Cek apakah Flatpickr sudah diinisialisasi
        if (flatpickrInput._flatpickr) {
          // Jika sudah diinisialisasi, hanya perbarui tanggal
          flatpickrInput._flatpickr.setDate(formData.deliveryDate, true);
        } else {
          let date = new Date(data.date_delivery);
          flatpickr(".inline-calendar", {
            inline: true,
            defaultDate: date.toISOString().split('T')[0],
            dateFormat: 'Y-m-d'
          });
        }
      }
    }

    $(document).on('click', '.edit-record', function () {
      const projectId = $(this).data('id');

      // Ambil data project dari server
      fetch(`/project/${projectId}/edit`)
        .then(response => {
          if (!response.ok) {
            throw new Error('Failed to fetch project data');
          }
          return response.json();
        })
        .then(data => {
          if (data.success) {
            // Isi form dengan data project
            populateEditForm(data.data);

            // Ubah teks modal
            const modalHeader = document.querySelector('#createApp .modal-body h4');
            const modalSubText = document.querySelector('#createApp .modal-body p');
            if (modalHeader) modalHeader.innerText = 'Edit Project';
            if (modalSubText) modalSubText.innerText = 'Edit data for this project';

            // Set ID proyek untuk mode Edit
            const submitButton = document.getElementById('btn-submit-project');
            if (submitButton) {
              submitButton.dataset.id = projectId;
            }

            // Tampilkan modal
            $('#createApp').modal('show');
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Failed to load project data.',
              timer: 2000,
              showConfirmButton: false
            });
          }
        })
        .catch(error => {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message,
            timer: 2000,
            showConfirmButton: false
          });
        });
    });

    $(document).on('click', '.delete-record', function () {
      const projectId = $(this).data('id');

      // Konfirmasi menggunakan SweetAlert
      Swal.fire({
        title: 'Are you sure?',
        text: 'This action cannot be undone!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        customClass: {
          confirmButton: 'btn btn-primary me-3',
          cancelButton: 'btn btn-label-secondary'
        }
      }).then(result => {
        if (result.isConfirmed) {
          deleteProject(projectId); // Panggil fungsi delete jika user mengonfirmasi
        }
      });
    });
    $(document).on('click', '.add-project', function () {
      // Reset semua input di dalam modal
      const submitButton = document.getElementById('btn-submit-project');
      submitButton.dataset.mode = 'create'; // Set mode ke create
      submitButton.dataset.id = ''; // Kosongkan ID untuk mode create

      // Reset semua input text
      const inputs = $('#createApp input[type="text"], #createApp input[type="hidden"]');
      inputs.each(function () {
        $(this).val(''); // Reset nilai input
      });

      // Reset semua radio button
      $('#createApp input[name="product-radio"]').prop('checked', false); // Pastikan tidak ada radio yang terpilih

      // Reset Flatpickr (kalender)
      const flatpickrInstance = document.querySelector('.inline-calendar')?._flatpickr;
      if (flatpickrInstance) {
        flatpickrInstance.clear(); // Hapus nilai flatpickr
      }

      // Reset QR Code jika ada
      const qrCodeImg = document.getElementById('qr-code');
      if (qrCodeImg) {
        qrCodeImg.src = ''; // Kosongkan gambar QR Code
      }

      // Reset teks modal
      $('#createApp .modal-body h4').text('Create Project');
      $('#createApp .modal-body p').text('Isi data untuk membuat project baru');

      // Pastikan dropdown atau area produk tetap aktif dan interaktif
      const productList = document.querySelectorAll('input[name="product-radio"]');
      productList.forEach(product => {
        product.disabled = false; // Pastikan semua radio button aktif
      });

      // Tampilkan modal
      $('#createApp').modal('show');
    });

    function deleteProject(projectId) {
      fetch(`/project/${projectId}/delete`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Content-Type': 'application/json'
        }
      })
        .then(response => {
          if (!response.ok) {
            throw new Error('Failed to delete project');
          }
          return response.json();
        })
        .then(data => {
          if (data.success) {
            Swal.fire({
              icon: 'success',
              title: 'Deleted!',
              text: data.message,
              timer: 2000,
              showConfirmButton: false
            }).then(() => {
              location.reload(); // Reload halaman setelah berhasil
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: data.message,
              timer: 2000,
              showConfirmButton: false
            });
          }
        })
        .catch(error => {
          Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Something went wrong. Please try again!',
            timer: 2000,
            showConfirmButton: false
          });
        });
    }
  }
});