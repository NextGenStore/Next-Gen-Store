<x-app-layout>
    <div class="container lg:w-2/3 xl:w-2/3 mx-auto">
        <h1 class="text-3xl font-bold mb-6">Checkout</h1>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                
                <div class="border-t border-gray-300 pt-4">
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">Subtotal</span>
                        <span id="cartTotal" class="text-lg">${{ number_format(\App\Models\Cart::where('user_id', Auth::id())->join('products', 'cards.product_id', '=', 'products.id')->sum(\DB::raw('products.price * cards.quantity')), 2) }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">Shipping</span>
                        <span class="text-lg">$5.00</span>
                    </div>
                    <div class="flex justify-between font-bold text-xl mt-4 pt-4 border-t border-gray-300">
                        <span>Total</span>
                        <span>${{ number_format(\App\Models\Cart::where('user_id', Auth::id())->join('products', 'cards.product_id', '=', 'products.id')->sum(\DB::raw('products.price * cards.quantity')) + 5, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Shipping Information</h2>
                <form>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="first_name" class="block mb-2 text-sm font-medium">First Name</label>
                            <input type="text" id="first_name" class="w-full rounded border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                        </div>
                        <div>
                            <label for="last_name" class="block mb-2 text-sm font-medium">Last Name</label>
                            <input type="text" id="last_name" class="w-full rounded border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="address" class="block mb-2 text-sm font-medium">Address</label>
                        <input type="text" id="address" class="w-full rounded border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label for="city" class="block mb-2 text-sm font-medium">City</label>
                            <input type="text" id="city" class="w-full rounded border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                        </div>
                        <div>
                            <label for="state" class="block mb-2 text-sm font-medium">State</label>
                            <input type="text" id="state" class="w-full rounded border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                        </div>
                        <div>
                            <label for="zip" class="block mb-2 text-sm font-medium">ZIP Code</label>
                            <input type="text" id="zip" class="w-full rounded border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                        </div>
                    </div>
                </form>
            </div>

            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Payment Method</h2>
                <div class="flex space-x-4">
                    <div class="border p-4 rounded-lg flex-1 text-center cursor-pointer hover:border-orange-500">
                        <div class="text-3xl mb-2">💳</div>
                        <div>Credit Card</div>
                    </div>
                    <div class="border p-4 rounded-lg flex-1 text-center cursor-pointer hover:border-orange-500">
                        <div class="text-3xl mb-2">🏦</div>
                        <div>Bank Transfer</div>
                    </div>
                    <div class="border p-4 rounded-lg flex-1 text-center cursor-pointer hover:border-orange-500">
                        <div class="text-3xl mb-2">💰</div>
                        <div>Cash on Delivery</div>
                    </div>
                </div>
            </div>

            <button class="btn-primary w-full py-3 text-lg">
                Complete Order
            </button>
        </div>
    </div>
</x-app-layout>
