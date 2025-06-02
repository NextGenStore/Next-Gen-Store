import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse'
import {get, post} from "./http.js";

Alpine.plugin(collapse)

window.Alpine = Alpine;

// Global function to add items to cart
window.addToCart = function(productId, quantity = 1) {
    const url = `/cart/add/${productId}`;
    post(url, {quantity})
        .then(result => {
            window.dispatchEvent(new CustomEvent('cart-change', {detail: {count: result.count}}));
            window.dispatchEvent(new CustomEvent('notify', {
                detail: {
                    message: "The item was added into the cart"
                }
            }));
        })
        .catch(response => {
            console.log(response);
            window.dispatchEvent(new CustomEvent('notify', {
                detail: {
                    message: response.message || 'Server Error. Please try again.',
                    type: 'error'
                }
            }));
        });
};

document.addEventListener("alpine:init", async () => {

    Alpine.data("toast", () => ({
        visible: false,
        delay: 5000,
        percent: 0,
        interval: null,
        timeout: null,
        message: null,
        type: null,
        close() {
            this.visible = false;
            clearInterval(this.interval);
        },
        show(message, type = 'success') {
            this.visible = true;
            this.message = message;
            this.type = type;

            if (this.interval) {
                clearInterval(this.interval);
                this.interval = null;
            }
            if (this.timeout) {
                clearTimeout(this.timeout);
                this.timeout = null;
            }

            this.timeout = setTimeout(() => {
                this.visible = false;
                this.timeout = null;
            }, this.delay);
            const startDate = Date.now();
            const futureDate = Date.now() + this.delay;
            this.interval = setInterval(() => {
                const date = Date.now();
                this.percent = ((date - startDate) * 100) / (futureDate - startDate);
                if (this.percent >= 100) {
                    clearInterval(this.interval);
                    this.interval = null;
                }
            }, 30);
        },
    }));

    Alpine.data("productItem", (product) => {
        return {
            product,
            addToCart(quantity = 1) {
                post(this.product.addToCartUrl, {quantity})
                    .then(result => {
                        this.$dispatch('cart-change', {count: result.count})
                        this.$dispatch("notify", {
                            message: "The item was added into the cart",
                        });
                    })
                    .catch(response => {
                        console.log(response);
                        this.$dispatch('notify', {
                            message: response.message || 'Server Error. Please try again.',
                            type: 'error'
                        })
                    })
            },
            removeItemFromCart() {
                post(this.product.removeUrl)
                    .then(result => {
                        this.$dispatch("notify", {
                            message: "The item was removed from cart",
                        });
                        this.$dispatch('cart-change', {count: result.count})
                        this.cartItems = this.cartItems.filter(p => p.id !== product.id)
                    })
            },
            changeQuantity() {
                post(this.product.updateQuantityUrl, {quantity: product.quantity})
                    .then(result => {
                        this.$dispatch('cart-change', {count: result.count})
                        this.$dispatch("notify", {
                            message: "The item quantity was updated",
                        });
                    })
                    .catch(response => {
                        this.$dispatch('notify', {
                            message: response.message || 'Server Error. Please try again.',
                            type: 'error'
                        })
                    })
            },
        };
    });
});


// Initialize cart count on page load
window.addEventListener('DOMContentLoaded', async () => {
    try {
        // Only fetch cart count if user is logged in
        if (document.querySelector('meta[name="auth-check"]')) {
            const response = await fetch('/cart/count');
            if (response.ok) {
                const data = await response.json();
                window.dispatchEvent(new CustomEvent('cart-change', {detail: {count: data.count}}));
            }
        }
    } catch (error) {
        console.error('Failed to fetch cart count:', error);
    }
});

Alpine.start();
