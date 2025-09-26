# Mobile Video Generation API Documentation

This document describes the mobile-specific video generation API endpoints for the Vidsmotion application.

## Overview

The Mobile Video Generation API provides endpoints specifically designed for mobile applications to generate AI-powered videos. It includes features for video generation, status checking, history management, and cancellation.

## Authentication

All endpoints require authentication using Laravel Sanctum. Include the Bearer token in the Authorization header:

```
Authorization: Bearer {your_token}
```

## Base URL

```
https://your-domain.com/api/mobile/video
```

## Endpoints

### 1. Generate Video

**POST** `/api/mobile/video/generate`

Starts a new video generation process.

#### Request Body

```json
{
  "prompt": "A beautiful sunset over mountains",
  "duration": 6,
  "image_url": "https://example.com/image.jpg",
  "is_recreate": false,
  "original_video_id": "optional_video_id",
  "original_video_title": "Optional Original Title"
}
```

#### Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `prompt` | string | Yes | Video description (5-500 characters) |
| `duration` | integer | No | Video duration in seconds (3-10, default: 6) |
| `image_url` | string | No | Reference image URL (max 2048 chars) |
| `is_recreate` | boolean | No | Whether this is recreating an existing video |
| `original_video_id` | string | No | ID of original video if recreating |
| `original_video_title` | string | No | Title of original video if recreating |

#### Success Response (201)

```json
{
  "success": true,
  "message": "Video generation started successfully",
  "data": {
    "upload_id": 123,
    "task_id": "task_abc123",
    "status": "processing",
    "prompt": "A beautiful sunset over mountains",
    "duration": 6,
    "required_credits": 50,
    "user_credits": 150,
    "created_at": "2024-01-15T10:30:00.000000Z",
    "estimated_completion": "2024-01-15T10:33:00.000000Z"
  }
}
```

#### Error Responses

**Insufficient Credits (402)**
```json
{
  "success": false,
  "message": "Insufficient credits. Please purchase credits to generate a video.",
  "data": {
    "required": 50,
    "current": 20,
    "shortfall": 30
  }
}
```

**Validation Error (422)**
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "prompt": ["The prompt field is required."],
    "duration": ["The duration must be between 3 and 10."]
  }
}
```

### 2. Check Video Status

**GET** `/api/mobile/video/status/{task_id}`

Checks the current status of a video generation task.

#### URL Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `task_id` | string | Yes | Task ID returned from generate endpoint |

#### Success Response (200)

```json
{
  "success": true,
  "data": {
    "upload_id": 123,
    "task_id": "task_abc123",
    "status": "completed",
    "progress_percent": 100,
    "video_url": "https://example.com/generated_video.mp4",
    "prompt": "A beautiful sunset over mountains",
    "duration": 6,
    "created_at": "2024-01-15T10:30:00.000000Z",
    "updated_at": "2024-01-15T10:33:00.000000Z",
    "user_credits": 100
  }
}
```

#### Status Values

| Status | Description |
|--------|-------------|
| `pending` | Task is queued for processing |
| `processing` | Video is being generated |
| `completed` | Video generation finished successfully |
| `failed` | Video generation failed |
| `cancelled` | Video generation was cancelled |

### 3. Get Video History

**GET** `/api/mobile/video/history`

Retrieves the user's video generation history.

#### Query Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `per_page` | integer | No | Items per page (1-50, default: 20) |
| `status` | string | No | Filter by status (pending, processing, completed, failed) |

#### Success Response (200)

```json
{
  "success": true,
  "data": {
    "items": [
      {
        "id": 123,
        "task_id": "task_abc123",
        "status": "completed",
        "prompt": "A beautiful sunset over mountains",
        "duration": 6,
        "video_url": "https://example.com/generated_video.mp4",
        "progress_percent": 100,
        "required_credits": 50,
        "created_at": "2024-01-15T10:30:00.000000Z",
        "updated_at": "2024-01-15T10:33:00.000000Z",
        "completed_at": "2024-01-15T10:33:00.000000Z"
      }
    ],
    "current_page": 1,
    "per_page": 20,
    "total": 1,
    "last_page": 1,
    "user_credits": 100
  }
}
```

### 4. Cancel Video Generation

**DELETE** `/api/mobile/video/cancel/{task_id}`

Cancels an ongoing video generation task.

#### URL Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `task_id` | string | Yes | Task ID to cancel |

#### Success Response (200)

```json
{
  "success": true,
  "message": "Video generation cancelled successfully",
  "data": {
    "upload_id": 123,
    "task_id": "task_abc123",
    "status": "cancelled"
  }
}
```

#### Error Response (400)

```json
{
  "success": false,
  "message": "Cannot cancel completed video generation"
}
```

## Credit System

### Credit Requirements

- **Base cost**: 50 credits per video
- **Long prompt bonus**: +10 credits for prompts over 80 characters
- **Total cost**: 50-60 credits per video

### Credit Deduction

Credits are deducted when the video generation is completed, not when it starts. This ensures users only pay for successful generations.

## Error Handling

### Common Error Codes

| Code | Description |
|------|-------------|
| 401 | Authentication required |
| 402 | Insufficient credits |
| 404 | Task not found |
| 422 | Validation error |
| 500 | Internal server error |

### Error Response Format

```json
{
  "success": false,
  "message": "Error description",
  "error": "Detailed error message (for 500 errors)"
}
```

## Usage Examples

### Complete Video Generation Flow

```javascript
// 1. Generate video
const generateResponse = await fetch('/api/mobile/video/generate', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer ' + token,
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    prompt: 'A beautiful sunset over mountains',
    duration: 6,
    image_url: 'https://example.com/sunset.jpg'
  })
});

