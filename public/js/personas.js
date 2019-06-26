new Vue({
    el: "#app",
    data: () => ({
        guardandoPersona: false,
        nuevaPersona: {
            nombre: null,
        },
        personaEditada: {
            id: null,
            nombre: null,
        },
        personas: [],
        encabezadoPersonas: [{
                key: "nombre",
                sortable: true,
                label: "Nombre"
            },
            {
                key: "editar",
                sortable: false,
                label: "Editar"
            },
            {
                key: "eliminar",
                sortable: false,
                label: "Eliminar"
            },
        ],
    }),
    mounted() {
        this.obtenerPersonas();
    },
    methods: {
        eliminarPersona(persona) {
            if (!confirm("Confirme la acción")) return;
            return fetch(RUTA_API + "/persona/" + persona.id, {
                    method: "DELETE",
                })
                .then(r => r.json())
                .then(respuesta => {
                    if (respuesta) {
                        this.obtenerPersonas();
                        this.$bvToast.toast('Persona eliminada', {
                            title: "Éxito",
                            toaster: "b-toaster-top-center",
                            variant: "warning",
                            solid: true,
                        });
                    }
                })
                .finally(() => {});
        },
        obtenerPersonas() {
            return fetch(RUTA_API + "/personas")
                .then(r => r.json())
                .then(personas => {
                    this.personas = personas;
                })
                .finally(() => {});
        },
        mostrarModalActualizarPersona(persona) {
            this.$refs.modalEditarPersona.show();
            this.personaEditada.id = persona.id;
            this.personaEditada.nombre = persona.nombre;
        },
        actualizarPersona() {
            if (!this.personaEditada.nombre) return alert("Escribe el nombre");
            this.guardandoPersona = true;
            return fetch(RUTA_API + "/persona", {
                    body: JSON.stringify(this.personaEditada),
                    method: "PUT",
                })
                .then(r => r.json())
                .then(respuesta => {
                    if (respuesta) {
                        this.obtenerPersonas();
                        this.personaEditada.nombre = "";
                        this.cerrarModalEditarPersona();
                        this.$bvToast.toast('Actualizada con éxito', {
                            title: "Éxito",
                            toaster: "b-toaster-top-center",
                            variant: "success",
                            solid: true
                        });
                    }
                })
                .finally(() => this.guardandoPersona = false);
        },
        guardarPersona() {
            if (!this.nuevaPersona.nombre) return alert("Escribe el nombre");
            this.guardandoPersona = true;
            return fetch(RUTA_API + "/persona", {
                    body: JSON.stringify(this.nuevaPersona),
                    method: "POST",
                })
                .then(r => r.json())
                .then(respuesta => {
                    if (respuesta) {
                        this.obtenerPersonas();
                        this.$bvToast.toast('Persona guardada', {
                            title: "Éxito",
                            toaster: "b-toaster-top-center",
                            variant: "success",
                            solid: true
                        });
                    }
                    this.cerrarModalNuevaPersona();
                    this.nuevaPersona.nombre = "";
                })
                .finally(() => this.guardandoPersona = false);
        },
        cerrarModalNuevaPersona() {
            this.$refs.modalAgregarPersona.hide();
        },
        cerrarModalEditarPersona() {
            this.$refs.modalEditarPersona.hide();
        },
    },
});