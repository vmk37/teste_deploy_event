document.addEventListener('DOMContentLoaded', () => {
    const cepInput = document.getElementById('postal_code');
    if (cepInput) {
        cepInput.addEventListener('input', async () => {
            let cep = cepInput.value.replace('-', '');
            if (cep.length === 8) {
                try {
                    const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                    const data = await response.json();

                    if (!data.erro) {
                        document.getElementById('street').value = data.logradouro || '';
                        document.getElementById('city').value = data.localidade || '';
                        document.getElementById('state').value = data.uf || '';
                    } else {
                        alert('CEP não encontrado!');
                    }
                } catch (error) {
                    alert('Erro ao buscar o CEP. Por favor, tente novamente.');
                }
            }
        });
    }
});