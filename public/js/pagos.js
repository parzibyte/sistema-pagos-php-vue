new Vue({
    el: "#app",
    data: () => ({
        estaGuardando: false,
        nuevoPago: {
            monto: null,
            idPersona: null,
            fecha: null,
        },
        total: 0,
        pagoEditado: {
            monto: null,
            idPersona: null,
            fecha: null,
        },
        pagos: [],
        personas: [],
        encabezadoPagos: [
            {
                key: "persona",
                sortable: true,
                label: "Persona"
            },
            {
                key: "monto",
                sortable: true,
                label: "Monto"
            },
            {
                key: "fecha",
                sortable: true,
                label: "Fecha"
            },
            {
                key: "hash",
                sortable: true,
                label: "Hash"
            },
            {
                key: "imprimir",
                sortable: false,
                label: "Imprimir ticket"
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
        this.obtenerPersonas().then(() => this.obtenerPagos());
    },
    methods: {
        imprimir(pago) {
            return fetch(RUTA_API + "/ticket/" + pago.id);
        },
        eliminarPago(pago) {
            if (!confirm("Confirme la acción")) return;
            return fetch(RUTA_API + "/pago/" + pago.id, {
                method: "DELETE",
            })
                .then(r => r.json())
                .then(respuesta => {
                    if (respuesta) {
                        this.obtenerPagos();
                        this.$bvToast.toast('Pago eliminado', {
                            title: "Éxito",
                            toaster: "b-toaster-top-center",
                            variant: "warning",
                            solid: true,
                        });
                    }
                })
                .finally(() => { });
        },
        obtenerPagos() {
            return fetch(RUTA_API + "/pagos")
                .then(r => r.json())
                .then(respuesta => {
                    this.pagos = respuesta.pagos;
                    this.total = respuesta.total;
                })
                .finally(() => { });
        },
        obtenerPersonas() {
            return fetch(RUTA_API + "/personas")
                .then(r => r.json())
                .then(personas => {
                    let personasMapeadas = personas.map(persona => ({
                        text: persona.nombre,
                        value: persona.id,
                    }));
                    this.personas = personasMapeadas;
                    if (this.personas.length) this.nuevoPago.idPersona = this.personas[0].value;
                })
                .finally(() => { });
        },
        mostrarModalEditar(pago) {
            this.$refs.modalEditar.show();
            this.pagoEditado.id = pago.id;
            this.pagoEditado.idPersona = pago.idPersona;
            this.pagoEditado.monto = pago.monto;
            this.pagoEditado.fecha = pago.fecha;
        },
        actualizar() {
            let posibleMonto = parseFloat(this.pagoEditado.monto);
            if (isNaN(posibleMonto) || posibleMonto <= 0) return alert("Introduce un monto válido");
            if (!this.pagoEditado.idPersona) return alert("Selecciona una persona");
            if (!this.pagoEditado.fecha) return alert("Selecciona una fecha");
            this.estaGuardando = true;
            return fetch(RUTA_API + "/pago", {
                body: JSON.stringify(this.pagoEditado),
                method: "PUT",
            })
                .then(r => r.json())
                .then(respuesta => {
                    if (respuesta) {
                        this.obtenerPagos();
                        this.nuevoPago.monto = null;
                        this.nuevoPago.fecha = null;
                        this.nuevoPago.idPersona = null;
                        this.cerrarModalEditar();
                        this.$bvToast.toast('Actualizada con éxito', {
                            title: "Éxito",
                            toaster: "b-toaster-top-center",
                            variant: "success",
                            solid: true
                        });
                    }
                })
                .finally(() => this.estaGuardando = false);
        },
        guardar() {
            let posibleMonto = parseFloat(this.nuevoPago.monto);
            if (isNaN(posibleMonto) || posibleMonto <= 0) return alert("Introduce un monto válido");
            if (!this.nuevoPago.idPersona) return alert("Selecciona una persona");
            if (!this.nuevoPago.fecha) return alert("Selecciona una fecha");
            this.estaGuardando = true;
            return fetch(RUTA_API + "/pago", {
                body: JSON.stringify(this.nuevoPago),
                method: "POST",
            })
                .then(r => r.json())
                .then(respuesta => {
                    if (respuesta) {
                        this.obtenerPagos();
                        this.$bvToast.toast('Pago guardado', {
                            title: "Éxito",
                            toaster: "b-toaster-top-center",
                            variant: "success",
                            solid: true
                        });
                    }
                    this.cerrarModalAgregar();
                    this.nuevoPago.monto = null;
                    this.nuevoPago.fecha = null;
                    this.nuevoPago.idPersona = null;
                })
                .finally(() => this.estaGuardando = false);
        },
        cerrarModalAgregar() {
            this.$refs.modalAgregar.hide();
        },
        cerrarModalEditar() {
            this.$refs.modalEditar.hide();
        },
    },
});