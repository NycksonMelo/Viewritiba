document.addEventListener("DOMContentLoaded", () => {
    const btn_voltar = document.getElementById("btn_voltar");
    const btn_enviar = document.getElementById("btn_enviar");
    if (btn_voltar){
        btn_voltar.addEventListener("click", (e) => {
            e.preventDefault();
            voltar();
        })
    if (btn_enviar) {
        btn_enviar.addEventListener("click", (e) =>{
            e.preventDefault();
            enviar();
        })
    }
    }
})
function enviar() {
    const id_evento = Date.now();
    const nome_evento = document.getElementById("new_nome").value;
    const desc_evento = document.getElementById("new_desc").value;
    const data_evento = document.getElementById("new_date").value;
    const img_evento = document.getElementById("new_img").value;
    const evento = {
        id: id_evento,
        nome: nome_evento,
        descricao: desc_evento,
        data: data_evento,
        imagem: img_evento
    };
    let eventos = JSON.parse(localStorage.getItem('eventos')) || [];
    eventos.push(evento);
    localStorage.setItem('eventos', JSON.stringify(eventos));
    window.location.href = 'eventos.html';
};
function voltar() {
    window.location.href = 'eventos.html';
};