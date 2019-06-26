new Vue({
    el: "#app",
    data: () => ({
        usuario: {
            correo: null,
            palabraSecreta: null,
        },
        cargando: false,
    }),
    methods: {
        iniciarSesion() {
            if (!this.usuario.correo) return alert("Escribe el correo electrónico");
            if (!this.usuario.correo.includes("@")) return alert("Escribe un correo electrónico válido");
            if (!this.usuario.palabraSecreta) return alert("Escribe la contraseña");
            this.cargando = true;
            return fetch(RUTA_API + "/usuario/login", {
                    body: JSON.stringify(this.usuario),
                    method: "PUT",
                })
                .then(r => r.json())
                .then(respuesta => {
                    if (respuesta) {
                        window.location.href = "./usuarios"
                    } else {
                        this.$bvToast.toast('El correo o la contraseña no coinciden', {
                            title: "Error",
                            toaster: "b-toaster-top-center",
                            variant: "success",
                            solid: true
                        });
                    }
                })
                .finally(() => this.cargando = false);
        }
    },
});