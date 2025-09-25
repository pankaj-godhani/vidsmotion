# Mobile Payment API Documentation

## Overview
This API provides payment functionality specifically designed for mobile applications. It uses Razorpay for payment processing and requires Sanctum authentication.

## Base URL
```
http://127.0.0.1:8000/api/mobile/payment
```

## Authentication
All endpoints require Sanctum authentication. Include the Bearer token in the Authorization header:
```
Authorization: Bearer {your_token}
```

## Endpoints

### 1. Get Available Plans
**GET** `/plans`

Returns all available subscription plans with details.

#### Response
```json
{
  "success": true,
  "data": {
    "plans": [
      {
        "id": "Standard",
        "name": "Standard Plan",
        "description": "7 days access with basic features",
        "amount": 99.00,
        "currency": "INR",
        "duration_days": 7,
        "features": [
          "Basic video generation",
          "Standard quality output",
          "7 days access",
          "Email support"
        ]
      },
      {
        "id": "Pro Monthly",
        "name": "Pro Monthly Plan",
        "description": "Monthly access with premium features",
        "amount": 299.00,
        "currency": "INR",
        "duration_days": 30,
        "features": [
          "Premium video generation",
          "HD quality output",
          "30 days access",
          "Priority support",
          "Advanced templates"
        ]
      },
      {
        "id": "Premier Yearly",
        "name": "Premier Yearly Plan",
        "description": "Yearly access with all features",
        "amount": 3999.00,
        "currency": "INR",
        "duration_days": 365,
        "features": [
          "Unlimited video generation",
          "4K quality output",
          "365 days access",
          "24/7 premium support",
          "All templates",
          "API access"
        ]
      }
    ],
    "razorpay_key": "rzp_test_RFSBxMsJNmTtzD"
  }
}
```

### 2. Create Payment Order
**POST** `/create-order`

Creates a Razorpay order for payment processing.

#### Request Body
```json
{
  "amount": 9900,
  "currency": "INR",
  "plan": "Standard"
}
```

#### Parameters
- `amount` (integer, required): Amount in paise (₹99 = 9900 paise)
- `currency` (string, required): Currency code (only "INR" supported)
- `plan` (string, required): Plan ID ("Standard", "Pro Monthly", "Premier Yearly")

#### Response
```json
{
  "success": true,
  "data": {
    "order_id": "order_ABC123",
    "amount": 9900,
    "currency": "INR",
    "receipt": "mobile_1234567890_Standard_1",
    "key_id": "rzp_test_RFSBxMsJNmTtzD",
    "plan": "Standard",
    "plan_details": {
      "type": "Standard",
      "amount": 99.00,
      "duration_days": 7,
      "subscription_id": "sub_RLmN8cDJVph0DQ"
    }
  }
}
```

### 3. Verify Payment
**POST** `/verify-payment`

Verifies and processes the payment after Razorpay returns success.

#### Request Body
```json
{
  "razorpay_payment_id": "pay_ABC123",
  "razorpay_order_id": "order_ABC123",
  "razorpay_signature": "signature_hash",
  "plan": "Standard"
}
```

#### Parameters
- `razorpay_payment_id` (string, required): Payment ID from Razorpay
- `razorpay_order_id` (string, required): Order ID from Razorpay
- `razorpay_signature` (string, required): Signature from Razorpay
- `plan` (string, required): Plan ID

#### Response
```json
{
  "success": true,
  "message": "Payment successful",
  "data": {
    "payment_id": 123,
    "plan": "Standard",
    "amount": 99.00,
    "currency": "INR",
    "subscription_start": "2024-01-15T10:30:00Z",
    "subscription_end": "2024-01-22T10:30:00Z",
    "credits_added": 50,
    "user_credits": 150
  }
}
```

### 4. Get Payment History
**GET** `/history`

Returns the user's payment history with pagination.

#### Query Parameters
- `page` (integer, optional): Page number (default: 1)
- `per_page` (integer, optional): Items per page (default: 20, max: 50)

#### Response
```json
{
  "success": true,
  "data": {
    "payments": [
      {
        "id": 123,
        "plan_name": "Standard",
        "amount": 99.00,
        "currency": "INR",
        "status": "completed",
        "payment_date": "2024-01-15T10:30:00Z",
        "subscription_start": "2024-01-15T10:30:00Z",
        "subscription_end": "2024-01-22T10:30:00Z",
        "is_active": true,
        "receipt_id": "mobile_1234567890_Standard"
      }
    ],
    "pagination": {
      "current_page": 1,
      "per_page": 20,
      "total": 1,
      "last_page": 1
    }
  }
}
```

## Error Responses

### Authentication Error
```json
{
  "error": "Authentication required"
}
```
**Status Code:** 401

### Validation Error
```json
{
  "error": "Validation failed",
  "details": {
    "amount": ["The amount field is required."],
    "plan": ["The plan field is required."]
  }
}
```
**Status Code:** 422

### Payment Verification Error
```json
{
  "error": "Invalid payment signature"
}
```
**Status Code:** 400

### Server Error
```json
{
  "error": "Internal server error"
}
```
**Status Code:** 500

## Mobile Integration Example

### 1. Get Plans
```javascript
const response = await fetch('/api/mobile/payment/plans', {
  headers: {
    'Authorization': 'Bearer ' + token,
    'Accept': 'application/json'
  }
});
const plans = await response.json();
```

### 2. Create Order
```javascript
const orderResponse = await fetch('/api/mobile/payment/create-order', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer ' + token,
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    amount: 9900, // ₹99 in paise
    currency: 'INR',
    plan: 'Standard'
  })
});
const order = await orderResponse.json();
```

### 3. Process Payment with Razorpay
```javascript
const options = {
  key: order.data.key_id,
  amount: order.data.amount,
  currency: order.data.currency,
  order_id: order.data.order_id,
  name: 'Vidsmotion',
  description: 'Standard Plan',
  handler: async function(response) {
    // Verify payment
    const verifyResponse = await fetch('/api/mobile/payment/verify-payment', {
      method: 'POST',
      headers: {
        'Authorization': 'Bearer ' + token,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        razorpay_payment_id: response.razorpay_payment_id,
        razorpay_order_id: response.razorpay_order_id,
        razorpay_signature: response.razorpay_signature,
        plan: 'Standard'
      })
    });
    const result = await verifyResponse.json();
    console.log('Payment successful:', result);
  }
};
const rzp = new Razorpay(options);
rzp.open();
```

### 4. Get Payment History
```javascript
const historyResponse = await fetch('/api/mobile/payment/history?page=1&per_page=10', {
  headers: {
    'Authorization': 'Bearer ' + token,
    'Accept': 'application/json'
  }
});
const history = await historyResponse.json();
```

## Notes

1. **Amount Format**: All amounts are in paise (₹1 = 100 paise)
2. **Authentication**: All endpoints require valid Sanctum token
3. **Plan IDs**: Use exact plan IDs: "Standard", "Pro Monthly", "Premier Yearly"
4. **Error Handling**: Always check response status and handle errors appropriately
5. **Logging**: All payment activities are logged for debugging and audit purposes
6. **Credits**: Successful payments automatically add credits to user account
7. **Subscription**: Payment creates an active subscription with start/end dates

## Testing

Use the following test credentials for Razorpay:
- **Test Card**: 4111 1111 1111 1111
- **CVV**: Any 3 digits
- **Expiry**: Any future date
- **Name**: Any name
