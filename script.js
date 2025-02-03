const cep = document.querySelector("#cep"); //Buscando o input com id CEP

//Método para mostrar os dados que retornarem (result) da API 
const showData = (result)=>{
    for(const campo in result){
        //Retornando apenas os campos do result que equivalem ao ID dos inputs do form HTML
        let condition = document.querySelector(`#${campo}`);
        if(condition){
            console.log(campo);
            condition.value = result[campo];
        }else{
            console.log(campo + " Não encontrado");
        }
    }
}
//Adicionando um evento com o blur (quando o usuário digitar o CEP e clicar em outra parte da tela)
cep.addEventListener("blur",(e)=>{
    let search = cep.value.replace("-",""); //Substituindo o "-" do CEP para que a API seja consultada somente com os números
    
    //Declarando os padrões de consulta
    const options = {
        method: 'GET',     //Buscar dados do servidor API (padrão do fetch)
        mode: 'no-cors',     //Permite que a requisição seja feita para outro domínio se o servidor permitir
        cache: 'default' //Usa o comportamento padrão do navegador
    }

    //Se o campo do CEP estiver vazio, voltar aos valores default;
    if(cep.value === ""){
        console.log("vazio");
    
        // Limpa todos os inputs do formulário
        document.querySelectorAll("input").forEach(input => {
            if (input.id !== "cep") { // Mantém o campo CEP sem alteração
                input.value = "";
            }
        });
    
        return;
    }
    
    //Fetch na API para buscar os dados de acordo com o CEP que o usuário digitou
    fetch(`https://apihom.correios.com.br/cep/v1/enderecos/${search}`, options)

    .then(response => {response.json()    //Se a consulta der certo, trazer a response em formato JSON
        .then( data => showData(data)) //Se trazer em JSON, retorne os dados com a função (showData)
        })
        
    .catch(e => console.log('Deu erro: ' + e))

})