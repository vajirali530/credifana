$(document).ready(function () {

  function refreshCaptcha() {
    grecaptcha
      .execute(GOOGLE_CAPTCHA_SITE_KEY, {
          action: "homepage",
      })
      .then(function(token) {
          var recaptchaResponse = document.getElementById("recaptchaResponse");
          recaptchaResponse.value = token;
      });
  }
    // Contact us form validation
    $("#contact-form").validate({
        rules: {
            user_name: {
              required: true,
              validName: true,
            },
            user_email: {
              required: true,
              email: true,
              validEmail: true,
            },
            user_phone: {
              required: true,
            },
            user_requirement: {
              required: true,
            },
        },
        messages: {
            user_name: "Please enter your Full Name.",
            user_email: {
              required: "Please enter your Email Address.",
              email: "Not a valid EMAIL",
            },
            user_phone: {
              required: "Please enter your Phone number.",
            },
            user_requirement: {
              required: "Please enter your requirement.",
            },
        },

        submitHandler: function (form) {
            var fd = $(form).serialize();
            $("button[name=submit_contact_form]").attr("disabled", true);
            $(".submit-loader").removeClass("d-none").addClass("d-inline-block");
            $(".error-message").html("");
            $.ajax({
              type: "POST",
              url: "./formHandler",
              data: fd,
              success: function (response) {
                $("button[name=submit_contact_form]").attr("disabled", false);
                $(".submit-loader").removeClass("d-inline-block").addClass("d-none");
                response = JSON.parse(response);
                if (response.success) {
                  Swal.fire(
                    'Success!',
                    `${response.success}`,
                    'success'
                  )
                  $("form")[0].reset();
                } else {
                  Swal.fire(
                    'Error!',
                    'Unwantederror while submiting form. Please try again',
                    'error'
                  )
                }
                refreshCaptcha();
              },
              error: function (data) {
                data = JSON.parse(data.responseText);
                $("button[name=submit_contact_form]").attr("disabled", false);
                $(".submit-loader").removeClass("d-inline-block").addClass("d-none");
                refreshCaptcha();
      
                if (data.errors) {
                  Object.keys(data.errors).forEach(function (key) {
                    $("#" + key).closest(".form-group").find(".error-message").html(data.errors[key]);
                  });
                }
      
                if (data.toast) {
                  Swal.fire(
                    'Oops!',
                    `${data.toast}`,
                    'error'
                  )
                }
              },
            });
          },
    });


    //  Common validation for Emails
    jQuery.validator.addMethod(
        "validEmail",
        function (value, element) {
            // allow any non-whitespace characters as the host part
            return (
                this.optional(element) ||
                /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(
                value
                )
            );
        },
        "Please enter a valid email address."
    );

    //  Common validation for Names
    jQuery.validator.addMethod(
        "validName",
        function (value, element) {
            //   console.log(value, element);
            return (
                this.optional(element) ||
                /^[\w][^\/"\'\-,.`^_!¡?÷?¿\\+=@#$%ˆ&*(){}|~<>;:[\]]{1,}$/gi.test(value)
            );
        },
        "Please enter a valid Name."
    );

    
    $("input[type=text]").on("keypress", function (e) {
      console.log('text');
        let pattern = /[^a-zA-Z\s]/gi;
        let target = e.key;
        if (target.match(pattern)) {
            e.preventDefault();
            return false;
        }
      });
      
    $("input[name=country-code]").on("keypress", function (e) {
      console.log('country');
      e.preventDefault();
      return false;
    });
    
    $("input[type=tel]").on("keypress", function (e) {
      let pattern = /[^0-9]/gi;
      let target = e.key;
      if (target.match(pattern)) {
        e.preventDefault();
        return false;
      }
    });
});