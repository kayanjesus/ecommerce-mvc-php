// Ativar botão clicado no menu
const menuButtons = document.querySelectorAll(".menu-btn");
menuButtons.forEach(btn => {
  btn.addEventListener("click", () => {
    menuButtons.forEach(b => b.classList.remove("active"));
    btn.classList.add("active");
  });
});

// Atualizar nome de usuário ao digitar
const userName = document.getElementById("user-name");
userName.addEventListener("input", () => {
  // Aqui você pode salvar ou fazer algo com o nome, se quiser
});

// Pré-visualizar imagem de perfil
const profileInput = document.getElementById("profile-pic");
const userIcon = document.getElementById("user-icon");

profileInput?.addEventListener("change", (e) => {
  const file = e.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = () => {
      userIcon.src = reader.result;
    };
    reader.readAsDataURL(file);
  }
});