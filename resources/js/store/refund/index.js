document.addEventListener('alpine:init', () => {
    Alpine.data('refundManager', () => {
        if (typeof window.StoreRefundConfig === 'undefined') {
            console.error('StoreRefundConfig is not defined.');
            return {};
        }

        const config = window.StoreRefundConfig;

        return {
            nfcCode: '',
            orders: [],
            isLoading: false,
            message: '',
            messageType: '',

            async searchOrders() {
                if (!this.nfcCode) return;

                this.isLoading = true;
                this.message = '';
                this.orders = [];

                try {
                    let response = await fetch(config.routes.search, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': config.csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ nfc_code: this.nfcCode })
                    });

                    let data = await response.json();

                    if (!response.ok) throw new Error(data.message || 'Erreur lors de la recherche.');

                    this.orders = data.orders;

                    if (this.orders.length === 0) {
                        this.message = "Aucune commande complétée n'a été trouvée pour ce participant.";
                        this.messageType = 'info';
                    }

                } catch (error) {
                    this.message = error.message;
                    this.messageType = 'error';
                } finally {
                    this.isLoading = false;
                    this.nfcCode = '';
                    // this.$refs.nfcInput.focus(); // Removed as $refs might not be available immediately in JS context if not careful, though usually fine in Alpine. Left for safety if it was causing issues, but usually it works.
                    // Better to keep it if it was there and working. Let's put it back with a safety check.
                    if (this.$refs && this.$refs.nfcInput) {
                        this.$refs.nfcInput.focus();
                    }
                }
            },

            async processRefund(orderId) {
                if (!confirm('Confirmer le remboursement ? Le solde du client sera recrédité.')) return;

                this.isLoading = true;
                this.message = '';

                try {
                    let response = await fetch(config.routes.process, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': config.csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ order_id: orderId })
                    });

                    let data = await response.json();

                    if (!response.ok) throw new Error(data.message || 'Erreur lors du remboursement.');

                    this.message = data.message;
                    this.messageType = 'success';

                    // Supprime la commande remboursée de l'interface avec une petite animation
                    this.orders = this.orders.filter(o => o.id !== orderId);

                } catch (error) {
                    this.message = error.message;
                    this.messageType = 'error';
                } finally {
                    this.isLoading = false;
                }
            }
        };
    });
});
