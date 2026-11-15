package crm

import "fmt"

// APIError is the base error type returned by the CRM API.
type APIError struct {
	StatusCode int
	Code       string
	Message    string
	Errors     map[string]interface{}
}

// Error is an alias of APIError, kept for backward compatibility.
// The base type must not be named Error: an embedded field named Error
// shadows the promoted Error() method and the wrappers would no longer
// implement the error interface.
type Error = APIError

func (e *APIError) Error() string {
	if e.Code != "" {
		return fmt.Sprintf("crm: [%d %s] %s", e.StatusCode, e.Code, e.Message)
	}
	return fmt.Sprintf("crm: [%d] %s", e.StatusCode, e.Message)
}

// AuthenticationError represents a 401 response.
type AuthenticationError struct{ *APIError }

// AuthorizationError represents a 403 response.
type AuthorizationError struct{ *APIError }

// NotFoundError represents a 404 response.
type NotFoundError struct{ *APIError }

// ConflictError represents a 409 response.
type ConflictError struct{ *APIError }

// ValidationError represents a 400/422 response.
type ValidationError struct{ *APIError }

// RateLimitError represents a 429 response and carries Retry-After seconds.
type RateLimitError struct {
	*APIError
	RetryAfter int
}

// ServerError represents a 5xx response.
type ServerError struct{ *APIError }

func mapError(status int, code, message string, errs map[string]interface{}, retryAfter int) error {
	base := &APIError{StatusCode: status, Code: code, Message: message, Errors: errs}
	switch status {
	case 400, 422:
		return &ValidationError{base}
	case 401:
		return &AuthenticationError{base}
	case 403:
		return &AuthorizationError{base}
	case 404:
		return &NotFoundError{base}
	case 409:
		return &ConflictError{base}
	case 429:
		return &RateLimitError{APIError: base, RetryAfter: retryAfter}
	default:
		if status >= 500 {
			return &ServerError{base}
		}
		return base
	}
}
