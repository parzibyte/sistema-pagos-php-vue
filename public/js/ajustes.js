new Vue({
    el: "#app",
    data: () => ({
        ajustes: {
            MENSAJE_TICKET: "",
        },
        cargando: false,
    }),
    mounted() {
        this.obtenerAjustes();
    },
    methods: {
        obtenerAjustesSerializados() {
            const ajustesComoArreglo = [];
            for (clave in this.ajustes) {
                if (!this.ajustes.hasOwnProperty(clave)) continue;
                ajustesComoArreglo.push({
                    clave,
                    valor: this.ajustes[clave],
                });
            }
            return ajustesComoArreglo;
        },
        deserializarAjustes(ajustesComoArreglo) {
            const ajustes = {};
            ajustesComoArreglo
                .forEach(ajuste => ajustes[ajuste.clave] = ajuste.valor);
            return ajustes;
        },
        obtenerAjustes() {
            this.cargando = true;
            return fetch(RUTA_API + "/ajustes")
                .then(r => r.json())
                .then(ajustesComoArreglo => {
                    this.ajustes = this.deserializarAjustes(ajustesComoArreglo);
                })
                .finally(() => this.cargando = false);
        },
        guardarAjustes() {
            this.cargando = true;
            const ajustes = this.obtenerAjustesSerializados();
            return fetch(RUTA_API + "/ajustes", {
                    body: JSON.stringify(ajustes),
                    method: "PUT",
                })
                .then(r => r.json())
                .then(respuesta => {
                    if (respuesta) {
                        this.$bvToast.toast('Ajustes guardados correctamente', {
                            title: "Correcto",
                            toaster: "b-toaster-top-center",
                            variant: "success",
                            solid: true
                        });
                    } else {
                        this.$bvToast.toast('No se pudieron guardar los ajustes', {
                            title: "Error",
                            toaster: "b-toaster-top-center",
                            variant: "danger",
                            solid: true
                        });
                    }
                })
                .finally(() => this.cargando = false);
        }
    },
});