@extends('layouts.app')

@section('title', 'Manage Orders - Shyness')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-12 border-b border-thin dark:border-gray-800 pb-6 flex justify-between items-end transition-colors">
        <div>
            <h1 class="text-3xl font-serif font-medium text-primary dark:text-white mb-2 transition-colors">Order Management</h1>
            <p class="text-xs tracking-widest uppercase text-secondary">Dashboard</p>
        </div>
    </div>

    <div class="bg-gray-50 dark:bg-[#151515] border border-thin dark:border-gray-800 transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-thin dark:border-gray-800 text-xs tracking-widest uppercase text-secondary">
                        <th class="p-6 font-medium">Invoice</th>
                        <th class="p-6 font-medium">Customer</th>
                        <th class="p-6 font-medium">Total</th>
                        <th class="p-6 font-medium">Status</th>
                        <th class="p-6 font-medium">Date</th>
                        <th class="p-6 font-medium text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-light text-primary dark:text-gray-300">
                    @foreach ($orders as $order)
                        <tr class="border-b border-gray-200 dark:border-gray-800 last:border-0 hover:bg-white dark:hover:bg-gray-900 transition-colors">
                            <td class="p-6 font-mono text-xs">{{ $order->invoice_number }}</td>
                            <td class="p-6 text-primary dark:text-white">{{ $order->user->name }}</td>
                            <td class="p-6 text-primary dark:text-white">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td class="p-6">
                                <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="inline status-update-form">
                                    @csrf @method('PATCH')
                                    <div class="relative">
                                        <select name="status" class="status-select appearance-none bg-transparent border border-thin dark:border-gray-700 py-2 pl-4 pr-10 text-xs tracking-widest uppercase focus:outline-none focus:border-black dark:focus:border-white cursor-pointer w-full text-secondary dark:text-gray-300 dark:bg-primary transition-colors">
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-secondary">
                                            <i class="fa-solid fa-chevron-down text-[10px]"></i>
                                        </div>
                                    </div>
                                </form>
                            </td>
                            <td class="p-6 text-xs text-secondary">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="p-6 text-center">
                                <a href="{{ route('orders.show', $order->id) }}" target="_blank" class="text-xs tracking-widest uppercase text-secondary hover:text-black dark:hover:text-white transition-colors hover:underline underline-offset-4">
                                    Print Receipt
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($orders->empty())
            <div class="p-12 text-center text-sm font-light text-secondary">
                No orders found.
            </div>
        @endif
        
        @if($orders->hasPages())
            <div class="p-6 border-t border-thin dark:border-gray-800 bg-white dark:bg-primary transition-colors">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function() {
            const form = this.closest('.status-update-form');
            const url = form.action;
            const formData = new FormData(form);

            const originalClasses = this.className;
            this.classList.add('opacity-50', 'pointer-events-none');

            // Find or create a toast notification element
            let notification = document.getElementById('status-notification');
            if (!notification) {
                notification = document.createElement('div');
                notification.id = 'status-notification';
                notification.className = 'fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg text-sm font-medium tracking-widest uppercase text-white transition-opacity duration-300 opacity-0 z-50';
                document.body.appendChild(notification);
            }

            fetch(url, {
                method: 'POST', // Contains _method=PATCH in formData
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if(!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                this.classList.remove('opacity-50', 'pointer-events-none');
                
                if(data.success) {
                    notification.textContent = 'Status berhasil diperbarui';
                    notification.classList.remove('bg-red-500');
                    notification.classList.add('bg-green-500', 'opacity-100');
                } else {
                    notification.textContent = data.error || 'Terjadi kesalahan saat memperbarui status';
                    notification.classList.remove('bg-green-500');
                    notification.classList.add('bg-red-500', 'opacity-100');
                }
                
                setTimeout(() => {
                    notification.classList.remove('opacity-100');
                }, 3000);
            })
            .catch(error => {
                this.classList.remove('opacity-50', 'pointer-events-none');
                notification.textContent = 'Gagal menghubungi server';
                notification.classList.remove('bg-green-500');
                notification.classList.add('bg-red-500', 'opacity-100');
                
                setTimeout(() => {
                    notification.classList.remove('opacity-100');
                }, 3000);
            });
        });
    });
</script>
@endpush
