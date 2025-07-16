document.addEventListener('DOMContentLoaded', function() {
    const rawProductTotalInput = document.getElementById('raw-product-total');
    const voucherCodeInput = document.getElementById('voucher_code_input');
    const voucherCodeHiddenInput = document.getElementById('voucher_code_hidden');
    const applyVoucherBtn = document.getElementById('apply-voucher-btn');
    const voucherError = document.getElementById('voucher-error-message');
    const voucherSuccess = document.getElementById('voucher-success-message');
    const voucherSelect = document.getElementById('voucher_select');

    const addressSelect = document.getElementById('address_id');
    const courierSelect = document.getElementById('courier');
    const shippingServiceSelect = document.getElementById('shipping_service');
    const shippingCostInput = document.getElementById('shipping_cost');

    const productPriceSummary = document.getElementById('product-price-summary');
    const discountAmountSummary = document.getElementById('discount-amount-summary');
    const shippingCostSummary = document.getElementById('shipping-cost-summary');
    const finalTotalPriceDisplay = document.getElementById('final-total-price-display');

    const payButton = document.getElementById('pay-button');
    const checkoutForm = document.getElementById('checkout-form');

    const originalRawProductTotal = parseFloat(rawProductTotalInput.value);

    let currentDiscountAmount = 0;
    let currentShippingCost = 0;

    function formatRupiah(amount) {
        return parseFloat(amount).toLocaleString('id-ID', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function updateSummaryDisplay() {
        const finalTotal = originalRawProductTotal - currentDiscountAmount + currentShippingCost;

        productPriceSummary.textContent = formatRupiah(originalRawProductTotal);
        discountAmountSummary.textContent = formatRupiah(currentDiscountAmount);
        shippingCostSummary.textContent = formatRupiah(currentShippingCost);
        finalTotalPriceDisplay.textContent = formatRupiah(finalTotal);
    }

    async function fetchShippingOptions() {
        const selectedAddressId = addressSelect.value;
        const selectedCourier = courierSelect.value;

        shippingServiceSelect.innerHTML = '<option value="" disabled selected>Memuat...</option>';
        shippingServiceSelect.disabled = true;
        currentShippingCost = 0;
        shippingCostInput.value = 0;
        updateSummaryDisplay();

        if (!selectedAddressId || !selectedCourier) {
            shippingServiceSelect.innerHTML = '<option value="" disabled selected>Pilih alamat dan kurir terlebih dahulu</option>';
            return;
        }

        try {
            const response = await fetch('/get-shipping-cost', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    address_id: selectedAddressId,
                    courier: selectedCourier
                })
            });
            const data = await response.json();

            shippingServiceSelect.innerHTML = '';
            if (response.ok && data && data.length > 0) {
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.disabled = true;
                defaultOption.selected = true;
                defaultOption.textContent = 'Pilih Jenis Layanan';
                shippingServiceSelect.appendChild(defaultOption);

                data.forEach(service => {
                    const option = document.createElement('option');
                    option.value = service.service;
                    option.textContent = `${service.service} - ${service.description} - Rp${formatRupiah(service.cost)}`;
                    option.dataset.cost = service.cost;
                    shippingServiceSelect.appendChild(option);
                });
                shippingServiceSelect.disabled = false;
            } else if (data && data.error) {
                shippingServiceSelect.innerHTML = `<option value="" disabled selected>${data.error}</option>`;
            } else {
                shippingServiceSelect.innerHTML = `<option value="" disabled selected>Tidak ada layanan yang tersedia</option>`;
            }
        } catch (error) {
            console.error('Error fetching shipping options:', error);
            shippingServiceSelect.innerHTML = `<option value="" disabled selected>Gagal memuat layanan</option>`;
        } finally {
            updateSummaryDisplay();
        }
    }

    async function applyVoucher() {
        const voucherCode = voucherCodeInput.value.trim();

        voucherError.textContent = '';
        voucherSuccess.textContent = '';
        currentDiscountAmount = 0;
        voucherCodeHiddenInput.value = '';
        updateSummaryDisplay();

        if (voucherCode === "") {
            voucherError.textContent = 'Harap masukkan kode voucher.';
            return;
        }

        try {
            const response = await fetch('/voucher/check', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    voucher_code: voucherCode,
                    raw_product_total: originalRawProductTotal
                })
            });
            const data = await response.json();

            if (data.success) {
                currentDiscountAmount = data.discount_amount;
                voucherCodeHiddenInput.value = voucherCode;
                voucherSuccess.textContent = 'Voucher berhasil diterapkan! Diskon: Rp' + formatRupiah(data.discount_amount);
            } else {
                voucherError.textContent = data.message || 'Kode voucher tidak valid.';
                voucherCodeHiddenInput.value = '';
            }
        } catch (error) {
            console.error('Error applying voucher:', error);
            voucherError.textContent = 'Terjadi kesalahan saat memeriksa voucher.';
            voucherCodeHiddenInput.value = '';
        } finally {
            updateSummaryDisplay();
        }
    }

    // Event Listeners
    applyVoucherBtn.addEventListener('click', applyVoucher);

    voucherSelect.addEventListener('change', function() {
        voucherCodeInput.value = this.value;
        applyVoucher();
    });

    voucherCodeInput.addEventListener('input', function() {
        voucherSelect.value = '';
    });

    addressSelect.addEventListener('change', fetchShippingOptions);
    courierSelect.addEventListener('change', fetchShippingOptions);

    shippingServiceSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption && selectedOption.dataset.cost) {
            currentShippingCost = parseFloat(selectedOption.dataset.cost);
            shippingCostInput.value = currentShippingCost;
            document.getElementById('selected_courier').value = courierSelect.value;
            document.getElementById('selected_shipping_service').value = shippingServiceSelect.value;
        } else {
            currentShippingCost = 0;
            shippingCostInput.value = 0;
        }
        updateSummaryDisplay();
    });

    payButton.addEventListener('click', async function() {
        if (!addressSelect.value) {
            alert('Harap pilih alamat pengiriman.');
            return;
        }
        if (!courierSelect.value) {
            alert('Harap pilih kurir.');
            return;
        }
        if (!shippingServiceSelect.value) {
            alert('Harap pilih jenis layanan pengiriman.');
            return;
        }

        document.getElementById('selected_courier').value = courierSelect.value;
        document.getElementById('selected_shipping_service').value = shippingServiceSelect.value;

        try {
            const formData = new FormData(checkoutForm);
            const response = await fetch(checkoutForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });
            const data = await response.json();

            if (data.success && data.snapToken) {
                snap.pay(data.snapToken, {
                    onSuccess: async function(result) {
                        try {
                            const response = await fetch('/status/update-payment', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                },
                                body: JSON.stringify({
                                    order_id: result.order_id,
                                    payment_status: result.transaction_status
                                }),
                            });

                            const data = await response.json();

                            if (response.ok) {
                                window.location.href = '/orders/history';
                            } else {
                                alert('Gagal update status pembayaran: ' + (data.message || 'Error tidak dikenal'));
                            }
                        } catch (error) {
                            console.error('Error update status payment:', error);
                            alert('Terjadi kesalahan saat mengupdate status pembayaran.');
                        }
                    },
                    onPending: async function(result) {
                        try {
                            const notifyResponse = await fetch('/order/notify-created', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                },
                                body: JSON.stringify({
                                    order_id: result.order_id
                                }),
                            });

                            if (!notifyResponse.ok) {
                                const errorData = await notifyResponse.json();
                                console.error('Gagal kirim notifikasi order created:', errorData.message || notifyResponse.statusText);
                            }
                        } catch (error) {
                            console.error('Error notify order created:', error);
                        }

                        window.location.href = '/orders/pending';
                    },
                    onError: function(result) {
                        console.error(result);
                        alert('Pembayaran Gagal: ' + result.status_message);
                    },
                    onClose: async function() {
                        try {
                            await fetch('/cancel-order', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                },
                                body: JSON.stringify({
                                    checkout_ids: data.checkoutIds
                                }),
                            });
                        } catch (error) {
                            console.error('Gagal menghapus order sementara:', error);
                        }

                        alert('Kamu menutup pembayaran sebelum memilih metode. Order dibatalkan.');
                        window.location.href = '/';
                    }
                });
            } else {
                alert('Gagal memproses pesanan: ' + (data.message || 'Terjadi kesalahan tidak dikenal.'));
                if (data.errors) {
                    let errorMessages = '';
                    for (const key in data.errors) {
                        errorMessages += data.errors[key].join('\n') + '\n';
                    }
                    alert('Detail Error:\n' + errorMessages);
                }
            }
        } catch (error) {
            console.error('Error submitting checkout form:', error);
            alert('Terjadi kesalahan saat membuat transaksi. Silakan coba lagi.');
        }
    });

    // Initial calls
    updateSummaryDisplay();
    if (addressSelect.value && courierSelect.value) {
        fetchShippingOptions();
    }
});
