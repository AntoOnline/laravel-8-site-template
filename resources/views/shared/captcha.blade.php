<input type="hidden" name="recaptcha" id="recaptcha">
<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.sitekey') }}"></script>
<script>
grecaptcha.ready(function () {
    grecaptcha.execute("{{ config('services.recaptcha.sitekey') }}", {action: 'contact'}).then(function (token) {
        if (token) {
            document.getElementById('recaptcha').value = token;
        }
    });
});
</script>