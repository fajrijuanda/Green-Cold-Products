/**
 * App eCommerce Category List
 */

'use strict';

import Swal from 'sweetalert2';
import toastr from 'toastr';
import $ from 'jquery';
import Quill from 'quill';

// Datatable (jquery)

$(function () {
  let borderColor, bodyBg, headingColor;
 
  if (isDarkStyle) {
    borderColor = config.colors_dark.borderColor;
    bodyBg = config.colors_dark.bodyBg;
    headingColor = config.colors_dark.headingColor;
  } else {
    borderColor = config.colors.borderColor;
    bodyBg = config.colors.bodyBg;
    headingColor = config.colors.headingColor;
  }

  // Variable declaration for category list table
  var dt_category_list_table = $('.datatables-category-list');

  //select2 for dropdowns in offcanvas
  var select2 = $('.select2');
  if (select2.length) {
    select2.each(function () {
      var $this = $(this);
      $this.wrap('<div class="position-relative"></div>').select2({
        dropdownParent: $this.parent(),
        placeholder: $this.data('placeholder') //for dynamic placeholder
      });
    });
  }

  // Customers List Datatable

  if (dt_category_list_table.length) {
    var dt_category = dt_category_list_table.DataTable({
      processing: true, // Aktifkan server-side processing
      serverSide: true, // Pastikan ini diaktifkan untuk mengambil data dari server
      responsive: true,
      ajax: {
        url: baseUrl + 'category',
        type: 'GET',
        error: function (xhr, status, error) {
          console.error('Error:', error);
          alert('Failed to load data. Please check your server or network connection.');
        }
      },
      columns: [
        // columns according to JSON
        { data: 'id' }, // Kolom untuk kontrol
        { data: 'id', name: 'id' }, // Kolom ID
        { data: 'name', name: 'name' }, // Kolom Nama
        { data: 'status', name: 'status' }, // Kolom Status
        { data: 'action', name: 'action', orderable: false, searchable: false } // Kolom Aksi
      ],
      columnDefs: [
        {
          // For Responsive
          className: 'control',
          searchable: false,
          orderable: false,
          responsivePriority: 1,
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
          responsivePriority: 4,
          checkboxes: true,
          render: function () {
            return '<input type="checkbox" class="dt-checkboxes form-check-input">';
          },
          checkboxes: {
            selectRow: true,
            selectAllRender: '<input type="checkbox" class="form-check-input">'
          }
        },
        {
          // Categories and Category Detail
          targets: 2,
          responsivePriority: 2,
          render: function (data, type, full, meta) {
            var $name = full['name'],
              $category_detail = full['category_detail'],
              $image = full['cat_image'],
              $id = full['id'];
            if ($image) {
              // For Product image
              var $output =
                '<img src="' +
                assetsPath +
                'img/categories/' +
                $image +
                '" alt="Product-' +
                $id +
                '" class="rounded-2">';
            } else {
              // For Product badge
              var stateNum = Math.floor(Math.random() * 6);
              var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
              var $state = states[stateNum],
                $name = full['category_detail'],
                $initials = $name.match(/\b\w/g) || [];
              $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
              $output = '<span class="avatar-initial rounded-2 bg-label-' + $state + '">' + $initials + '</span>';
            }
            // Creates full output for Categories and Category Detail
            var $row_output =
              '<div class="d-flex align-items-center">' +
              '<div class="avatar-wrapper me-3 rounded-2 bg-label-secondary">' +
              '<div class="avatar">' +
              $output +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column justify-content-center">' +
              '<span class="text-heading text-wrap fw-medium">' +
              $name +
              '</span>' +
              '<span class="text-truncate mb-0 d-none d-sm-block"><small>' +
              $category_detail +
              '</small></span>' +
              '</div>' +
              '</div>';
            return $row_output;
          }
        },
        {
          // Status
          targets: 3,
          orderable: false,
          render: function (data, type, full, meta) {
            var badgeClass;
            switch (data) {
              case 'Published':
                badgeClass = 'bg-label-success';
                break;
              case 'Scheduled':
                badgeClass = 'bg-label-warning';
                break;
              case 'Inactive':
                badgeClass = 'bg-label-danger';
                break;
              default:
                badgeClass = 'bg-label-secondary';
                break;
            }
            return (
              '<div class="d-flex justify-content-startr align-items-start">' +
              '<span class="badge ' +
              badgeClass +
              ' text-capitalize">' +
              data +
              '</span>' +
              '</div>'
            );
          }
        },
        {
          // Actions
          targets: -1,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            return (
              '<div class="d-flex align-items-sm-center justify-content-sm-center">' +
              `<button class="btn btn-sm btn-icon edit-record btn-text-secondary rounded-pill waves-effect" data-id="${full['id']}" data-bs-toggle="offcanvas" data-bs-target="#offcanvasEcommerceCategoryList"><i class="ti ti-edit"></i></button>` +
              `<button class="btn btn-sm btn-icon delete-record btn-text-secondary rounded-pill waves-effect" data-id="${full['id']}"><i class="ti ti-trash"></i></button>` +
              '</div>'
            );
          }
        }
      ],
      order: [2, 'asc'], //set any columns order asc/desc
      dom:
        '<"card-header d-flex flex-wrap py-0 flex-column flex-sm-row"' +
        '<f>' +
        '<"d-flex justify-content-center justify-content-md-end align-items-baseline"<"dt-action-buttons d-flex justify-content-center flex-md-row align-items-baseline"lB>>' +
        '>t' +
        '<"row mx-1"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      lengthMenu: [7, 10, 20, 50, 70, 100], //for length of menu
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search Category',
        paginate: {
          next: '<i class="ti ti-chevron-right ti-sm"></i>',
          previous: '<i class="ti ti-chevron-left ti-sm"></i>'
        }
      },
      select: {
        style: 'multi',
        className: 'selected'
      },
      // Button for offcanvas
      buttons: [
        {
          text: '<i class="ti ti-plus ti-xs me-0 me-sm-2"></i><span class="d-none d-sm-inline-block">Add Category</span>',
          className: 'add-new btn btn-primary ms-2 waves-effect waves-light',
          attr: {
            'data-bs-toggle': 'offcanvas',
            'data-bs-target': '#offcanvasEcommerceCategoryList'
          }
        }
      ],
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['name'];
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
                    '<td> ' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td class="ps-0">' +
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
    $('.dt-action-buttons').addClass('pt-0');
    $('.dataTables_filter').addClass('me-3 mb-sm-6 mb-0 ps-0');
  }

 
  // Delete record
  $(document).on('click', '.delete-record', function () {
    var category_id = $(this).data('id'),
      dtrModal = $('.dtr-bs-modal.show');

    if (dtrModal.length) {
      dtrModal.modal('hide');
    }

    Swal.fire({
      title: 'Are you sure?',
      text: 'You will not be able to recover this category!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      customClass: {
        confirmButton: 'btn btn-primary me-3',
        cancelButton: 'btn btn-label-secondary'
      },
      buttonsStyling: false
    }).then(function (result) {
      if (result.value) {
        // delete the data
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          type: 'DELETE',
          url: `${baseUrl}category-list/${category_id}`,
          success: function () {
            dt_category.draw();
          },
          error: function (error) {
            console.log(error);
          }
        });

        // success sweetalert
        Swal.fire({
          icon: 'success',
          title: 'Deleted!',
          text: 'The category has been deleted!',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        // cancel sweetalert
        Swal.fire({
          icon: 'error',
          title: 'Cancelled',
          text: 'Your category is safe!',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      }
    });
  });

  // Edit record
  $(document).on('click', '.edit-record', function () {
    var categoryId = $(this).data('id'),
      dtrModal = $('.dtr-bs-modal.show');

    if (dtrModal.length) {
      dtrModal.modal('hide');
    }

    $('#offcanvasEcommerceCategoryListLabel').html('Edit Category');
    $('#form-method').val('PUT');
    // Isi ID kategori ke input hidden
    $('#category_id').val(categoryId);

    $.get(`${baseUrl}category-list\/${categoryId}\/edit`, function (data) {
      $('#category_id').val(data.id);
      $('#ecommerce-category-title').val(data.name);
      quill.root.innerHTML = data.description || '';
      $('.select2').val(null).trigger('change'); // Reset Select2
      $('#ecommerce-category-status').val(data.status).trigger('change');

      // Tampilkan nama file saat ini (jika ada)
      if (data.image) {
        $('#current-image-name').text(`Current file: ${data.image}`);
      } else {
        $('#current-image-name').text('No file uploaded.');
      }
    });
  });

  $('.add-new').on('click', function () {
    $('#eCommerceCategoryListForm')[0].reset();
    $('#category_id').val('');
    $('#offcanvasEcommerceCategoryListLabel').html('Add Category');
  });
  // Save edited record
  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_filter .form-control').addClass('ms-0');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
    $('.dataTables_length .form-select').addClass('ms-0');
  }, 300);

  //For form validation

  const eCommerceCategoryListForm = document.getElementById('eCommerceCategoryListForm');

  const commentEditor = document.querySelector('.comment-editor');
  let quill;
  if (commentEditor) {
    quill = new Quill(commentEditor, {
      modules: {
        toolbar: '.comment-toolbar'
      },
      placeholder: 'Write a Description...',
      theme: 'snow'
    });

    // Sinkronkan konten editor ke input hidden
    quill.on('text-change', function () {
      const description = quill.root.innerHTML.trim(); // Ambil konten Quill Editor
      const hiddenInput = document.querySelector('#description-input');
      if (hiddenInput) {
        hiddenInput.value = description !== '<p><br></p>' ? description : ''; // Set nilai ke input hidden
      }
    });
  }

  // Add New Customer Form Validation
  const fv = FormValidation.formValidation(eCommerceCategoryListForm, {
    fields: {
      categoryTitle: {
        validators: {
          notEmpty: {
            message: 'Please enter category title'
          }
        }
      },
      status: {
        validators: {
          notEmpty: {
            message: 'Please select a status'
          }
        }
      }
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        eleValidClass: '',
        rowSelector: function (field, ele) {
          return '.mb-6';
        }
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  }).on('core.form.valid', function () {
    // Ambil data form
    const formData = new FormData(eCommerceCategoryListForm);
    const categoryId = $('#category_id').val(); // Ambil ID kategori
    const method = $('#form-method').val(); // Ambil nilai method dari input hidden
    const url = categoryId ? `${baseUrl}category-list/${categoryId}` : `${baseUrl}category-list`;
  
    
    // Tambahkan `_method` untuk update
    if (method === 'PUT') {
      formData.append('_method', 'PUT');
    }

    // ajax setup
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    // Kirim permintaan AJAX
    $.ajax({
      url: url,      type: 'POST',
      data: formData,
      processData: false, // Jangan memproses data agar file upload berfungsi
      contentType: false, // Jangan mengatur Content-Type secara manual
      success: function (response) {
        dt_category.draw();
        const offcanvasElement = document.getElementById('offcanvasEcommerceCategoryList');
        const offcanvasInstance = bootstrap.Offcanvas.getInstance(offcanvasElement);
        if (offcanvasInstance) {
          offcanvasInstance.hide();
        }

        // SweetAlert
        Swal.fire({
          icon: 'success',
          title: categoryId ? 'Successfully Updated!' : 'Successfully Created!',
          text: response.message || 'Operation completed successfully.',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });

        // Reset form dan editor
        eCommerceCategoryListForm.reset();
        if (quill) {
          quill.root.innerHTML = ''; // Reset Quill Editor
        }
        $('#category-id').val('');
        $('.select2').val(null).trigger('change'); // Reset Select2
      },
      error: function (xhr) {
        console.error('Error:', xhr.responseJSON); // Debugging
        const offcanvasElement = document.getElementById('offcanvasEcommerceCategoryList');
        const offcanvasInstance = bootstrap.Offcanvas.getInstance(offcanvasElement);
        if (offcanvasInstance) {
          offcanvasInstance.hide();
        }
        Swal.fire({
          title: 'Error Occurred!',
          text: xhr.responseJSON?.message || 'An error occurred while processing your request.',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-danger'
          }
        });
      }
    });
  });

  // Reset form saat offcanvas ditutup
  $('#offcanvasEcommerceCategoryList').on('hidden.bs.offcanvas', function () {
    fv.resetForm(true);
    if (quill) {
      quill.root.innerHTML = ''; // Reset Quill Editor
    }
    $(this).removeAttr('aria-hidden'); // Hapus atribut `aria-hidden`
    $('#form-method').val('POST'); // Reset form method to POST
    $('.select2').val(null).trigger('change'); // Reset Select2
    $('#eCommerceCategoryListForm')[0].reset(); // Reset form
    $('#category_id').val(''); // Reset category id
    $('#offcanvasEcommerceCategoryListLabel').html('Add Category'); // Reset label form
  });
});
