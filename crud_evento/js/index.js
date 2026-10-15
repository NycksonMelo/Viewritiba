document.addEventListener("DOMContentLoaded", () => {
    buscar();
});
document.getElementById("novo").addEventListener("click", () => {
    window.location.href = 'evento_novo.html';
});
document.getElementById("buscar").addEventListener("click", () => {
    pesquisar();
});
async function buscar(){
    const retorno = await fetch("../php/evento_get.php");
    const resposta = await retorno.json();
    if (retorno.status === 401) {
        window.location.replace("../../crud_organizador/login/Login.html");
    } else if(resposta.status == "ok"){
        preencherTabela(resposta.data);    
    } else {
        document.getElementById("lista").innerHTML =
            "<p>Você ainda não cadastrou eventos.</p>";
    }
}
async function pesquisar() {
    const titulo = document.getElementById("pesquisa").value;
    const retorno = await fetch(
        "../php/evento_get.php?titulo=" + encodeURIComponent(titulo)
    );
    const resposta = await retorno.json();
    if (retorno.status === 401) {
        window.location.replace("../../crud_organizador/login/Login.html");
    } else if (resposta.status == "ok") {
        preencherTabela(resposta.data);
    } else {
        document.getElementById("lista").innerHTML =
            "<p>Nenhum evento encontrado.</p>";
    }
}
async function excluir(id){
    if (!confirm("Tem certeza que deseja excluir este evento?")) {
        return;
    }
    const retorno = await fetch("../php/evento_excluir.php?id=" + id);
    const resposta = await retorno.json();
    if(resposta.status == "ok"){
        alert(resposta.mensagem);
        buscar();
    }else{
        alert(resposta.mensagem);
    }
}
function preencherTabela(tabela){
    var html = `
        <table>
            <tr>
                <th> Título </th>
                <th> Descrição </th>
                <th> Data e Hora </th>
                <th> Local </th>
                <th> Ações </th>
            </tr>
    `;
    for(var i=0;i<tabela.length;i++){
        html += `
            <tr>
                <td>${tabela[i].titulo}</td>
                <td>${tabela[i].descricao}</td>
                <td>${tabela[i].data_hora}</td>
                <td>${tabela[i].local}</td>
                <td>
                    <a href='evento_alterar.html?id=${tabela[i].id}'>Alterar</a>
                    <a href='#' onclick='excluir(${tabela[i].id})'>Excluir</a>
               </td>
            </tr>
        `;
    }
    html += '</table>';
    document.getElementById("lista").innerHTML = html;
}
