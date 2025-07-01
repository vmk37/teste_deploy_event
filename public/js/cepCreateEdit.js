 async function buscarCEP() {
            let cep = document.getElementById('cep').value;
            cep = cep.replace('-', '');

            if (cep.length === 8) {
                try {
                    const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                    const data = await response.json();

                    if (!data.erro) {
                        document.getElementById('street').value = data.logradouro || '';
                        document.getElementById('neighborhood').value = data.bairro || '';
                        document.getElementById('state').value = data.uf || '';
                        document.getElementById('municipality').value = data.localidade || '';
                    } else {
                        alert('CEP não encontrado!');
                    }
                } catch (error) {
                    alert('Erro ao buscar o CEP. Por favor, tente novamente.');
                }
            } else {
                alert('Por favor, insira um CEP válido.');
            }
        }