# Razorpay Integration Setup

## 1. Environment Configuration

Add the following to your `.env` file:

```env
# Razorpay Configuration
RAZORPAY_KEY_ID=rzp_test_your_test_key_id_here
RAZORPAY_KEY_SECRET=your_test_key_secret_here
RAZORPAY_WEBHOOK_SECRET=your_webhook_secret_here
```

## 2. Get Razorpay Credentials

1. Go to [Razorpay Dashboard](https://dashboard.razorpay.com/)
2. Sign up or log in to your account
3. Go to Settings > API Keys
4. Generate API Keys for Test Mode (for development)
5. Copy the Key ID and Key Secret to your `.env` file

## 3. Update Razorpay Configuration

In `resources/js/utils/razorpay.js`, update the key:

```javascript
export const razorpayConfig = {
    key: 'rzp_test_your_actual_key_here', // Replace with your actual key
    // ... rest of the configuration
};
```

## 4. Test the Integration

1. Start your Laravel development server
2. Go to the Pricing page
3. Click "Subscribe Standard" button
4. The Razorpay payment modal should open
5. Use test card numbers for testing:
   - Card Number: 4111 1111 1111 1111
   - Expiry: Any future date
   - CVV: Any 3 digits
   - Name: Any name

## 5. Production Setup

For production:

1. Switch to Live Mode in Razorpay Dashboard
2. Update your `.env` with live credentials
3. Update the key in `razorpay.js` with live key
4. Set up webhooks for payment verification

## 6. Features Implemented

- ✅ Razorpay order creation
- ✅ Payment modal integration
- ✅ Payment success/failure handling
- ✅ Loading states
- ✅ Error handling
- ✅ Payment verification

## 7. Next Steps

You may want to:
- Add user subscription management
- Implement webhook handling
- Add payment history
- Create subscription renewal logic
- Add email notifications
