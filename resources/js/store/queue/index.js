document.addEventListener('alpine:init', () => {
    Alpine.data('queueHandler', () => {
        if (typeof window.StoreQueueConfig === 'undefined') {
            console.error('StoreQueueConfig is not defined.');
            return {};
        }

        const config = window.StoreQueueConfig;

        return {
            orders: config.pendingOrders || [],
            isConnected: false,
            processingId: null,
            cancellingId: null,
            toast: { show: false, message: '', type: 'success' },

            init() {
                if (typeof window.Echo !== 'undefined') {
                    window.Echo.connector.pusher.connection.bind('connected', () => {
                        this.isConnected = true;
                    });
                    window.Echo.connector.pusher.connection.bind('disconnected', () => {
                        this.isConnected = false;
                    });

                    window.Echo.private(`store.${config.storeId}.queue`)
                        .listen('NewPendingOrder', (event) => {
                            console.log("Nouvelle commande reçue !", event);

                            let newOrder = event.order;
                            newOrder.isNew = true;

                            this.orders.unshift(newOrder);

                            setTimeout(() => {
                                let idx = this.orders.findIndex(o => o.id === newOrder.id);
                                if (idx !== -1) {
                                    this.orders[idx].isNew = false;
                                }
                            }, 5000);
                        })
                        .listen('OrderCancelled', (event) => {
                            console.log("Commande annulée (broadcast) !", event);
                            this.orders = this.orders.filter(o => o.id !== event.order_id);
                        })
                        .listen('OrderReadyForPickup', (event) => {
                            console.log("Commande prête (broadcast) !", event);
                            this.orders = this.orders.filter(o => o.id !== event.order.id);
                        });
                } else {
                    console.error("Laravel Echo n'est pas défini. Les WebSockets ne fonctionneront pas.");
                }
            },

            async markAsReady(orderId) {
                if (this.processingId === orderId) return;

                if (!confirm(`Valider la commande #${orderId} ?`)) return;

                this.processingId = orderId;

                try {
                    let url = config.routes.completeOrder.replace(':order', orderId);

                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': config.csrfToken
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.orders = this.orders.filter(o => o.id !== orderId);
                        this.showToast('Commande marquée comme prête !', 'success');
                    } else {
                        this.showToast('Erreur lors de la validation', 'error');
                    }
                } catch (error) {
                    console.error("Erreur:", error);
                    this.showToast('Erreur technique', 'error');
                } finally {
                    this.processingId = null;
                }
            },

            async cancelOrder(orderId) {
                if (this.cancellingId === orderId) return;

                if (!confirm(`Êtes-vous sûr de vouloir annuler la commande #${orderId} ?`)) return;

                this.cancellingId = orderId;

                try {
                    let url = config.routes.cancelOrder.replace(':order', orderId);

                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': config.csrfToken
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.orders = this.orders.filter(o => o.id !== orderId);
                        this.showToast('Commande #' + orderId + ' annulée.', 'error');
                    } else {
                        this.showToast("Erreur lors de l'annulation", 'error');
                    }
                } catch (error) {
                    console.error("Erreur annulation:", error);
                    this.showToast('Erreur technique', 'error');
                } finally {
                    this.cancellingId = null;
                }
            },

            showToast(msg, type = 'success') {
                this.toast.message = msg;
                this.toast.type = type;
                this.toast.show = true;
                setTimeout(() => { this.toast.show = false; }, 3000);
            }
        };
    });
});

