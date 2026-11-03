import './bootstrap';

// Smart Palao Global Client Helpers
window.SmartPalao = {
    // Temperature to RGB color mapping for AMG8833 thermal camera
    getThermalColor(temp, setpoint = 37.5, critical = 38.5) {
        if (temp >= critical) {
            return { bg: '#E63946', text: '#FFFFFF', label: 'CRITICAL HOT' };
        }
        if (temp >= setpoint + 0.5) {
            return { bg: '#F59E0B', text: '#1F1F1F', label: 'WARM' };
        }
        if (temp >= setpoint - 0.2 && temp <= setpoint + 0.4) {
            return { bg: '#FFC107', text: '#1F1F1F', label: 'OPTIMAL' };
        }
        if (temp >= setpoint - 0.8) {
            return { bg: '#D9CBB8', text: '#262626', label: 'TAUPE / EQUILIBRIUM' };
        }
        return { bg: '#89B4C4', text: '#FFFFFF', label: 'COOL ZONE' };
    },

    // Cart Management using LocalStorage
    cart: {
        getItems() {
            try {
                return JSON.parse(localStorage.getItem('smart_palao_cart')) || [];
            } catch (e) {
                return [];
            }
        },
        saveItems(items) {
            localStorage.setItem('smart_palao_cart', JSON.stringify(items));
            window.dispatchEvent(new CustomEvent('cart-updated', { detail: items }));
        },
        addItem(product, qty = 1) {
            const items = this.getItems();
            const existing = items.find(i => i.id === product.id);
            if (existing) {
                existing.quantity = Math.min(existing.quantity + qty, product.stock);
            } else {
                items.push({
                    id: product.id,
                    size: product.size,
                    price: parseFloat(product.price),
                    branch: product.branch_location,
                    stock: product.stock,
                    quantity: Math.min(qty, product.stock),
                    image: product.image_url,
                });
            }
            this.saveItems(items);
            this.showToast(`Added ${qty} dozen ${product.size} Duck Eggs to cart!`);
        },
        removeItem(productId) {
            let items = this.getItems();
            items = items.filter(i => i.id !== productId);
            this.saveItems(items);
        },
        updateQty(productId, qty) {
            const items = this.getItems();
            const item = items.find(i => i.id === productId);
            if (item) {
                if (qty <= 0) {
                    this.removeItem(productId);
                } else {
                    item.quantity = Math.min(qty, item.stock);
                    this.saveItems(items);
                }
            }
        },
        clear() {
            localStorage.removeItem('smart_palao_cart');
            window.dispatchEvent(new CustomEvent('cart-updated', { detail: [] }));
        },
        count() {
            return this.getItems().reduce((acc, item) => acc + item.quantity, 0);
        },
        subtotal() {
            return this.getItems().reduce((acc, item) => acc + (item.price * item.quantity), 0);
        },
        showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-5 right-5 z-50 px-4 py-3 rounded-xl shadow-lg border text-sm font-semibold flex items-center gap-2 transition-all duration-300 transform translate-y-0 ${
                type === 'danger' 
                    ? 'bg-red-50 border-red-300 text-red-800' 
                    : 'bg-[#FFF8E1] border-[#FFC107] text-[#3A3A3A]'
            }`;
            toast.innerHTML = `
                <span>${type === 'danger' ? '⚠️' : '🥚'}</span>
                <span>${message}</span>
            `;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    },

    // Audio synthesizer for thermal runaway alert
    alertAudio: {
        ctx: null,
        isMuted: false,
        playAlarm() {
            if (this.isMuted) return;
            try {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                if (!AudioContext) return;
                if (!this.ctx) this.ctx = new AudioContext();
                
                const osc = this.ctx.createOscillator();
                const gain = this.ctx.createGain();
                osc.type = 'sawtooth';
                osc.frequency.setValueAtTime(880, this.ctx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(440, this.ctx.currentTime + 0.35);
                
                gain.gain.setValueAtTime(0.2, this.ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, this.ctx.currentTime + 0.35);
                
                osc.connect(gain);
                gain.connect(this.ctx.destination);
                
                osc.start();
                osc.stop(this.ctx.currentTime + 0.4);
            } catch (e) {
                console.warn('Audio alert unavailable:', e);
            }
        }
    }
};

// Initialize cart badge on page load
document.addEventListener('DOMContentLoaded', () => {
    const updateCartBadges = () => {
        const badges = document.querySelectorAll('.cart-badge');
        const count = window.SmartPalao.cart.count();
        badges.forEach(b => {
            b.textContent = count;
            b.style.display = count > 0 ? 'inline-flex' : 'none';
        });
    };
    updateCartBadges();
    window.addEventListener('cart-updated', updateCartBadges);
});
