<a 
    href="{{ route('cart.index') }}" 
    class="hover:text-orange-400 relative"
    x-data="{ cartCount: 0 }"
    @cart-change.window="cartCount = $event.detail.count"
>
    🛒 Cart
    <template x-if="cartCount > 0">
        <span 
            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs"
            x-text="cartCount"
        ></span>
    </template>
</a> 