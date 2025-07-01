document.addEventListener('DOMContentLoaded', () => {
    const stripeKey = document.querySelector('#payment-form')?.dataset.stripeKey;
    if (!stripeKey) {
        return;
    }

    const stripe = Stripe(stripeKey);
    const elements = stripe.elements();
    const cardElement = elements.create('card');

    const form = document.getElementById('payment-form');
    const paymentMethodSelect = document.getElementById('payment_method');
    const cardContainer = document.getElementById('card-element');
    const paymentMessage = document.getElementById('payment-message');

    if (!form || !paymentMethodSelect || !cardContainer || !paymentMessage) {
        return;
    }

    cardElement.mount('#card-element');

    cardElement.on('change', (event) => {
        if (event.error) {
            paymentMessage.innerText = event.error.message;
        } else {
            paymentMessage.innerText = '';
        }
    });

    function togglePaymentMethod() {
        const paymentMethod = paymentMethodSelect.value;
        if (paymentMethod === 'boleto') {
            cardContainer.style.display = 'none';
            cardElement.clear();
            paymentMessage.innerText = 'Você selecionou Boleto. Clique em Pagar para gerar o boleto.';
        } else {
            cardContainer.style.display = 'block';
            cardContainer.innerHTML = '';
            cardElement.mount('#card-element');
            paymentMessage.innerText = '';
        }
    }

    paymentMethodSelect.addEventListener('change', togglePaymentMethod);

    form.addEventListener('submit', async (event) => {
        const paymentMethod = paymentMethodSelect.value;
        if (paymentMethod === 'boleto') {
            return;
        }

        event.preventDefault();
        const { token, error } = await stripe.createToken(cardElement);
        if (error) {
            paymentMessage.innerText = error.message;
            return;
        }

        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'token';
        hiddenInput.value = token.id;
        form.appendChild(hiddenInput);

        form.submit();
    });

    togglePaymentMethod();
});