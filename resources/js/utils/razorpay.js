// Razorpay configuration and payment handling
export const razorpayConfig = {
    // Replace with your actual Razorpay key
    key: 'rzp_test_RFSBxMsJNmTtzD', // Test key - replace with your actual key
    currency: 'INR',
    name: 'Vidsmotion',
    description: 'Video Generation Service',
    image: '/logo.png', // Add your logo path
    theme: {
        color: '#000000'
    }
};

export const createRazorpayOrder = async (amount, planName) => {
    try {
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            throw new Error('CSRF token not found. Please refresh the page.');
        }

        // Validate inputs
        if (!amount || amount <= 0) {
            throw new Error('Invalid amount provided');
        }
        if (!planName || planName.trim() === '') {
            throw new Error('Plan name is required');
        }

        console.log('Creating Razorpay order:', { amount, planName });

        // This should call your backend API to create an order
        const response = await fetch('/api/create-razorpay-order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                amount: amount * 100, // Razorpay expects amount in paise
                currency: 'INR',
                plan: planName
            })
        });

        console.log('Response status:', response.status);

        if (!response.ok) {
            const errorData = await response.json().catch(() => ({}));
            console.error('Order creation failed:', errorData);
            throw new Error(errorData.error || `HTTP ${response.status}: Failed to create order`);
        }

        const order = await response.json();
        console.log('Order created successfully:', order);
        return order;
    } catch (error) {
        console.error('Error creating Razorpay order:', error);
        throw error;
    }
};

export const openRazorpayPayment = (order, planName, onSuccess, onError) => {
    const options = {
        key: razorpayConfig.key,
        amount: order.amount,
        currency: order.currency,
        name: razorpayConfig.name,
        description: `${razorpayConfig.description} - ${planName}`,
        image: razorpayConfig.image,
        order_id: order.id,
        theme: razorpayConfig.theme,
        handler: function (response) {
            // Payment successful
            console.log('Payment successful:', response);
            onSuccess(response);
        },
        prefill: {
            name: '', // You can prefill user details if available
            email: '',
            contact: ''
        },
        notes: {
            plan: planName,
            order_id: order.id
        },
        modal: {
            ondismiss: function() {
                // Payment modal closed
                console.log('Payment modal closed');
            }
        }
    };

    const rzp = new Razorpay(options);
    rzp.on('payment.failed', function (response) {
        console.error('Payment failed:', response.error);
        onError(response.error);
    });

    rzp.open();
};

// Helper function to handle payment success
export const handlePaymentSuccess = async (response, planName) => {
    try {
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            throw new Error('CSRF token not found. Please refresh the page.');
        }

        console.log('Processing payment success:', response);

        // Call backend to verify and process payment
        const verifyResponse = await fetch('/api/payment-success', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                razorpay_payment_id: response.razorpay_payment_id,
                razorpay_order_id: response.razorpay_order_id,
                razorpay_signature: response.razorpay_signature,
                plan: planName
            })
        });

        if (!verifyResponse.ok) {
            const errorData = await verifyResponse.json().catch(() => ({}));
            throw new Error(errorData.error || 'Payment verification failed');
        }

        const result = await verifyResponse.json();
        console.log('Payment verification successful:', result);
        return result;
    } catch (error) {
        console.error('Error processing payment success:', error);
        throw error;
    }
};

// Load Razorpay script dynamically
export const loadRazorpayScript = () => {
    return new Promise((resolve, reject) => {
        if (window.Razorpay) {
            console.log('Razorpay already loaded');
            resolve();
            return;
        }

        console.log('Loading Razorpay script...');
        const script = document.createElement('script');
        script.src = 'https://checkout.razorpay.com/v1/checkout.js';
        script.onload = () => {
            console.log('Razorpay script loaded successfully');
            resolve();
        };
        script.onerror = (error) => {
            console.error('Failed to load Razorpay script:', error);
            reject(new Error('Failed to load Razorpay script. Please check your internet connection.'));
        };
        document.body.appendChild(script);
    });
};
