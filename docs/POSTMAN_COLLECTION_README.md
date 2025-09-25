# Vidsmotion API Postman Collection

## Overview
This Postman collection contains all the API endpoints for the Vidsmotion application, including authentication, payments, explore videos, mobile APIs, and admin functions.

## Files Included
- `Vidsmotion_API_Collection.postman_collection.json` - Main API collection
- `Vidsmotion_Environment.postman_environment.json` - Environment variables
- `POSTMAN_COLLECTION_README.md` - This documentation

## Setup Instructions

### 1. Import Collection
1. Open Postman
2. Click "Import" button
3. Select `Vidsmotion_API_Collection.postman_collection.json`
4. Click "Import"

### 2. Import Environment
1. In Postman, click the gear icon (⚙️) in the top right
2. Click "Import"
3. Select `Vidsmotion_Environment.postman_environment.json`
4. Click "Import"
5. Select the "Vidsmotion Environment" from the environment dropdown

### 3. Configure Environment Variables
Update the following variables in your environment:

| Variable | Description | Example Value |
|----------|-------------|---------------|
| `base_url` | API base URL | `http://127.0.0.1:8000` |
| `test_email` | Test user email | `test@example.com` |
| `test_password` | Test user password | `password123` |
| `test_name` | Test user name | `Test User` |
| `razorpay_key` | Razorpay test key | `rzp_test_RFSBxMsJNmTtzD` |

## Collection Structure

### 🔐 Authentication
- **Register User** - Create new user account
- **Login User** - Authenticate and get token (auto-saves token)
- **Logout User** - Invalidate current token
- **Get User Profile** - Get current user details
- **Update User Profile** - Update user information

### 🎬 Explore Videos API
- **Get All Explore Videos** - List public videos with pagination
- **Get Explore Video by ID** - Get specific video details
- **Create Explore Video** - Upload new video (authenticated)
- **Update Explore Video** - Modify video details (authenticated)
- **Delete Explore Video** - Remove video (authenticated)
- **Like/Unlike Video** - Toggle video like status
- **Get My Videos** - Get user's own videos (authenticated)

### 📱 Mobile Payment API
- **Get Available Plans** - List all subscription plans
- **Create Payment Order** - Generate Razorpay order
- **Verify Payment** - Process payment verification
- **Get Payment History** - User's payment history

### 💳 Web Payment API
- **Create Razorpay Order** - Web payment order creation
- **Payment Success** - Process successful payment
- **Create Subscription** - Create recurring subscription

### 👤 User Management
- **Get User Stats** - User statistics and metrics
- **Get User Uploads** - User's uploaded files
- **Get User Credits** - Current credit balance
- **Get My Files** - User's generated videos
- **Get My Files Stats** - File statistics

### 📁 File Upload & Processing
- **Upload Image** - Upload reference image
- **Upload Video** - Submit video generation request
- **Get Upload Status** - Check processing status
- **Get Upload Result** - Download completed video

### 👨‍💼 Admin API
- **Get Admin Stats** - System statistics
- **Get Admin Activity** - System activity log
- **Get Admin Users** - User management
- **Get Admin Uploads** - Upload management
- **Toggle User Status** - Enable/disable users
- **Retry Upload** - Retry failed uploads

### 🔗 Webhooks
- **Piapi Webhook** - External service webhook

## Usage Guide

### 1. Authentication Flow
1. **Register User** - Create a new account
2. **Login User** - Get authentication token (automatically saved)
3. Use authenticated endpoints with the saved token

### 2. Testing Payment Flow
1. **Get Available Plans** - See available subscription plans
2. **Create Payment Order** - Generate Razorpay order
3. Use Razorpay test credentials:
   - Card: `4111 1111 1111 1111`
   - CVV: Any 3 digits
   - Expiry: Any future date
4. **Verify Payment** - Complete payment verification

### 3. Video Management
1. **Get All Explore Videos** - Browse public videos
2. **Create Explore Video** - Upload your own video
3. **Get My Videos** - Manage your uploads
4. **Like/Unlike Video** - Interact with videos

### 4. File Processing
1. **Upload Image** - Upload reference image
2. **Upload Video** - Submit generation request
3. **Get Upload Status** - Monitor processing
4. **Get Upload Result** - Download result

## Environment Variables

### Automatic Variables
These are set automatically by the collection:
- `auth_token` - Set after successful login
- `user_id` - Set after successful login

### Manual Variables
Update these as needed:
- `base_url` - Your API server URL
- `test_email` - Test user email
- `test_password` - Test user password
- `test_name` - Test user name

## Testing Tips

### 1. Authentication
- Always start with **Register User** or **Login User**
- The token is automatically saved and used for authenticated requests
- Use **Logout User** to clear the token

### 2. Error Handling
- Check response status codes
- Review error messages in response body
- Use Postman console for detailed logs

### 3. Data Validation
- Update request bodies with valid data
- Check required fields in API documentation
- Use appropriate data types (strings, numbers, booleans)

### 4. Pagination
- Use `page` and `per_page` parameters for paginated endpoints
- Default page size is usually 12-20 items
- Check `total` and `last_page` in response

## Common Issues

### 1. 401 Unauthorized
- Check if `auth_token` is set
- Verify token is valid (try login again)
- Ensure Authorization header is present

### 2. 422 Validation Error
- Check request body format
- Verify required fields are present
- Ensure data types are correct

### 3. 500 Server Error
- Check server logs
- Verify environment variables
- Ensure database is accessible

### 4. CSRF Token Mismatch
- Some endpoints don't require CSRF tokens
- Check if endpoint is in API routes vs web routes
- Verify middleware configuration

## API Response Format

### Success Response
```json
{
  "success": true,
  "data": {
    // Response data
  },
  "message": "Operation successful"
}
```

### Error Response
```json
{
  "error": "Error message",
  "details": {
    // Additional error details
  }
}
```

### Paginated Response
```json
{
  "success": true,
  "data": {
    "items": [...],
    "current_page": 1,
    "per_page": 12,
    "total": 100,
    "last_page": 9
  }
}
```

## Testing Checklist

### ✅ Authentication
- [ ] Register new user
- [ ] Login with credentials
- [ ] Get user profile
- [ ] Update user profile
- [ ] Logout user

### ✅ Explore Videos
- [ ] Get all videos
- [ ] Get video by ID
- [ ] Create new video
- [ ] Update video
- [ ] Delete video
- [ ] Like/unlike video
- [ ] Get my videos

### ✅ Payments
- [ ] Get available plans
- [ ] Create payment order
- [ ] Verify payment (with test card)
- [ ] Get payment history

### ✅ File Processing
- [ ] Upload image
- [ ] Upload video
- [ ] Check upload status
- [ ] Get upload result

### ✅ User Management
- [ ] Get user stats
- [ ] Get user uploads
- [ ] Get user credits
- [ ] Get my files

## Support

For issues or questions:
1. Check the API documentation
2. Review server logs
3. Verify environment configuration
4. Test with different data sets

## Version History

- **v1.0.0** - Initial collection with all API endpoints
- Includes authentication, payments, videos, and admin APIs
- Comprehensive error handling and validation
- Mobile-specific payment endpoints
