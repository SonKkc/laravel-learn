<div x-show="confirmOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="fixed inset-0 bg-black opacity-40"></div>
    <div class="bg-white rounded-lg shadow-lg p-6 z-10 max-w-md w-full">
        <h3 class="text-lg font-semibold mb-2">Xác nhận</h3>
        <p class="text-sm text-gray-600 mb-4">Bạn có chắc muốn thực hiện hành động này với sản phẩm không?</p>
        <div class="flex items-center justify-end gap-2">
            <button x-on:click.prevent="confirmOpen=false" class="px-4 py-2 rounded border">Huỷ</button>
            <button x-bind:disabled="processing" x-on:click.prevent="confirmProceed($event)" class="px-4 py-2 rounded bg-main-red text-white inline-flex items-center gap-2">
                <svg x-show="processing" class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                <span>Xác nhận</span>
            </button>
        </div>
    </div>
</div>
