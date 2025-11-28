# TransactiWar---Secure-Web-Application-Development-Security-Testing

[![Security](https://img.shields.io/badge/Security-Hardened-green.svg)](https://github.com/yourusername/transactiwar)
[![PHP](https://img.shields.io/badge/PHP-Backend-blue.svg)](https://www.php.net/)
[![Docker](https://img.shields.io/badge/Docker-Containerized-2496ED.svg)](https://www.docker.com/)

> **Battle for Security, Compete for Supremacy** - A hardened web application built for competitive security testing and vulnerability assessment.

## Project Overview

TransactiWar is a secure banking web application developed as part of CS6903 Network Security course at IIT Hyderabad. The project involved building a full-stack web application with robust security mechanisms, followed by a competitive "war game" where teams attempted to exploit vulnerabilities in opposing applications.

**Key Achievement**: Successfully defended against all attacks during the war game phase while identifying critical vulnerabilities (XSS, CSRF, Session Fixation) in competitor applications.

##  Features

### Core Functionality
- **User Authentication**: Secure registration and login with password-based authentication
- **Profile Management**: Editable profiles with bio, email, and profile picture uploads
- **Money Transfer System**: Peer-to-peer transactions with balance validation
- **Transaction History**: Complete audit trail of all financial transactions
- **User Search**: Search and discover other users on the platform
- **Activity Logging**: Comprehensive logging of user activities with timestamps and IP addresses

### Security Features
- ✅ **CSRF Protection**: Token-based validation for all state-changing operations
- ✅ **XSS Prevention**: Input sanitization using `htmlspecialchars()`
- ✅ **Session Security**: HTTP-only, secure, SameSite cookies with session fixation prevention
- ✅ **TLS/SSL Encryption**: End-to-end encryption for all communications
- ✅ **SQL Transaction Safety**: ACID-compliant transactions with row-level locking
- ✅ **File Upload Security**: Validated image uploads with LFI attack prevention
- ✅ **Input Validation**: Comprehensive validation rules for all user inputs
- ✅ **File System Access Control**: Restricted directory access via Apache configuration

## Technology Stack

**Frontend**
- HTML5, CSS3, JavaScript
- Bootstrap (UI Framework)

**Backend**
- PHP 8.x
- MySQL/PostgreSQL
- Apache HTTP Server with mod_rewrite
- GD Library (Image Processing)

**Infrastructure**
- Docker & Docker Compose
- PHP-FPM
- Apache HTTP Server as Reverse Proxy

## Architecture

```
┌─────────────────┐
│   Client        │
│   (HTTPS)       │
└────────┬────────┘
         │
    ┌────▼─────┐
    │  Apache  │
    │  (TLS)   │
    └────┬─────┘
         │
    ┌────▼─────┐
    │ PHP-FPM  │
    └────┬─────┘
         │
    ┌────▼─────┐
    │  MySQL   │
    └──────────┘
```

##  Quick Start

### Prerequisites
- Docker
- Docker Compose

### Installation

1. Clone the repository
```bash
git clone 
cd filename
```

2. Build and run with Docker
```bash
docker-compose up -d
```

3. Access the application
```
https://localhost:443
```

4. Create test accounts
```bash
./scripts/create_accounts.sh
```

## Security Mechanisms Implemented

### 1. CSRF Protection
- Unique tokens generated per request
- Server-side validation before processing
- Stored in PHP session storage

### 2. Authentication & Session Management
- Secure password hashing
- Session cookies with strict security flags
- Prevention of session fixation attacks

### 3. Input Validation & Sanitization
- Alphanumeric validation for usernames
- HTML entity encoding for output
- File type validation for uploads

### 4. SQL Security
- Prepared statements
- Transaction-based operations
- Row-level locking for race condition prevention
- Database constraints preventing negative balances

### 5. File Upload Security
- MIME type validation
- Image verification using GD library
- Isolated storage directory
- Size restrictions via php.ini

## War Game Results

During the competitive security assessment phase:

### Attacks Conducted
- **CSRF Attack on Team-16**: Successfully exploited unprotected profile update endpoint
- **XSS Attack on Team-9**: Exploited unsanitized username field with JavaScript injection
- **Session Fixation on Team-9**: Combined XSS with session manipulation for unauthorized access

### Defense Record
- **Zero Successful Attacks**: All security mechanisms held against competitor attacks
- **Vulnerabilities Identified**: None exploited during war game phase

## Project Outcomes

- ✅ Implemented 10+ security mechanisms from scratch
- ✅ Defended against all penetration attempts
- ✅ Successfully exploited 3 vulnerabilities in competitor applications
- ✅ Comprehensive security documentation and analysis

## 🔮 Future Enhancements

1. **Session Management**: Implement token refresh and timeout mechanisms
2. **Security Frameworks**: Migration to established security frameworks
3. **Credential Storage**: Encrypted credential management system
4. **Dependency Management**: Automated vulnerability scanning
5. **Data-at-Rest Encryption**: MySQL encryption configuration

## 📚 Key Learning Outcomes

- Practical implementation of OWASP security principles
- Understanding of common web vulnerabilities (XSS, CSRF, Session Fixation)
- Secure coding practices in PHP
- Docker containerization for security isolation
- Penetration testing methodologies

## 📖 References

Detailed references available in project documentation including MySQL transactions, Apache TLS configuration, PHP security best practices, and Docker deployment guides.

## 📄 License

This project was developed for educational purposes as part of CS6903 Network Security course at IIT Hyderabad.

## Acknowledgments

Department of Computer Science and Engineering, IIT Hyderabad

---

**Note**: This application was built without using any built-in security frameworks as per project requirements, demonstrating understanding of security principles from first principles.
