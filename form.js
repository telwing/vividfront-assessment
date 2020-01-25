;(function (stateList) {
    'use strict';

    const addEntryForm = document.getElementById('addEntryForm')

    addEntryForm.addEventListener('submit', handleAddEntrySubmit)

    function handleAddEntrySubmit (e) {
        e.preventDefault()

        const nameField = document.getElementById('name')
        const emailField = document.getElementById('email')
        const stateField = document.getElementById('state')

        const valid = (
            validateName(nameField.value)
            && validateEmail(emailField.value)
            && validateState(stateField.value)
        )

        if (! valid) {
            alert('Form values invalid')
        } else {
            addEntryForm.submit()
        }
    }

    function validateName(value)
    {
        return /^[^\d~!@#$%^&*()]+$/g.test(value)
    }

    function validateEmail(value)
    {
        return /^[^\@]*\@[^\.]+(?=\.)/.test(value)
    }

    function validateState(value)
    {
        return stateList.indexOf(value) > -1;
    }

})(window.stateList || []);