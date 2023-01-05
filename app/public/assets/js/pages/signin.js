function handleFormSubmit() {
    // Get the form element
    let form = $('#signin-form');
    let isValid = form[0].checkValidity();

    console.log(isValid);

    if (!isValid) {
        return new Swal("Error", "The details entered are invalid", "error");
    }

    // Use the serializeArray function to get an array of objects representing the form data
    let formData = form.serializeArray().reduce((o, kv) => ({ ...o, [kv.name]: kv.value }), {})
    let url = formData['partner'] ? "/partner" + form.attr('action') : form.attr('action');

    axios.post(url, formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
        .then(response => {
            // If the request is successful, redirect to the "/shops" route
            window.location.replace(formData['partner'] ? '/dashboard' : '/shops');
        })
        .catch(error => {
            return new Swal("Error", error?.response?.data, "error");
        });
}  