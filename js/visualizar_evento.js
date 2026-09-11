let evento_tela;
const url = new URLSearchParams(window.location.search);
const id = url.get("id");
document.addEventListener("DOMContentLoaded", () => {
    const btn_voltar = document.getElementById("btn_voltar");
    evento_tela = document.getElementById("evento_tela");
    if (btn_voltar){
        btn_voltar.addEventListener("click", (e) => {
            e.preventDefault();
            voltar();
        })
    }
    renderizar_evento(id);
}
)
function renderizar_evento(id){
    const eventos = JSON.parse(localStorage.getItem('eventos')) || [];
    const evento = eventos.find(e => e.id == id);
    const role = localStorage.getItem("role");
    if (!evento) {
        alert("Evento não encontrado!");
        window.location.href = 'eventos.html';
    }
    let btn_alterar = "";
    let btn_excluir = "";
    if (role == "organizador" || role == "admin") {
        btn_alterar = `<button type = "button">Alterar</button>`;
        btn_excluir = `<button type = "button">Excluir</button>`;
    }
    evento_tela.innerHTML = `
        <div><h2>${evento.nome}</h2>
            <little>${evento.data}</little>
            <p>${evento.descricao}</p>
            <div class="actions-row">
                ${btn_alterar} ${btn_excluir}
            </div>
            <hr>
        </div>
    `;
}
function voltar() {
    window.location.href = 'eventos.html';
};