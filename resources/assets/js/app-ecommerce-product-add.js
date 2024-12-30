/**
 * App eCommerce Add Product Script
 */
'use strict';

import { file } from 'jszip';
import { baseApiUrl } from 'mapbox-gl';

(function () {
  // Initialize Quill editor for product description
  const commentEditor = document.querySelector('.comment-editor');
  if (commentEditor) {
    const quill = new Quill(commentEditor, {
      modules: {
        toolbar: '.comment-toolbar'
      },
      placeholder: 'Product Description',
      theme: 'snow'
    });

    // Sync Quill content with hidden input
    quill.on('text-change', function () {
      const description = quill.root.innerHTML.trim();
      const descriptionInput = document.querySelector('#description-input');
      if (descriptionInput) {
        descriptionInput.value = description !== '<p><br></p>' ? description : '';
      }
    });
  }

  const previewTemplate = `<div class="dz-preview dz-file-preview">
<div class="dz-details">
  <div class="dz-thumbnail">
    <img data-dz-thumbnail>
    <span class="dz-nopreview">No preview</span>
    <div class="dz-success-mark"></div>
    <div class="dz-error-mark"></div>
    <div class="dz-error-message"><span data-dz-errormessage></span></div>
    <div class="progress">
      <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
    </div>
  </div>
  <div class="dz-filename" data-dz-name></div>
  <div class="dz-size" data-dz-size></div>
</div>
</div>`;

  // Initialize Dropzone for product image upload
  // Basic Dropzone

  let uploadedFile = null; // Declare the variable

  const myDropzone = new Dropzone('#dropzone-basic', {
    previewTemplate: previewTemplate,
    maxFilesize: 5, // Max file size in MB
    acceptedFiles: '.jpg,.jpeg,.png',
    maxFiles: 1,
    addRemoveLinks: true,
    dictDefaultMessage: 'Drag & drop an image or click to browse',
    init: function () {
      this.on('addedfile', function (file) {
        uploadedFile = file; // Save file reference
        console.log('Image ready for upload:', file.name);
      });

      this.on('removedfile', function () {
        uploadedFile = null; // Clear file reference
        console.log('Image removed');
      });
    }
  });

  document.addEventListener('DOMContentLoaded', function () {
    const dropzoneElement = document.querySelector('#dropzone-basic');
    const hiddenImageInput = document.querySelector('#hidden-image-name');

    if (dropzoneElement && hiddenImageInput) {
      const existingImage = hiddenImageInput.value; // Nama file gambar sebelumnya
      const dropzone = Dropzone.forElement(dropzoneElement);

      if (existingImage) {
        // URL gambar lama
        const imageUrl = `/assets/img/products/${existingImage}`;

        // Simulasi file pratinjau
        const mockFile = { name: existingImage, size: 12345, accepted: true };
        dropzone.emit('addedfile', mockFile);
        dropzone.emit('thumbnail', mockFile, imageUrl);
        dropzone.emit('complete', mockFile);
        dropzone.files.push(mockFile);
      }
      return () => uploadedFile || hiddenImageInput?.value || null;
    }
  });

  // Select2 Initialization
  var select2 = $('.select2');
  if (select2.length) {
    select2.each(function () {
      var $this = $(this);
      $this.wrap('<div class="position-relative"></div>').select2({
        dropdownParent: $this.parent(),
        placeholder: $this.data('placeholder') // for dynamic placeholder
      });
    });
  }
  document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi Quill
    const quillEditor = new Quill('#ecommerce-category-description', {
      modules: {
        toolbar: '.comment-toolbar'
      },
      placeholder: 'Product Description',
      theme: 'snow'
    });

    // Ambil nilai dari input hidden
    const descriptionInput = document.querySelector('#description-input');
    if (descriptionInput && descriptionInput.value) {
      quillEditor.root.innerHTML = descriptionInput.value; // Set content ke editor
    }

    // Sinkronkan perubahan di Quill ke input hidden
    quillEditor.on('text-change', function () {
      const description = quillEditor.root.innerHTML.trim();
      descriptionInput.value = description !== '<p><br></p>' ? description : '';
    });
  });

  // Handle Publish Product button click
  
    const publishButton = document.querySelector('.publish')
    if (publishButton) {
      publishButton.addEventListener('click', function (e) {
        e.preventDefault();

        // Collect form data
        const productName = document.querySelector('#ecommerce-product-name').value;
        const category = document.querySelector('#category-org').value;
        const project = document.querySelector('#project').value;
        const status = document.querySelector('#status-org').value;
        const description = document.querySelector('#description-input').value;
        const image = uploadedFile || document.querySelector('#hidden-image-name')?.value;
        const size = document.querySelector('#size').value;
        const length = document.querySelector('#length').value;
        const thickness = document.querySelector('#thickness').value;

        if (!uploadedFile) {
          Swal.fire({
            icon: 'warning',
            title: 'No Image',
            text: 'Please upload a product image!',
            timer: 3000,
            showConfirmButton: false
          });
          return;
        }

        if (!image) {
          Swal.fire({
            icon: 'warning',
            title: 'No Image',
            text: 'Please upload a product image or ensure the previous image is available!',
            timer: 3000,
            showConfirmButton: false,
          });
          return;
        }

        // Validate required fields
        if (!productName || !category || !status || !project) {
          Swal.fire({
            icon: 'warning',
            title: 'Incomplete Fields',
            text: 'Please fill in all required fields!',
            timer: 3000,
            showConfirmButton: false
          });
          return;
        }
        const isEditMode = window.location.pathname.includes('/edit');

        // SweetAlert2 Confirmation
        Swal.fire({
          title: isEditMode ? 'Updating Product...' : 'Publishing Product...',
          text: 'Please wait while we save your product.',
          icon: 'info',
          timer: 3000,
          showConfirmButton: false,
          didClose: () => {
            // Prepare FormData
            const formData = new FormData();
            formData.append('product_name', productName);
            formData.append('category_id', category);
            formData.append('description', description);
            formData.append('project', project);
            formData.append('status', status);

            if (image instanceof File) {
              formData.append('image', image);
            } else {
              formData.append('existing_image', image);
            }
      
            formData.append('size', size);
            formData.append('length', length);
            formData.append('thickness', thickness);

            // Add _method if editing
            // if (isEditMode) {
            //   formData.append('_method', 'PUT');
            // }

            // Set URL based on mode
            const slug = window.location.pathname.split('/').slice(-2, -1)[0];
            const url = isEditMode ? `/product/${slug}/update` : '/product/store';

            // Send AJAX request
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });

            $.ajax({
              url: url,
              type: 'POST', // Always POST, Laravel will interpret `_method`
              data: formData,
              // enctype:'multipart/form-data',
              processData: false, // Prevent jQuery from processing the FormData
              contentType: false, // Let the browser set the Content-Type
              success: data => {
                if (data.success) {
                  Swal.fire({
                    icon: 'success',
                    title: isEditMode ? 'Product Updated' : 'Product Published!',
                    text: data.message,
                    timer: 3000,
                    showConfirmButton: false
                  }).then(() => {
                    // Redirect to product list
                    window.location.href = data.redirect_url;
                  });
                }
              },
              error: (jqXHR, textStatus, errorThrown) => {
                console.error('Error:', textStatus, errorThrown);
                Swal.fire({
                  icon: 'error',
                  title: isEditMode ? 'Failed to Update' : 'Failed to Publish',
                  text: 'An error occurred. Please try again.',
                  timer: 3000,
                  showConfirmButton: false
                });
              }
            });
          }
        });
      });
    }

})();
