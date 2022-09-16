$(document).ready(function () {
    //   estimate page for country code
    const input = document.querySelector(".tel");
    if (input) {
        var iti = window.intlTelInput(input, {
            autoHideDialCode: true,
            separateDialCode: true,
            initialCountry: 'US',
        });
        let target_field = $(input).closest(".country-code").next().find("input");
        input.addEventListener("countrychange", function (e, countryData) {
            $(target_field).val("");
            $(target_field).mask($(this).attr("placeholder").replace(/[0-9]/g, "9"));
            var selectedData = iti.getSelectedCountryData();
            $('#dial-code').val(selectedData.dialCode);
            $('#country-name').val(selectedData.name);
            $('#customer_phone').focus();
        });

        $(target_field).mask($(input).attr("placeholder").replace(/[0-9]/g, "9"));
        
        var selectedData = iti.getSelectedCountryData();
        $('#dial-code').val(selectedData.dialCode);
        $('#country-name').val(selectedData.name);
    }
});