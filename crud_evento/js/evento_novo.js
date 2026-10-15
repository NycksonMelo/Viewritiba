document.getElementById("formEvento").addEventListener("submit", (evento) => {
    evento.preventDefault();
    novo();
});

async function novo() {
    const form = document.getElementById("formEvento");
    const fd = new FormData(form);
    const retorno = await fetch("../php/evento_novo.php", {
        method: "POST",
        body: fd
    });
    const resposta = await retorno.json();

    if (retorno.status === 401) {
        window.location.href = "../../crud_organizador/login/Login.html";
        return;
    }

    if (resposta.status === "ok") {
        alert(resposta.mensagem);
        window.location.href = "../../crud_organizador/home/perfil_organizador.html";
        return;
    }

    alert("Erro: " + resposta.mensagem);
}