const generateData = await generateResponse.json();
const taskId = generateData.data.task_id;

// 2. Poll for completion
const pollStatus = async () => {
  const statusResponse = await fetch(`/api/mobile/video/status/${taskId}`, {
    headers: {
      'Authorization': 'Bearer ' + token,
      'Accept': 'application/json'
    }
  });
  
  const statusData = await statusResponse.json();
  
  if (statusData.data.status === 'completed') {
    console.log('Video ready:', statusData.data.video_url);
    return statusData.data.video_url;
  } else if (statusData.data.status === 'failed') {
    console.error('Video generation failed');
    return null;
  } else {
    // Still processing, poll again in 5 seconds
    setTimeout(pollStatus, 5000);
  }
};

// Start polling
pollStatus();
```

### Get User's Video History

```javascript
const getHistory = async (status = null) => {
  const url = status 
    ? `/api/mobile/video/history?status=${status}&per_page=10`
    : '/api/mobile/video/history?per_page=10';
    
  const response = await fetch(url, {
    headers: {
      'Authorization': 'Bearer ' + token,
      'Accept': 'application/json'
    }
  });
  
  const data = await response.json();
  return data.data.items;
};

// Get all videos
const allVideos = await getHistory();

// Get only completed videos
const completedVideos = await getHistory('completed');
```

### Cancel Video Generation

```javascript
const cancelVideo = async (taskId) => {
  const response = await fetch(`/api/mobile/video/cancel/${taskId}`, {
    method: 'DELETE',
    headers: {
      'Authorization': 'Bearer ' + token,
      'Accept': 'application/json'
    }
  });
  
  const data = await response.json();
  
  if (data.success) {
    console.log('Video generation cancelled');
  } else {
    console.error('Failed to cancel:', data.message);
  }
};
```

## Rate Limiting

- **Generate Video**: 10 requests per minute per user
- **Status Check**: 60 requests per minute per user
- **History**: 30 requests per minute per user
- **Cancel**: 5 requests per minute per user

## Best Practices

### 1. Polling Strategy

- Poll status every 5-10 seconds
- Implement exponential backoff for failed requests
- Set maximum polling time (e.g., 10 minutes)

### 2. Error Handling

- Always check response status codes
- Handle network errors gracefully
- Provide user-friendly error messages

### 3. Credit Management

- Check user credits before starting generation
- Display credit balance in UI
- Handle insufficient credit scenarios

### 4. Video Management

- Cache video URLs for offline viewing
- Implement video download functionality
- Handle video URL expiration

## Integration Notes

### Mobile App Integration

1. **Authentication**: Use Laravel Sanctum for token-based auth
2. **Background Processing**: Use background tasks for status polling
3. **Offline Support**: Cache completed videos locally
4. **Progress Indicators**: Show real-time progress updates

### Webhook Support

The API supports webhooks for real-time status updates (optional):

```json
{
  "task_id": "task_abc123",
  "status": "completed",
  "video_url": "https://example.com/video.mp4",
  "timestamp": "2024-01-15T10:33:00.000000Z"
}
```

## Support

For technical support or questions about the Mobile Video Generation API, please contact the development team or refer to the main API documentation.
