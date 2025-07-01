document.addEventListener('DOMContentLoaded', () => {
    const paymentMethodSelect = document.getElementById('payment_method');
    const boletoFields = document.getElementById('boleto_fields');
    const cardElement = document.getElementById('card-element');

    if (paymentMethodSelect && boletoFields && cardElement) {
        const inputs = boletoFields.querySelectorAll('[data-required="true"]');

        paymentMethodSelect.addEventListener('change', function() {
            if (this.value === 'boleto') {
                boletoFields.style.display = 'block';
                cardElement.style.display = 'none';
                inputs.forEach(input => input.setAttribute('required', 'required'));
            } else {
                boletoFields.style.display = 'none';
                cardElement.style.display = 'block';
                inputs.forEach(input => input.removeAttribute('required'));
            }
        });

        boletoFields.style.display = 'none';
        cardElement.style.display = 'block';
        inputs.forEach(input => input.removeAttribute('required'));
    }
});