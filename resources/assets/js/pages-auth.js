'use strict';

document.addEventListener('DOMContentLoaded', function () {
  // Form handling for different authentication pages
  const formAuthentication = document.querySelector('#formAuthentication');

  if (formAuthentication) {
    const formType = formAuthentication.dataset.type; // Identify the page type dynamically

    // Common form validation setup
    const fv = FormValidation.formValidation(formAuthentication, {
      fields: {
        email: {
          validators: {
            notEmpty: {
              message: 'Please enter your email'
            },
            emailAddress: {
              message: 'Please enter a valid email address'
            }
          }
        },
        username: {
          validators: {
            notEmpty: {
              message: 'Please enter username'
            },
            stringLength: {
              min: 6,
              message: 'Username must be more than 6 characters'
            }
          }
        },
        password: {
          validators: {
            notEmpty: {
              message: 'Please enter your password'
            },
            stringLength: {
              min: 8,
              message: 'Password must be more than 8 characters'
            }
          }
        },
        'confirm-password': {
          validators: {
            notEmpty: {
              message: 'Please confirm your password'
            },
            identical: {
              compare: function () {
                return formAuthentication.querySelector('[name="password"]').value;
              },
              message: 'The password and its confirmation do not match'
            },
            stringLength: {
              min: 8,
              message: 'Password must be more than 8 characters'
            }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          eleValidClass: '',
          rowSelector: '.mb-6'
        }),
        submitButton: new FormValidation.plugins.SubmitButton(),
        defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
        autoFocus: new FormValidation.plugins.AutoFocus()
      },
      init: instance => {
        instance.on('plugins.message.placed', function (e) {
          if (e.element.parentElement.classList.contains('input-group')) {
            e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
          }
        });
      }
    });

    // Additional validations for specific pages
    if (formType === 'forgot-password') {
      // Example: Add special handling for forgot password if needed
      console.log('Forgot password page loaded');
    } else if (formType === 'reset-password') {
      // Example: Ensure both passwords are filled correctly
      fv.addField('password', {
        validators: {
          notEmpty: {
            message: 'Please enter your new password'
          }
        }
      });
      fv.addField('confirm-password', {
        validators: {
          notEmpty: {
            message: 'Please confirm your new password'
          },
          identical: {
            compare: function () {
              return formAuthentication.querySelector('[name="password"]').value;
            },
            message: 'The password and its confirmation do not match'
          }
        }
      });
    }
  }

  // Verification input masking for two-factor authentication or similar use cases
  const numeralMask = document.querySelectorAll('.numeral-mask');
  if (numeralMask.length) {
    numeralMask.forEach(e => {
      new Cleave(e, {
        numeral: true
      });
    });
  }
});
