# Payment Debug Guide

## Common Issues and Solutions

### 1. Check Browser Console
Open your browser's Developer Tools (F12) and check the Console tab for any error messages when you click "Subscribe Standard".

### 2. Check Network Tab
In Developer Tools, go to the Network tab and look for:
- `/api/create-razorpay-order` request
- Check if it returns 200 status or an error

### 3. Environment Configuration
Make sure your `.env` file has:
```env
RAZORPAY_KEY_ID=rzp_test_RFSBxMsJNmTtzD
RAZORPAY_KEY_SECRET=your_actual_secret_here
```

### 4. Common Error Messages

#### "CSRF token not found"
- **Solution**: Refresh the page and try again
- **Cause**: The CSRF token meta tag was missing (now fixed)

#### "Razorpay configuration missing"
- **Solution**: Add Razorpay credentials to your `.env` file
- **Cause**: Missing RAZORPAY_KEY_ID or RAZORPAY_KEY_SECRET

#### "Failed to create order"
- **Solution**: Check your Razorpay credentials and internet connection
- **Cause**: Backend API call failed

#### "Failed to load Razorpay script"
- **Solution**: Check your internet connection
- **Cause**: Cannot load Razorpay's checkout script

### 5. Testing Steps

1. **Open Browser Console** (F12)
2. **Click "Subscribe Standard"**
3. **Check Console Messages**:
   - Should see: "Starting payment process..."
   - Should see: "Loading Razorpay script..."
   - Should see: "Razorpay script loaded successfully"
   - Should see: "Creating Razorpay order: {amount: 50, planName: 'Standard'}"
   - Should see: "Response status: 200"
   - Should see: "Order created successfully: {...}"

4. **If any step fails**, check the error message

### 6. Backend Logs
Check your Laravel logs at `storage/logs/laravel.log` for any backend errors.

### 7. Quick Test
Try this in your browser console:
```javascript
// Test if Razorpay script loads
fetch('https://checkout.razorpay.com/v1/checkout.js')
  .then(response => console.log('Razorpay script accessible:', response.ok))
  .catch(error => console.error('Razorpay script not accessible:', error));
```

### 8. Manual Order Creation Test
Test the backend API directly:
```javascript
fetch('/api/create-razorpay-order', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
  },
  body: JSON.stringify({
    amount: 5000,
    currency: 'INR',
    plan: 'Standard'
  })
})
.then(response => response.json())
.then(data => console.log('Order created:', data))
.catch(error => console.error('Error:', error));
```

## What to Check Next

1. **Browser Console** - Look for specific error messages
2. **Network Tab** - Check if API calls are successful
3. **Environment Variables** - Ensure Razorpay credentials are set
4. **Laravel Logs** - Check for backend errors

Let me know what specific error message you see in the console!
