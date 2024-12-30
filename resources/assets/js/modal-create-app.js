'use strict';

document.addEventListener('DOMContentLoaded', function () {
  const appModal = document.getElementById('createApp');

  // Object to store form data
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

  // Initialize Flatpickr for inline calendar
  function initFlatpickr() {
    const inlineCalendar = document.querySelector('.inline-calendar');
    if (inlineCalendar) {
      flatpickr(inlineCalendar, {
        inline: true,
        defaultDate: new Date(),
        monthSelectorType: 'static',
        dateFormat: 'Y-m-d',
        onChange: function (selectedDates, dateStr) {
          formData.deliveryDate = dateStr; // Save delivery date to formData
        }
      });
    }
  }

  // Initialize Stepper and Flatpickr when modal is shown
  appModal.addEventListener('shown.bs.modal', function () {
    const wizardCreateApp = document.querySelector('#wizard-create-app');

    if (wizardCreateApp) {
      const wizardCreateAppNextList = [].slice.call(wizardCreateApp.querySelectorAll('.btn-next'));
      const wizardCreateAppPrevList = [].slice.call(wizardCreateApp.querySelectorAll('.btn-prev'));
      const wizardCreateAppBtnSubmit = document.querySelector('#btn-submit-project');

      const createAppStepper = new Stepper(wizardCreateApp, {
        linear: false
      });

      // Event listener for "Next" buttons
      wizardCreateAppNextList.forEach(wizardCreateAppNext => {
        wizardCreateAppNext.addEventListener('click', () => {
          const currentStep = createAppStepper._currentIndex;

          if (currentStep === 0) {
            if (!validateDetailsStep()) return; // Cegah langkah berikutnya jika tidak valid
            saveDetailsStep();
          }

          if (currentStep === 1) {
            if (!validateProductsStep()) return; // Cegah langkah berikutnya jika tidak valid
            saveProductsStep();
          }

          if (currentStep === 2) {
            saveDeliveryDateStep(); // Save step 3 data
            populateSubmitStep(); // Immediately update Submit Step
          }

          createAppStepper.next();
        });
      });

      // Event listener for "Previous" buttons
      wizardCreateAppPrevList.forEach(wizardCreateAppPrev => {
        wizardCreateAppPrev.addEventListener('click', () => {
          createAppStepper.previous();
        });
      });

      // Event listener for the Submit button
      if (wizardCreateAppBtnSubmit) {
        // Hapus semua listener submit sebelumnya untuk menghindari duplikasi
        wizardCreateAppBtnSubmit.replaceWith(wizardCreateAppBtnSubmit.cloneNode(true));
        const newSubmitButton = document.querySelector('#btn-submit-project');

        // Tambahkan listener ke tombol baru
        newSubmitButton.addEventListener('click', () => {
          submitProject(); // Submit project
        });
      }
    }

    // Initialize Flatpickr for the inline calendar
    initFlatpickr();
  });

  function attachProductListeners() {
    const productRadios = document.querySelectorAll('input[name="product-radio"]');
    if (!productRadios.length) {
      console.warn("No product radio buttons found.");
      return;
    }
    
    productRadios.forEach(radio => {
      radio.removeEventListener('change', handleProductSelection); // Hapus listener lama untuk mencegah duplikasi
      radio.addEventListener('change', handleProductSelection);
    });
  }
  
  function handleProductSelection(event) {
    const selectedProduct = event.target;
    formData.selectedProduct = selectedProduct.value; // Capture product ID
    formData.qrCodePath = selectedProduct.dataset.qrCodePath || ''; // Capture QR code path
    console.log('Selected Product:', formData.selectedProduct);
    console.log('QR Code Path:', formData.qrCodePath);
  }

  function validateDetailsStep() {
    const projectId = document.querySelector('input[name="project_id"]').value.trim();
    const projectName = document.querySelector('input[name="project_name"]').value.trim();
    const location = document.querySelector('input[name="location"]').value.trim();
    const customer = document.querySelector('input[name="customer"]').value.trim();

    if (!projectId || !projectName || !location || !customer) {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Please fill all fields in the Details step, including Project ID!',
        timer: 2000,
        showConfirmButton: false
      });
      return false;
    }

    return true;
  }

  function validateProductsStep() {
    // Periksa apakah ada produk yang dipilih
    const selectedProduct = document.querySelector('input[name="product-radio"]:checked');
  
    // Jika tidak ada produk yang dipilih
    if (!selectedProduct) {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Please select a product before proceeding!',
        timer: 2000,
        showConfirmButton: false
      });
      return false;
    }
  
    // Simpan data produk yang dipilih ke `formData`
    formData.selectedProduct = selectedProduct.value;
    formData.qrCodePath = selectedProduct.dataset.qrCodePath || '';
    return true;
  }
  
  // Save data from "Details" step
  function saveDetailsStep() {
    formData.projectId = document.querySelector('input[name="project_id"]').value || '';
    formData.projectName = document.querySelector('input[name="project_name"]').value || '';
    formData.location = document.querySelector('input[name="location"]').value || '';
    formData.customer = document.querySelector('input[name="customer"]').value || '';
  }

  // Save data from "Products" step
  function saveProductsStep() {
    const selectedProduct = document.querySelector('input[name="product-radio"]:checked');

    if (selectedProduct) {
      const productElement = selectedProduct.closest('li');
      formData.selectedProduct = selectedProduct.value;
      formData.qrCodePath = selectedProduct.dataset.qrCodePath || '';
      formData.type = productElement.querySelector('h6')?.textContent.trim() || '';

      // Ambil varian produk (Size, Length, dll.)
      const productVariants = productElement.querySelectorAll('small div');
      productVariants.forEach(variant => {
        const text = variant.textContent.trim().toLowerCase();
        if (text.startsWith('size:')) formData.size = text.split(':')[1].trim();
        if (text.startsWith('length:')) formData.length = text.split(':')[1].trim();
      });
    }
  }

  // Save data from "Delivery Date" step
  function saveDeliveryDateStep() {
    if (!formData.deliveryDate) {
      formData.deliveryDate = new Date().toISOString().split('T')[0]; // Fallback to today's date
    }
  }

  // Populate "Submit" step
  // Populate "Submit" step
  function populateSubmitStep() {
    // Format tanggal untuk Delivery Date
    const deliveryDateElement = document.getElementById('review-delivery-date');
    if (formData.deliveryDate) {
      const date = new Date(formData.deliveryDate);
      const formattedDate = `${String(date.getDate()).padStart(2, '0')}-${String(date.getMonth() + 1).padStart(2, '0')}-${date.getFullYear()}`;
      deliveryDateElement.textContent = formattedDate;
    } else {
      deliveryDateElement.textContent = 'N/A';
    }

    // Update informasi proyek
    document.getElementById('review-project-id').textContent = formData.projectId || 'N/A';
    document.getElementById('review-project-name').textContent = formData.projectName || 'N/A';
    document.getElementById('review-location').textContent = formData.location || 'N/A';
    document.getElementById('review-customer').textContent = formData.customer || 'N/A';
    document.getElementById('review-size').textContent = formData.size || 'N/A';
    document.getElementById('review-length').textContent = formData.length || 'N/A';
    document.getElementById('review-type').textContent = formData.type || 'N/A';

    // Update QR Code
    const qrCodeImg = document.getElementById('qr-code');
    if (qrCodeImg && formData.qrCodePath) {
      qrCodeImg.src = formData.qrCodePath;
    }
  }

  let isSubmitting = false;
  // Submit project to server
  function submitProject() {
    if (isSubmitting) return; // Mencegah double submit
    isSubmitting = true;

    const projectId = document.getElementById('btn-submit-project').dataset.id;
    const isEditMode = !!projectId; // Determine if edit mode
    const url = isEditMode ? `/project/${projectId}/update` : '/project/store';
    const method = isEditMode ? 'PUT' : 'POST';

    const payload = {
      project_id: formData.projectId,
      project_name: formData.projectName,
      product_id: formData.selectedProduct,
      customer: formData.customer,
      location: formData.location,
      date_delivery: formData.deliveryDate
    };

    console.log('Payload:', payload);
    // Validasi data sebelum dikirim
    if (!formData.projectId || !formData.projectName || !formData.selectedProduct) {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Please fill all required fields!',
        timer: 2000,
        showConfirmButton: false
      });
      isSubmitting = false; // Reset flag
      return;
    }
    // Kirim data ke server
    fetch(url, {
      method: method,
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(payload)
    })
      .then(response => {
        if (!response.ok) {
          throw new Error('Server error');
        }
        return response.json();
      })
      .then(data => {
        if (data.success) {
          Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: data.message,
            timer: 2000,
            showConfirmButton: false
          }).then(() => {
            location.reload(); // Reload halaman setelah sukses
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
      })
      .finally(() => {
        isSubmitting = false; // Reset flag
      });
  }

  $(document).on('click', '#addProjectButton', function () {
    resetModal(); // Kosongkan form
    document.getElementById('btn-submit-project').dataset.id = ''; // Reset ID
    $('#createApp').modal('show');
  });

  // Reset modal form
  function resetModal() {
    document.querySelector('form').reset(); // Reset semua input form

    formData = {
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

    // Reset atribut tombol submit
    const submitButton = document.getElementById('btn-submit-project');
    submitButton.dataset.id = ''; // Kosongkan ID
    submitButton.dataset.mode = 'create'; // Set mode ke create
    // Clear all inputs
    const inputs = document.querySelectorAll('#createApp input');
    inputs.forEach(input => {
      if (input) {
        input.value = '';
      }
    });

    const productRadios = document.querySelectorAll('input[name="product-radio"]');

    productRadios.forEach(radio => {
      radio.addEventListener('change', function () {
        formData.selectedProduct = this.value; // Simpan ID produk ke formData
        formData.qrCodePath = this.dataset.qrCodePath || ''; // Ambil QR code path
        console.log('Selected Product ID:', formData.selectedProduct);
        console.log('QR Code Path:', formData.qrCodePath);
      });
    });
    

    // Reset calendar input
    const calendarInput = document.querySelector('.inline-calendar input');
    if (calendarInput) {
      calendarInput.value = '';
    }

    // Reset QR Code Image
    const qrCodeImg = document.getElementById('qr-code');
    if (qrCodeImg) {
      qrCodeImg.src = '';
    }
    attachProductListeners();
  }
  // Modal event handlers
  appModal.addEventListener('shown.bs.modal', function () {
    initFlatpickr();
    attachProductListeners();
  });

  document.querySelector('#btn-submit-project').addEventListener('click', submitProject);

});
