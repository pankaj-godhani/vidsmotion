# Razorpay: Plan ID vs Subscription ID in Auto-Renewal

## 🔍 **Key Difference:**

### **Plan ID** (`plan_xxxxx`)
- **What it is**: A template/blueprint for a subscription
- **Purpose**: Defines the pricing, billing cycle, and features
- **Usage**: Used to CREATE new subscriptions
- **Example**: `plan_RGmQmqwhgJIrHe` (Standard plan template)

### **Subscription ID** (`sub_xxxxx`)
- **What it is**: An actual subscription instance for a specific customer
- **Purpose**: Represents a customer's active subscription
- **Usage**: Used to MANAGE existing subscriptions
- **Example**: `sub_RGmRftCSxQxz1X` (Your actual subscription)

---

## 🎯 **In Auto-Renewal Process:**

### **✅ CORRECT Approach:**
```php
// 1. Get existing subscription
$subscription = $api->subscription->fetch('sub_RGmRftCSxQxz1X');

// 2. Extract PLAN ID from subscription
$planId = $subscription->plan_id; // This gives us 'plan_RGmQmqwhgJIrHe'

// 3. Create NEW subscription using PLAN ID
$newSubscription = $api->subscription->create([
    'plan_id' => $planId, // ✅ Use PLAN ID
    'customer_id' => $customerId,
    'total_count' => 1,
    // ... other parameters
]);
```

### **❌ WRONG Approach:**
```php
// DON'T do this:
$newSubscription = $api->subscription->create([
    'plan_id' => 'sub_RGmRftCSxQxz1X', // ❌ This is a SUBSCRIPTION ID, not PLAN ID
    // ... other parameters
]);
```

---

## 📊 **Your Current Setup:**

### **Plan IDs (Templates):**
- **Standard (7 days)**: `plan_RGmQmqwhgJIrHe` - ₹50
- **Pro Monthly**: `plan_RGmR6HlQ7mW0pP` - ₹100
- **Premier Yearly**: `plan_RGmRITENfJFYMj` - ₹1100

### **Subscription IDs (Your Active Subscriptions):**
- **Your Standard Subscription**: `sub_RGmRftCSxQxz1X`
- **Your Pro Monthly Subscription**: `sub_RGmS1mvLpdkjej`
- **Your Premier Yearly Subscription**: `sub_RGmSOxU11fg088`

---

## 🔄 **Auto-Renewal Flow:**

### **Step 1: Check Expiring Subscriptions**
```php
// Find subscriptions expiring soon
$expiringSubscriptions = Payment::where('is_active', true)
    ->where('auto_renew', true)
    ->where('subscription_end', '<=', now()->addDay())
    ->whereNotNull('razorpay_subscription_id')
    ->get();
```

### **Step 2: Get Plan ID from Existing Subscription**
```php
// Fetch subscription from Razorpay
$razorpaySubscription = $api->subscription->fetch($subscription->razorpay_subscription_id);

// Extract plan ID
$planId = $razorpaySubscription->plan_id; // This is the PLAN ID
```

### **Step 3: Create New Subscription**
```php
// Create new subscription using PLAN ID
$newSubscription = $api->subscription->create([
    'plan_id' => $planId, // ✅ Use PLAN ID
    'customer_id' => $customerId,
    'total_count' => 1,
    'start_at' => $oldSubscriptionEnd->timestamp,
    'expire_by' => $oldSubscriptionEnd->addDays(7)->timestamp,
]);
```

---

## 🎛️ **What You Need to Enter:**

### **For Auto-Renewal Setup:**
- **✅ Use**: Plan IDs (`plan_xxxxx`)
- **❌ Don't use**: Subscription IDs (`sub_xxxxx`)

### **For Managing Existing Subscriptions:**
- **✅ Use**: Subscription IDs (`sub_xxxxx`)
- **❌ Don't use**: Plan IDs (`plan_xxxxx`)

---

## 🔧 **In Your Database:**

### **Payments Table:**
```sql
-- This stores the SUBSCRIPTION ID (not plan ID)
razorpay_subscription_id = 'sub_RGmRftCSxQxz1X'
```

### **Plan Details Configuration:**
```php
// This stores the SUBSCRIPTION ID for reference
'Standard' => [
    'subscription_id' => 'sub_RGmRftCSxQxz1X' // This is actually a subscription ID
]
```

**Note**: The naming is confusing, but `subscription_id` in our config actually refers to the subscription ID, not plan ID.

---

## 🚀 **Corrected Implementation:**

The auto-renewal process now correctly:
1. **Fetches** the existing subscription using subscription ID
2. **Extracts** the plan ID from the subscription
3. **Creates** a new subscription using the plan ID
4. **Stores** the new subscription ID in the database

---

## 💡 **Summary:**

- **Plan ID**: Template for creating subscriptions (use for auto-renewal)
- **Subscription ID**: Actual subscription instance (use for management)
- **Auto-renewal**: Uses Plan ID to create new subscriptions
- **Your system**: Now correctly handles both IDs in the auto-renewal process
