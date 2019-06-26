new Vue({
    el: "#app",
    data: () => ({
        guardandoUsuario: false,
        nuevoUsuario: {
            correo: null,
            palabraSecreta: null,
            palabraSecretaConfirmar: null,
        },
        usuarioEditado: {
            id: null,
            palabraSecretaActual: null,
            palabraSecretaNueva: null,
            palabraSecretaNuevaConfirmar: null,
        },
        usuarios: [],
        encabezadoUsuarios: [{
                key: "correo",
                sortable: true,
                label: "Correo electrónico"
            },
            {
                key: "editar",
                sortable: false,
                label: "Cambiar contraseña"
            },
            {
                key: "eliminar",
                sortable: false,
                label: "Eliminar"
            },
        ],
    }),
    mounted() {
        this.obtenerUsuarios();
    },
    methods: {
        eliminarUsuario(usuario) {
            if (!confirm("Confirme la acción")) return;
            return fetch(RUTA_API + "/usuario/" + usuario.id, {
                    method: "DELETE",
                })
                .then(r => r.json())
                .then(respuesta => {
                    if (respuesta) {
                        this.obtenerUsuarios();
                        this.$bvToast.toast('Usuario eliminado', {
                            title: "Éxito",
                            toaster: "b-toaster-top-center",
                            variant: "warning",
                            solid: true,
                        });
                    }
                })
                .finally(() => {});
        },
        obtenerUsuarios() {
            return fetch(RUTA_API + "/usuarios")
                .then(r => r.json())
                .then(usuarios => {
                    this.usuarios = usuarios;
                })
                .finally(() => {});
        },
        mostrarModalCambioDePalabraSecreta(usuario) {
            this.$refs.modalCambiarPalabraSecretaUsuario.show();
            this.usuarioEditado.id = usuario.id;
        },
        cambiarPalabraSecreta() {
            console.log("Lo cambia: ", this.usuarioEditado);
            if (!this.usuarioEditado.palabraSecretaActual) return alert("Escribe la contraseña actual");
            if (!this.usuarioEditado.palabraSecretaNueva) return alert("Escribe la nueva contraseña");
            if (!this.usuarioEditado.palabraSecretaNuevaConfirmar) return alert("Confirma la nueva contraseña");
            if (this.usuarioEditado.palabraSecretaNueva !== this.usuarioEditado.palabraSecretaNuevaConfirmar) return alert("Las nuevas contraseñas no coinciden");
            this.guardandoUsuario = true;
            return fetch(RUTA_API + "/usuario", {
                    body: JSON.stringify(this.usuarioEditado),
                    method: "PUT",
                })
                .then(r => r.json())
                .then(respuesta => {
                    if (respuesta) {
                        this.obtenerUsuarios();
                        this.usuarioEditado.palabraSecretaActual = "";
                        this.usuarioEditado.palabraSecretaNueva = "";
                        this.usuarioEditado.palabraSecretaNuevaConfirmar = "";
                        this.cerrarModalCambiarPalabraSecreta();
                        this.$bvToast.toast('Contraseña cambiada', {
                            title: "Éxito",
                            toaster: "b-toaster-top-center",
                            variant: "success",
                            solid: true
                        });
                    } else {
                        alert("La contraseña actual no coincide. Intenta de nuevo");
                    }
                })
                .finally(() => this.guardandoUsuario = false);
        },
        guardarUsuario() {
            if (!this.nuevoUsuario.correo) return alert("Escribe el correo electrónico");
            if (!this.nuevoUsuario.correo.includes("@")) return alert("Escribe un correo electrónico válido");
            if (!this.nuevoUsuario.palabraSecreta) return alert("Escribe la contraseña");
            if (!this.nuevoUsuario.palabraSecretaConfirmar) return alert("Confirma la contraseña");
            if (this.nuevoUsuario.palabraSecreta !== this.nuevoUsuario.palabraSecretaConfirmar) return alert("Las contraseñas no coinciden");
            this.guardandoUsuario = true;
            return fetch(RUTA_API + "/usuario", {
                    body: JSON.stringify(this.nuevoUsuario),
                    method: "POST",
                })
                .then(r => r.json())
                .then(respuesta => {
                    if (respuesta) {
                        this.obtenerUsuarios();
                        this.$bvToast.toast('Usuario guardado', {
                            title: "Éxito",
                            toaster: "b-toaster-top-center",
                            variant: "success",
                            solid: true
                        });
                    }
                    this.cerrarModalNuevoUsuario();
                    this.nuevoUsuario.correo = "";
                    this.nuevoUsuario.palabraSecreta = "";
                    this.nuevoUsuario.palabraSecretaConfirmar = "";
                })
                .finally(() => this.guardandoUsuario = false);
        },
        cerrarModalNuevoUsuario() {
            this.$refs.modalAgregarUsuario.hide();
        },
        cerrarModalCambiarPalabraSecreta() {
            this.$refs.modalCambiarPalabraSecretaUsuario.hide();
        },
    },
});